<?

defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED === true || die();

use Bitrix\Highloadblock\HighloadBlockTable as HightloadLBT;
use Bitrix\Main,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\ObjectNotFoundException,
    Bitrix\Main\Context;
use Bitrix\Main\UI\PageNavigation;
use \Bitrix\Main\Data\Cache;


class UserAddress extends CBitrixComponent
{
    const TABLE_NAME_ADDRESS = 'HbUserAddress';
    const NAV_PARAMS_ID = 'nav-address';
    const BASE_CASHE_FOLDER_PATH = '/viktor/user.address/';
    const GRID_ID = 'table_list';
    const ARR_ERRORS = [
        'failHLTABLE' => "Не найден нужный HighLoadBlock с адресами",
    ];
    const CACHE_TIME_DEFAULT = 36000;
    const PAGE_SIZE_DEFAULT = 10;
    public $nav;
    protected $arFilter = array();

    public function onIncludeComponentLang()
    {
        $this->includeComponentLang(basename(__FILE__));
        Loc::loadMessages(__FILE__);
    }

    public function onPrepareComponentParams($params)
    {
        if (!isset($params['CACHE_TIME'])) {
            if (defined('CACHE_TIME')) {
                $params['CACHE_TIME'] = CACHE_TIME;
            } else {
                $params['CACHE_TIME'] = self::CACHE_TIME_DEFAULT;
            }
            $params['CACHE_GROUPS'] = "Y";
        }
        return $params;
    }

    /**
     * выполяет действия перед кешированием
     */
    protected function executeProlog()
    {
    }

    /**
     * выполняет действия после выполения компонента, например установка заголовков из кеша
     */
    protected function executeEpilog()
    {

    }

    public function executeComponent()
    {
        $this->executeProlog();
        $this->__includeComponent();
        $this->executeTemplate();
    }

    protected function prepareIsAjax()
    {
        return $this->request->isAjaxRequest();
    }

    /**
     * Определяем папку Кеширования для пользователя.
     * У каждого пользователя будет свой кеш, что даёт нам возможности чистить кеш для конретного пользователя при изменении элементов.
     * @param $userID
     * @return string
     */
    protected function getCashePathFolder($userID)
    {
        $baseCashePath = self::BASE_CASHE_FOLDER_PATH;
        if ($userID) {
            $baseCashePath .= md5($userID);
        } else {
            $baseCashePath .= md5("userNotAuth");
        }
        return $baseCashePath;
    }


    /**
     * Выполнение логики и подключение шаблона компонента.
     * @return array
     */
    protected function executeTemplate()
    {
        //Подготавливаем параметры и данные перед кешериованием
        global $USER;
        $userAuth = $USER->IsAuthorized();
        $userID = ($userAuth) ? $USER->GetID() : 0;
        $this->arParams['USER_ID'] = $userID;
        $nav = new \Bitrix\Main\UI\PageNavigation(self::NAV_PARAMS_ID);
        $nav->allowAllRecords(true);
        $nav->setPageSize(self::PAGE_SIZE_DEFAULT);
        $nav->initFromUri();
        $this->nav = $nav;
        $this->arParams['Nav'] = $nav;
        $baseCashePath = $this->getCashePathFolder($userID);
        $bitrixCashePath = \Bitrix\Main\Data\ManagedCache::getCompCachePath($baseCashePath);

        /**
         * Сделал такую логику кеша сознательно. Например для того, чтобы при изменнии элемента у пользователя
         * можно было отчищать только лишь кеш конкретного пользователя.
         * В теории можно было оставить просто управляемый кеш ORM запроса
         */
        if ($this->startResultCache($this->arParams['CACHE_TIME'], $this->arParams, $bitrixCashePath)) {
            $this->prepareResult();
            $this->includeComponentTemplate();
        }
        $this->executeEpilog();
        return $this->arResult;

    }

    /**
     * @param $params
     * @return array
     * @throws Main\ArgumentException
     * @throws Main\ObjectPropertyException
     */
    public function getDataFromHL($params)
    {
        try {
            $entityHL = HightloadLBT::compileEntity(self::TABLE_NAME_ADDRESS);
        } catch (Exception $e) {

            $this->arResult['ERRORS'][] = self::ARR_ERRORS['failHLTABLE'];
            $this->abortResultCache();
            return [];
        }
        $entityClass = $entityHL->getDataClass();
        $arDataObj = $entityClass::getList($params);
        $this->arResult['countAll'] = $arDataObj->getCount();
        if ($arData = $arDataObj->FetchAll()) {
            return $arData;
        } else {
            return [];
        }
    }

    /**
     * Формируем данные для передачи в компонет гридов
     * @param $arData
     * @return array
     */
    public function generateGridDataFormat($arData)
    {
        $listGrid = [];
        foreach ($arData as $row) {
            $listGrid[] = [
                'data' => [
                    "ID" => $row['ID'],
                    "UF_USER_ID" => $row['UF_USER_ID'],
                    "UF_ADDRESS" => $row['UF_ADDRESS'],
                    "UF_ACTIVE" => ($row['UF_ACTIVE']) ? "Да" : "Нет",
                ],
                'actions' => [
                    [
                        'text' => 'Просмотр',
                        'default' => true,
                        'onclick' => 'document.location.href="?op=view&id=' . $row['ID'] . '"'// можно сделать popup-окно просмотра
                    ]
                ]
            ];
        }
        return $listGrid;
    }

    protected function prepareResult()
    {
        $this->arResult = &$arResult;
        $nav = $this->nav;
        $userID = $this->arParams['USER_ID'];
        $arResult['USER_ID'] = ($userID) ?: false;
        if ($userID) {
            $params = [
                'select' => ['*'],
                'limit' => $nav->getLimit(),
                'offset' => $nav->getOffset(),
                'order' => [
                    "ID" => 'ASC'
                ],
                'filter' => [
                    'UF_USER_ID' => $userID
                ],
                'count_total' => 1,
            ];
//            if ($this->arParams['CACHE_TIME']) {
//                $params['cache']['ttl'] = $this->arParams['CACHE_TIME'];
//            }
            if ($this->arParams['SHOW_ONLY_ACTIVE'] == 'Y') {
                $params['filter']['UF_ACTIVE'] = ($this->arParams['SHOW_ONLY_ACTIVE'] == 'Y') ? true : false;
            }
            $arResult['DATA'] = $this->getDataFromHL($params);
            if ($arResult['DATA']) {
                $nav->setRecordCount($this->arResult['countAll']);
                $arResult['ITEMS_GRID'] = $this->generateGridDataFormat($arResult['DATA']);
            }
            $arResult['CurrentPage'] = $nav->getCurrentPage();
            $arResult['GRID_ID'] = self::GRID_ID;
            $arResult['Nav'] = $nav;
        }
    }

    /**
     * Код для генерации тестовых данных в справочник
     */
    public static function migrationDataExample()
    {
        global $USER;
        $entityHL = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity('HbUserAddress');
        $entityClass = $entityHL->getDataClass();
        for ($i = 0; $i < 100; $i++) {
            $result = $entityClass::add(array(
                'UF_USER_ID' => ($i % 3 == 0) ? 1 :  $USER->GetID(),
                'UF_ADDRESS' => 'г. Новый адресс ул. Числа  -  ' . $i,
                'UF_ACTIVE' => ($i % 3 == 0) ? 0 : 1
            ));
            echo "Строка добавлена " . (($result->isSuccess()) ? "Успешно" : "с ошибкой") . "<br>";
        }
    }

}
