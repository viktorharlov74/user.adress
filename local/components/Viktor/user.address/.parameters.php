<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/** @var array $arCurrentValues */

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arComponentParameters = array(
    'PARAMETERS' => array(
        'SHOW_ONLY_ACTIVE' => [
            'NAME' => Loc::getMessage('SHOW_ONLY_ACTIVE'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N',
        ]
    )
);
