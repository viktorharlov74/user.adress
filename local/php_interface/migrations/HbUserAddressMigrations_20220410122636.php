<?php

namespace Sprint\Migration;


class HbUserAddressMigrations_20220410122636 extends Version
{
    protected $description = "HbUserAddressMigrations";

    protected $moduleVersion = "3.29.3";

    /**
     * @return bool|void
     * @throws Exceptions\HelperException
     */
    public function up()
    {
        $helper = $this->getHelperManager();
        $hlblockId = $helper->Hlblock()->saveHlblock(array(
            'NAME' => 'HbUserAddress',
            'TABLE_NAME' => 'hb_user_address',
            'LANG' =>
                array(
                    'ru' =>
                        array(
                            'NAME' => 'HbUserAddressTable',
                        ),
                    'en' =>
                        array(
                            'NAME' => 'HbUserAddressTable',
                        ),
                ),
        ));
        $helper->Hlblock()->saveField($hlblockId, array(
            'FIELD_NAME' => 'UF_USER_ID',
            'USER_TYPE_ID' => 'integer',
            'XML_ID' => 'UF_USER_ID',
            'SORT' => '100',
            'MULTIPLE' => 'N',
            'MANDATORY' => 'Y',
            'SHOW_FILTER' => 'I',
            'SHOW_IN_LIST' => 'Y',
            'EDIT_IN_LIST' => 'Y',
            'IS_SEARCHABLE' => 'N',
            'SETTINGS' =>
                array(
                    'SIZE' => 20,
                    'MIN_VALUE' => 0,
                    'MAX_VALUE' => 0,
                    'DEFAULT_VALUE' => '',
                ),
            'EDIT_FORM_LABEL' =>
                array(
                    'en' => 'UF_USER_ID',
                    'ru' => 'UF_USER_ID',
                ),
            'LIST_COLUMN_LABEL' =>
                array(
                    'en' => 'UF_USER_ID',
                    'ru' => 'UF_USER_ID',
                ),
            'LIST_FILTER_LABEL' =>
                array(
                    'en' => 'UF_USER_ID',
                    'ru' => 'UF_USER_ID',
                ),
            'ERROR_MESSAGE' =>
                array(
                    'en' => '',
                    'ru' => '',
                ),
            'HELP_MESSAGE' =>
                array(
                    'en' => '',
                    'ru' => '',
                ),
        ));
        $helper->Hlblock()->saveField($hlblockId, array(
            'FIELD_NAME' => 'UF_ADDRESS',
            'USER_TYPE_ID' => 'string',
            'XML_ID' => 'UF_ADDRESS',
            'SORT' => '100',
            'MULTIPLE' => 'N',
            'MANDATORY' => 'N',
            'SHOW_FILTER' => 'N',
            'SHOW_IN_LIST' => 'Y',
            'EDIT_IN_LIST' => 'Y',
            'IS_SEARCHABLE' => 'N',
            'SETTINGS' =>
                array(
                    'SIZE' => 20,
                    'ROWS' => 1,
                    'REGEXP' => '',
                    'MIN_LENGTH' => 0,
                    'MAX_LENGTH' => 0,
                    'DEFAULT_VALUE' => '',
                ),
            'EDIT_FORM_LABEL' =>
                array(
                    'en' => 'UF_ADDRESS',
                    'ru' => 'UF_ADDRESS',
                ),
            'LIST_COLUMN_LABEL' =>
                array(
                    'en' => 'UF_ADDRESS',
                    'ru' => 'UF_ADDRESS',
                ),
            'LIST_FILTER_LABEL' =>
                array(
                    'en' => 'UF_ADDRESS',
                    'ru' => 'UF_ADDRESS',
                ),
            'ERROR_MESSAGE' =>
                array(
                    'en' => '',
                    'ru' => '',
                ),
            'HELP_MESSAGE' =>
                array(
                    'en' => '',
                    'ru' => '',
                ),
        ));
        $helper->Hlblock()->saveField($hlblockId, array(
            'FIELD_NAME' => 'UF_ACTIVE',
            'USER_TYPE_ID' => 'boolean',
            'XML_ID' => 'UF_ACTIVE',
            'SORT' => '100',
            'MULTIPLE' => 'N',
            'MANDATORY' => 'N',
            'SHOW_FILTER' => 'N',
            'SHOW_IN_LIST' => 'Y',
            'EDIT_IN_LIST' => 'Y',
            'IS_SEARCHABLE' => 'N',
            'SETTINGS' =>
                array(
                    'DEFAULT_VALUE' => 1,
                    'DISPLAY' => 'CHECKBOX',
                    'LABEL' =>
                        array(
                            0 => '',
                            1 => '',
                        ),
                    'LABEL_CHECKBOX' => '',
                ),
            'EDIT_FORM_LABEL' =>
                array(
                    'en' => 'UF_ACTIVE',
                    'ru' => 'UF_ACTIVE',
                ),
            'LIST_COLUMN_LABEL' =>
                array(
                    'en' => 'UF_ACTIVE',
                    'ru' => 'UF_ACTIVE',
                ),
            'LIST_FILTER_LABEL' =>
                array(
                    'en' => 'UF_ACTIVE',
                    'ru' => 'UF_ACTIVE',
                ),
            'ERROR_MESSAGE' =>
                array(
                    'en' => '',
                    'ru' => '',
                ),
            'HELP_MESSAGE' =>
                array(
                    'en' => '',
                    'ru' => '',
                ),
        ));
        /**
         * Генерация тестовых данных
         */
        global $USER;
        $entityHL = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity('HbUserAddress');
        $entityClass = $entityHL->getDataClass();
        for ($i = 0; $i < 100; $i++) {
            $result = $entityClass::add(array(
                'UF_USER_ID' => ($i % 3 == 0) ? 1 : $USER->GetID(),
                'UF_ADDRESS' => 'г. Новый адресс ул. Числа номер -  ' . $i,
                'UF_ACTIVE' => ($i % 3 == 0) ? 0 : 1
            ));
            echo "Строка добавлена " . (($result->isSuccess()) ? "Успешно" : "с ошибкой") . "<br>";
        }
    }

    public function down()
    {
        //your code ...
    }
}
