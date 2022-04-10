<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Пример использования с гридами");
?>
<?php
$APPLICATION->IncludeComponent(
    "viktor:user.address",
    "",
    Array(
        "SHOW_ONLY_ACTIVE" => 'Y',
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "36000",
        "SITE_ID" => SITE_ID,
        "CACHE_GROUPS" => "N", // Для разных групп пользователей - один кеш,
    ),
    false
);
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
