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
use ANZ\Bitrix24\BasicPackage\Config\Options;
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

    private string $moduleId;

    public function __construct()
    {
        $this->moduleId = Configuration::getInstance()->getBasicModuleId();
    }

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

    /**
     * @return void
     */
    protected function addCustomCrmServices(): void
    {
        ServiceLocator::getInstance()->addInstance('crm.service.container', new Container());
        //ServiceLocator::getInstance()->addInstance('crm.service.router', new Router());
        //ServiceLocator::getInstance()->addInstance('crm.filter.factory', new FilterFactory());
    }

    /**
     * @return void
     */
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
     * TODO Вызывается на событии EventManager::ON_ENTITY_DETAILS_CONTEXT в зависимости от типа сущности
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
        if (!ModuleManager::isModuleInstalled($this->moduleId))
        {
            ModuleManager::add($this->moduleId);
        }

        $included = Loader::includeSharewareModule($this->moduleId);
        if ($included !== Loader::MODULE_INSTALLED)
        {
            switch ($included)
            {
                case Loader::MODULE_NOT_FOUND:
                    throw new Exception('Module '.$this->moduleId.' not found');
                case Loader::MODULE_DEMO:
                    throw new Exception('Module '.$this->moduleId.' in demo mode');
                case Loader::MODULE_DEMO_EXPIRED:
                    throw new Exception('Module '.$this->moduleId.' demo period expired');
            }
        }
    }

    /**
     * @throws \Exception
     */
    protected function updateUrlRewriter(): void
    {
        if ($this->needToUpdateUrlRewriteConditions())
        {
            foreach (Configuration::getInstance()->getUrlRewriteConditions() as $urlRewriteItem)
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
            }

            Option::set(
                $this->moduleId,
                Options\System::OPTION_KEY_LAST_UPDATED_CONDITIONS_HASH,
                Configuration::getInstance()->getUrlRewriteConditionsHash()
            );
        }
    }

    /**
     * @return bool
     */
    private function needToUpdateUrlRewriteConditions(): bool
    {
        $conditionsHash = Configuration::getInstance()->getUrlRewriteConditionsHash();
        $lastUpdatedConditionsHash = Option::get(
            $this->moduleId, Options\System::OPTION_KEY_LAST_UPDATED_CONDITIONS_HASH
        );

        return ($conditionsHash !== $lastUpdatedConditionsHash);
    }
}