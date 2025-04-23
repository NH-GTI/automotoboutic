<?php class AdminCarmatSelectorAjaxController extends ModuleAdminController
{
    public function ajaxProcessGetData()
    {
        $page = (int)Tools::getValue('page', 1);
        $limit = (int)Tools::getValue('limit', 20);
        $dataType = Tools::getValue('dataType', '');

        $offset = ($page - 1) * $limit;
        
        switch($dataType) {
            case 'brands':
                $data = Db::getInstance()->executeS('
                    SELECT SQL_CALC_FOUND_ROWS id_carmatselector_brand as id, name
                    FROM `' . _DB_PREFIX_ . 'carmatselector_brand`
                    LIMIT ' . $offset . ', ' . $limit
                );
                $total = Db::getInstance()->getValue('SELECT FOUND_ROWS()');
                break;
            case 'models':
                $data = Db::getInstance()->executeS('
                    SELECT SQL_CALC_FOUND_ROWS id_carmatselector_model as id, name
                    FROM `' . _DB_PREFIX_ . 'carmatselector_model`
                    LIMIT ' . $offset . ', ' . $limit
                );
                $total = Db::getInstance()->getValue('SELECT FOUND_ROWS()');
                break;
            case 'versions':
                $data = Db::getInstance()->executeS('
                    SELECT SQL_CALC_FOUND_ROWS id_carmatselector_version as id, name
                    FROM `' . _DB_PREFIX_ . 'carmatselector_version`
                    LIMIT ' . $offset . ', ' . $limit
                );
                $total = Db::getInstance()->getValue('SELECT FOUND_ROWS()');
                break;
            case 'gammes':
                $data = Db::getInstance()->executeS('
                    SELECT SQL_CALC_FOUND_ROWS id_carmatselector_gamme as id, name
                    FROM `' . _DB_PREFIX_ . 'carmatselector_gamme`
                    LIMIT ' . $offset . ', ' . $limit
                );
                $total = Db::getInstance()->getValue('SELECT FOUND_ROWS()');
                break;
            case 'configurations':
                $data = Db::getInstance()->executeS('
                    SELECT SQL_CALC_FOUND_ROWS id_carmatselector_configuration as id, name
                    FROM `' . _DB_PREFIX_ . 'carmatselector_configuration`
                    LIMIT ' . $offset . ', ' . $limit
                );
                $total = Db::getInstance()->getValue('SELECT FOUND_ROWS()');
                break;
            case 'colors':
                $data = Db::getInstance()->executeS('
                    SELECT SQL_CALC_FOUND_ROWS id_carmatselector_color as id, name
                    FROM `' . _DB_PREFIX_ . 'carmatselector_color`
                    LIMIT ' . $offset . ', ' . $limit
                );
                $total = Db::getInstance()->getValue('SELECT FOUND_ROWS()');
                break;
            case 'attachments':
                $data = Db::getInstance()->executeS('
                    SELECT SQL_CALC_FOUND_ROWS id_carmatselector_attachment as id, name
                    FROM `' . _DB_PREFIX_ . 'carmatselector_attachment`
                    LIMIT ' . $offset . ', ' . $limit
                );
                $total = Db::getInstance()->getValue('SELECT FOUND_ROWS()');
                break;
        }

        die(json_encode([
            'success' => true,
            'data' => $data,
            'type' => $dataType,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $limit,
                'total' => $total
            ]
        ]));
    }
}