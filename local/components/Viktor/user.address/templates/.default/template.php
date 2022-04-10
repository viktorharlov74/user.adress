<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

$this->setFrameMode(true);

use Bitrix\Main\Localization\Loc;

Loc::loadLanguageFile(__FILE__);

?>
<?php

if ($arResult['ERRORS']) {
    foreach ($arResult['ERRORS'] as $error) {
        ?>
        <div class="error red">
            <p><?= $error ?></p>
        </div>
        <?php
    }
}
if (!$arResult['USER_ID']) {
    ?>
    <div class="data-not-found ">
        <?=Loc::getMessage('USER_NOT_AUTH');?>
    </div>
    <?
}

?>
