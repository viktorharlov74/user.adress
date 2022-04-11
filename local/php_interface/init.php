<?php
define(
    "LOG_FILENAME",
    \Bitrix\Main\Application::getDocumentRoot() .
    "/local/logs/bx_" .
    date("Ymd") .
    ".log"
);


use Bitrix\Highloadblock\HighloadBlockTable as HightloadBT;
use Bitrix\Main\Entity;

$eventManager = \Bitrix\Main\EventManager::getInstance();

$eventManager->addEventHandler('', 'HbUserAddressOnAfterAdd', 'HbUserAddressUpdate');
$eventManager->addEventHandler('', 'HbUserAddressOnAfterUpdate', 'HbUserAddressUpdate');
$eventManager->addEventHandler('', 'HbUserAddressOnBeforeDelete', 'HbUserAddressUpdate');

/**
 *  Код для отчистки кеша компонента user.address
 * @param Entity\Event $event
 * @return bool
 * @throws \Bitrix\Main\ArgumentException
 */
function HbUserAddressUpdate(Entity\Event $event)
{
    $arFields = $event->getParameter("fields");
    $userID = $arFields['UF_USER_ID']['VALUE'];
    $allParameters = $event->getParameters();
    $cacheObj = \Bitrix\Main\Data\Cache::createInstance();
    $baseCachePath = "/s1/viktor/user.address/";
    if ($userID) {
        $baseCachePath .= md5($userID);
        $cacheObj->clearCache($baseCachePath);
        $event->getEntity()->cleanCache();
    } elseif ($allParameters['id']['ID']) {
        try {
            $entityHL = HightloadBT::compileEntity('HbUserAddress');
        } catch (Exception $e) {
            return true;
        }
        $entityClass = $entityHL->getDataClass();
        $arDataObj = $entityClass::getList([
            'select' => ['UF_USER_ID'],
            'order' => [
                "ID" => 'ASC'
            ],
            'filter' => [
                'ID' => $allParameters['id']['ID']
            ]
        ]);
        $arData = $arDataObj->fetch();
        if ($arData['UF_USER_ID']) {
            $baseCachePath .= md5($arData['UF_USER_ID']);
            $cacheObj->clearCache($baseCachePath);
            $event->getEntity()->cleanCache();
        }
    }
    return true;

}

?>
