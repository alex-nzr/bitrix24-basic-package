<?php
/**
 * ==================================================
 * Developer: Alexey Nazarov
 * E-mail: jc1988x@gmail.com
 * Copyright (c) 2019 - 2023
 * ==================================================
 * bitrix24-basic-package - ServiceManager.php
 * 16.12.2023 21:52
 * ==================================================
 */
namespace ANZ\Bitrix24\BasicPackage\Internal;


use ANZ\Bitrix24\BasicPackage\Config\Configuration;
use ANZ\Bitrix24\BasicPackage\Event\EventManager;
use ANZ\Bitrix24\BasicPackage\Internal\Traits\Singleton;
use ANZ\Bitrix24\BasicPackage\Service\Container;
use Bitrix\Main\Config\Configuration as BxConfig;
use Bitrix\Main\Config\Option;
use Bitrix\Main\DI\ServiceLocator;
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\UI\Extension;
use Bitrix\Main\UrlRewriter;
use Exception;


/**
 * @class ServiceManager
 * @package ANZ\Bitrix24\BasicPackage\Internal
 */
class ServiceManager
{
    use Singleton;

    /**
     * @return void
     * @throws \Exception
     */
    public function start(): void
    {
        EventManager::addEventHandlers();

        $this->updateUrlRewriter();
        $this->includeBasicModule();
        $this->includeDependentModules();
        $this->addCustomCrmServices();
        $this->addCustomSectionProvider();
        $this->includeDependentExtensions();

        Container::getInstance()->getLocalization()->loadMessages();
    }

    /**
     * @return void
     * @throws \Exception
     */
    protected function includeDependentModules(): void
    {
        $dependencies = [
            'crm', 'ui'
        ];

        foreach ($dependencies as $dependency) {
            if (!Loader::includeModule($dependency)){
                throw new Exception("Can not include module '$dependency'");
            }
        }
    }

    /**
     * @return void
     * @throws \Exception
     */
    protected function includeDependentExtensions(): void
    {
        $dependencies = [];
        Extension::load($dependencies);
    }

    protected function addCustomCrmServices(): void
    {
        ServiceLocator::getInstance()->addInstance('crm.service.container', new Container());
        //ServiceLocator::getInstance()->addInstance('crm.service.router', new Router());
        //ServiceLocator::getInstance()->addInstance('crm.filter.factory', new FilterFactory());
    }

    protected function addCustomSectionProvider(): void
    {
        $crmConfig = BxConfig::getInstance('crm');
        $customSectionConfig = $crmConfig->get('intranet.customSection');
        /*if (is_array($customSectionConfig))
        {
            $customSectionConfig['provider'] = CustomSectionProvider::class;
        }
        else
        {
            $customSectionConfig = [
                'provider' => CustomSectionProvider::class,
            ];
        }*/
        $crmConfig->add('intranet.customSection', $customSectionConfig);
    }

    /**
     * TODO Вызывается на событии EventManager::ON_ENTITY_DETAILS_CONTEXT
     * @return void
     * @throws \Exception
     */
    public static function addDetailPageExtensions(): void
    {
        $dependencies = [];
        Extension::load($dependencies);
    }

    /**
     * @throws \Exception
     */
    protected function includeBasicModule(): void
    {
        $moduleId = Configuration::getInstance()->getBasicModuleId();

        if (!ModuleManager::isModuleInstalled($moduleId))
        {
            ModuleManager::add($moduleId);
        }

        $included = Loader::includeSharewareModule($moduleId);
        if ($included !== Loader::MODULE_INSTALLED)
        {
            switch ($included)
            {
                case Loader::MODULE_NOT_FOUND:
                    throw new Exception('Module '.$moduleId.' not found');
                case Loader::MODULE_DEMO:
                    throw new Exception('Module '.$moduleId.' in demo mode');
                case Loader::MODULE_DEMO_EXPIRED:
                    throw new Exception('Module '.$moduleId.' demo period expired');
            }
        }
    }

    /**
     * @throws \Exception
     */
    protected function updateUrlRewriter(): void
    {
        $moduleId = Configuration::getInstance()->getBasicModuleId();
        $urlRewriteData = Configuration::getInstance()->getUrlRewriteConditions();
        $addedConditions = [];
        $addedConditionsJson = Option::get($moduleId, 'project_added_url_rewrite_conditions');
        if (is_string($addedConditionsJson) && !empty($addedConditionsJson))
        {
            $addedConditions = json_decode($addedConditionsJson, true);
            if (!is_array($addedConditions)){
                $addedConditions = [];
            }
        }

        foreach ($urlRewriteData as $urlRewriteItem)
        {
            $siteId = $urlRewriteItem['SITE_ID'];
            $condition = $urlRewriteItem['CONDITION'];
            if (empty($siteId))
            {
                $siteId = 's1';
            }

            if (empty($condition))
            {
                throw new Exception('Condition is empty');
            }

            //для обновления существующего правила нужно удалить его из массива, хранящегося в опции
            if (in_array($condition, $addedConditions))
            {
                continue;
            }

            $arResult = UrlRewriter::getList($siteId, ['CONDITION' => $condition]);
            if (!empty($arResult))
            {
                UrlRewriter::update(
                    $siteId,
                    ['CONDITION' => $condition],
                    [
                        'CONDITION' => $condition,
                        'ID' => $urlRewriteItem['ID'],
                        'PATH' => $urlRewriteItem['PATH'],
                        'RULE' => $urlRewriteItem['RULE']
                    ]
                );
            }
            else
            {
                UrlRewriter::add(
                    $siteId,
                    [
                        'CONDITION' => $condition,
                        'ID' => $urlRewriteItem['ID'],
                        'PATH' => $urlRewriteItem['PATH'],
                        'RULE' => $urlRewriteItem['RULE']
                    ]
                );
            }

            $addedConditions[] = $condition;
        }

        Option::set($moduleId, 'project_added_url_rewrite_conditions', json_encode($addedConditions));
    }
}