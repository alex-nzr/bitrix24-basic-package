<?php
/**
 * ==================================================
 * Developer: Alexey Nazarov
 * E-mail: jc1988x@gmail.com
 * Copyright (c) 2019 - 2023
 * ==================================================
 * bitrix24-basic-package - Options.php
 * 31.12.2023 02:12
 * ==================================================
 */
namespace ANZ\Bitrix24\BasicPackage\Component\Admin;

use ANZ\Bitrix24\BasicPackage\Config\Configuration;
use ANZ\Bitrix24\BasicPackage\Config\Constants;
use ANZ\Bitrix24\BasicPackage\Internal\Contract\Option\IOptionStorage;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UI\Extension;
use CAdminTabControl;
use CBitrixComponent;
use CFile;
use CUtil;
use Exception;

/**
 * @class Options
 * @package ANZ\Bitrix24\BasicPackage\Component\Admin
 */
abstract class Options extends CBitrixComponent//todo mb other class?
{
    protected string  $moduleId;
    protected array   $tabs;
    protected string $formAction;
    protected CAdminTabControl $tabControl;
    protected string $pageTitle = '';

    public function __construct()
    {
        parent::__construct();
        $this->moduleId = Configuration::getInstance()->getBasicModuleId();
        $this->formAction = $this->request->getRequestedPage() . "?" . http_build_query([
                'mid'  => htmlspecialcharsbx($this->request->get('mid')),
                'lang' => $this->request->get('lang')
            ]);
    }

    public function onPrepareComponentParams($arParams): array
    {
        if (!is_array($arParams))
        {
            return [];
        }

        $this->tabs = (key_exists('TABS', $arParams) && is_array($arParams['TABS'])) ? $arParams['TABS'] : [];
        $this->pageTitle = key_exists('PAGE_TITLE', $arParams) ? (string)$arParams['PAGE_TITLE'] : '';
        $this->tabControl = new CAdminTabControl('tabControl', $this->tabs);

        return $arParams;
    }

    /**
     * @return void
     */
    public function processRequest(): void
    {
        try
        {
            if ($this->request->isPost() && $this->request->getPost('Update') && check_bitrix_sessid())
            {
                foreach ($this->tabs as $arTab)
                {
                    foreach ($arTab['OPTIONS'] as $arOption)
                    {
                        if(!is_array($arOption) || !empty($arOption['note']))
                        {
                            continue;
                        }
                        $optionName = $arOption[0];
                        $optionValue = $this->request->getPost($optionName);

                        $fileOptionPostfix = IOptionStorage::OPTION_TYPE_FILE_POSTFIX;
                        if (str_ends_with($optionName, $fileOptionPostfix))
                        {
                            $currentValue = Option::get($this->moduleId, $optionName);
                            $optionValue = $this->request->getFile($optionName);

                            if (empty($optionValue['name']) && !empty($currentValue)){
                                continue;
                            }

                            $arFile = $optionValue;
                            $arFile['MODULE_ID'] = $this->moduleId;

                            if (strlen($arFile['name']) > 0)
                            {
                                $fid = CFile::SaveFile($arFile, $arFile['MODULE_ID']);
                                $optionValue = (int)$fid > 0 ? $fid : '';
                            }
                        }
                        Option::set(
                            $this->moduleId,
                            $optionName,
                            is_array($optionValue) ? json_encode($optionValue) : $optionValue
                        );
                    }
                }
            }
        }
        catch (Exception $e)
        {
            ShowError($e->getMessage());
        }
    }

