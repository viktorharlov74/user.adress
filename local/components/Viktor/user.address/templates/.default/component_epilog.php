<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $templateData
 * @var array $arParams
 * @var array $arResult
 * @var array $component
 * @var string $templateFolder
 * @global CMain $APPLICATION
 */
//Гриды добавил в эпилог а не в шаблон компонента, так как при включенном кешировании и при установленном режиме AJAX_MODE = Y
if ($arResult['USER_ID'] && empty($arResult['ERRORS'])) {
    if ($arResult['ITEMS_GRID']) {
        $APPLICATION->IncludeComponent(
            'bitrix:main.ui.grid',
            '',
            [
                'GRID_ID' => $arResult['GRID_ID'],
                'COLUMNS' => [
                    ['id' => 'ID', 'name' => 'ID', 'sort' => 'DATE', 'default' => true],
                    ['id' => 'UF_USER_ID', 'name' => 'ID пользователя', 'sort' => 'DATE', 'default' => true],
                    ['id' => 'UF_ACTIVE', 'name' => 'Активность', 'sort' => 'AMOUNT', 'default' => true],
                    ['id' => 'UF_ADDRESS', 'name' => 'Адресс', 'sort' => 'AMOUNT', 'default' => true]
                ],
                'TOTAL_ROWS_COUNT' => $arResult['countAll'],
                'ROWS' => $arResult['ITEMS_GRID'],
                'SHOW_ROW_CHECKBOXES' => true,
                'NAV_OBJECT' => $arResult['Nav'],
                'AJAX_MODE' => 'Y',
                'AJAX_ID' => \CAjax::getComponentID('bitrix:main.ui.grid', '.default', ''),
                'PAGE_SIZES' => [
                    ['NAME' => "2", 'VALUE' => '2'],
                    ['NAME' => "5", 'VALUE' => '5'],
                    ['NAME' => '10', 'VALUE' => '10'],
                    ['NAME' => '20', 'VALUE' => '20'],
                ],
                'AJAX_OPTION_JUMP' => 'N',
                'SHOW_CHECK_ALL_CHECKBOXES' => true,
                'SHOW_ROW_ACTIONS_MENU' => true,
                'SHOW_GRID_SETTINGS_MENU' => true,
                'SHOW_NAVIGATION_PANEL' => true,
                'SHOW_PAGINATION' => true,
                'SHOW_SELECTED_COUNTER' => true,
                'SHOW_TOTAL_COUNTER' => true,
                'ALLOW_COLUMNS_SORT' => true,
                'ALLOW_COLUMNS_RESIZE' => true,
                'ALLOW_HORIZONTAL_SCROLL' => true,
                'ALLOW_SORT' => true,
                'ALLOW_PIN_HEADER' => true,
                'AJAX_OPTION_HISTORY' => 'N'
            ],
            $component
        );
    } else {
        ?>
        <div class="data-not-found ">
            <p><?= Loc::getMessage('USER_NOT_ADR'); ?></p>
        </div>
        <?php
    }
}