    /**
     * @throws \Exception
     */
    public function executeComponent() //TODO cache and other(see BaseComponent in gpnsm or FactoryBased)
    {
        $this->processRequest();
        $this->drawHtml();
        return parent::executeComponent(); // TODO: Change the autogenerated stub
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function drawHtml(): void //TODO html to template
    {
        Extension::load(Constants::ADMIN_OPTIONS_EXTENSION);
        $this->tabControl->Begin();
        ?>
        <form method="POST" action="<?=$this->formAction?>" name="<?=$this->moduleId?>_settings" enctype="multipart/form-data">
            <?php
            foreach ($this->tabs as $arTab)
            {
                if(is_array($arTab['OPTIONS']))
                {
                    $this->tabControl->BeginNextTab();
                    $this->drawSettingsList($this->moduleId, $arTab['OPTIONS']);
                }
            }

            $this->tabControl->Buttons();?>
            <?=bitrix_sessid_post();?>
            <input type="submit" name="Update" value="<?=Loc::getMessage('MAIN_SAVE')?>" class="adm-btn-save">
            <input type="reset"  name="reset" value="<?=Loc::getMessage('MAIN_RESET')?>">
        </form>
        <?php
        $this->tabControl->End();
    }

    /**
     * @param string $moduleId
     * @param array $arParams
     * @return void
     * @throws \Exception
     */
    protected function drawSettingsList(string $moduleId, array $arParams): void
    {
        foreach($arParams as $option)
        {
            $this->drawSettingsRow($moduleId, $option);
        }
    }

    /**
     * @param string $module_id
     * @param $option
     * @return void
     * @throws \Exception
     */
    protected function drawSettingsRow(string $module_id, $option): void
    {
        if(empty($option))
        {
            return;
        }

        if(!is_array($option))
        {
            echo "<tr class='heading'><td colspan='2'>$option</td></tr>";
        }
        elseif(isset($option["note"]))
        {
            echo    "<tr>
                        <td colspan='2'>
                            <div class='adm-info-message-wrap'>
                                <div class='adm-info-message'>{$option["note"]}</div>
                            </div>
                        </td>
                    </tr>";
        }
        else
        {
            $currentVal = Option::get($module_id, $option[0], $option[2]);
            echo "<tr>";
            $this->renderTitle($option[1]);
            $this->renderInput($option, $currentVal ?? '');
            echo "</tr>";
        }
    }

    /**
     * @param string $text
     */
    protected function renderTitle(string $text): void
    {
        echo "<td><span>$text</span></td>";
    }

    /**
     * @param array $option
     * @param string $val
     * @return void
     * @throws \Exception
     */
    protected function renderInput(array $option, string $val): void
    {
        $name  = $option[0];
        $type  = $option[3];
        ?>
        <td style="width: 50%">
        <label for="<?=$name?>" class="module-option-label">
            <?
            switch ($type[0])
            {
                case "checkbox":
                    $checked = ($val === "Y") ? "checked" : '';
                    echo "<input type='checkbox' id='$name' name='$name' value='Y' $checked>";
                    break;
                case "text":
                case "password":
                    $autocomplete = $type[0] === 'password' ? 'autocomplete="new-password"' : '';
                    echo "<input type='$type[0]' id='$name' name='$name' value='$val' size='$type[1]' maxlength='255' $autocomplete>";
                    break;
                case "number":
                    echo "<input type='number' name='$name' value='$val' size='$type[1]' min='1' max='999999'>";
                    break;
                case "select":
                    $arr = is_array($type[1]) ? $type[1] : [];
                    echo "<select name='$name'>";
                    foreach($arr as $optionVal => $displayVal)
                    {
                        $selected = ($val === $optionVal) ? "selected" : '';
                        echo "<option value='$optionVal' $selected>$displayVal</option>";
                    }
                    echo "</select>";
                    break;
                case "multiselect":
                    $arr = is_array($type[1]) ? $type[1] : [];
                    $name .= '[]';
                    $arr_val = json_decode($val, true);
                    echo "<select name='$name' size='5' multiple>";
                    foreach($arr as $optionVal => $displayVal)
                    {
                        $selected = (in_array($optionVal, $arr_val)) ? "selected" : '';
                        echo "<option value='$optionVal' $selected>$displayVal</option>";
                    }
                    echo "</select>";
                    break;
                case "textarea":
                    echo "<textarea rows='$type[1]' cols='$type[2]' name='$name'>$val</textarea>";
                    break;
                case "staticText":
                    echo "<span>$val</span>";
                    break;
                case "file":
                    if (is_numeric($val) && (int)$val > 0)
                    {
                        $arFile = CFile::GetFileArray($val);
                        if (!empty($arFile))
                        {
                            $fileLink = $arFile['SRC'];
                            $fileName = $arFile['FILE_NAME'];
                            if (CFile::IsImage($fileName))
                            {
                                echo "<div>
                                        <a href='$fileLink' download='$fileName'><img src='$fileLink' alt='image' width='200'></a>
                                      </div>";
                            }
                            else
                            {
                                echo "<div>
                                        <a href='$fileLink' download='$fileName'>$fileName</a>
                                      </div>";
                            }
                        }
                    }
                    echo "<input type='file' id='$name' name='$name'/>";
                    break;
                case 'ui-selector':
                    if (!is_array($type[1]) || empty($type[1]['id']))
                    {
                        ShowError("Entity is empty in option $name");
                    }

                    $dialogPreselectedItems = [];
                    if (!empty($val))
                    {
                        $decodedValue = json_decode($val, true);

                        if (is_array($decodedValue) && !empty($decodedValue))
                        {
                            foreach ($decodedValue as $item)
                            {
                                if (is_array($item))
                                {
                                    $dialogPreselectedItems[] = [$type[1]['id'], (int)$item['ID']];
                                }
                            }
                        }
                    }

                    $containerId = 'container_' . $name;
                    $placementId = 'placement_' . $name;
                    ?>
                    <span id="<?=$containerId?>">
                    <span id="<?=$placementId?>"></span>
                </span>
                    <script>
                        BX.ready(function(){
                            const selectors = BX.namespace('Anz.Admin.UI.Options.UiSelectors');
                            selectors['<?=$name?>'] = BX.Anz.Admin.UI.Options.createUiSelectorOption(<?=CUtil::PhpToJSObject([
                                'OPTION_NAME' => $name,
                                'CONTAINER_ID' => $containerId,
                                'PLACEMENT_ID' => $placementId,
                                'MULTIPLE' => ($type[2] === 'Y'),
                                'PRESELECTED_ITEMS' => $dialogPreselectedItems,
                                'EVENT_HANDLERS' => is_array($type[3]) ? $type[3] : [],
                                'ENTITIES' => [$type[1]],
                            ])?>);
                        });
                    </script>
                    <?php
                    break;
            }
            ?>
        </label>
        </td><?
    }
}