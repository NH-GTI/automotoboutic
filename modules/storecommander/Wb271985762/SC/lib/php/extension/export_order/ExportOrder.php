<?php

class ExportOrder extends ObjectModel
{
    public $id_extension_export_order;
    public $id_extension_export_order_filter;
    public $id_extension_export_order_mapping;
    public $id_lang;
    public $filename;
    public $token;
    public $date_last_export;
    public $date_add;
    public $date_upd;

    public static $definition = [
        'table' => SC_DB_PREFIX.'extension_export_order',
        'primary' => 'id_extension_export_order',
        'fields' => [
            'id_extension_export_order_filter' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId'],
            'id_extension_export_order_mapping' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId'],
            'id_lang' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true],
            'filename' => ['type' => self::TYPE_STRING, 'validate' => 'isFileName', 'required' => true],
            'token' => ['type' => self::TYPE_STRING, 'required' => true],
            'date_last_export' => ['type' => self::TYPE_DATE, 'validate' => 'isDate'],
            'date_add' => ['type' => self::TYPE_DATE, 'validate' => 'isDate'],
            'date_upd' => ['type' => self::TYPE_DATE, 'validate' => 'isDate'],
        ]
    ];

    public function __construct($id = null, $id_lang = null, $id_shop = null, $translator = null)
    {
        parent::__construct($id, $id_lang, $id_shop, $translator);

        if($this->date_last_export === '0000-00-00 00:00:00')
        {
            $this->date_last_export = null;
        }
    }

    public function add($auto_date = true, $null_values = false)
    {
        $this->token = generateToken();
        return parent::add($auto_date, $null_values); // TODO: Change the autogenerated stub
    }

    /**
     * @return array|false
     */
    public static function getExportList()
    {
        $query = new DbQuery();
        $query->select('*, IF(date_last_export <> "0000-00-00 00:00:00", date_last_export, NULL) as date_last_export');
        $query->from(self::$definition['table']);
        return Db::getInstance()->executeS($query);
    }

    /**
     * @return array|false
     */
    public static function getExportCronList()
    {
        $query = new DbQuery();
        $query->select('orde.*, IF(orde.date_last_export <> "0000-00-00 00:00:00", orde.date_last_export, NULL) as date_last_export');
        $query->select('IF(ordf.name IS NOT NULL, ordf.name, "--") AS filter_name');
        $query->select('IF(ordm.name IS NOT NULL, ordm.name, "--") AS mapping_name');
        $query->select('UPPER(l.iso_code) as iso');
        $query->from(self::$definition['table'], 'orde');
        $query->leftJoin(
            ExportOrderFilter::$definition['table'],
            'ordf',
            'ordf.`'.ExportOrderFilter::$definition['primary'].'` = orde.`'.ExportOrderFilter::$definition['primary'].'`'
        );
        $query->leftJoin(
            ExportOrderMapping::$definition['table'],
            'ordm',
            'ordm.`'.ExportOrderMapping::$definition['primary'].'` = orde.`'.ExportOrderMapping::$definition['primary'].'`'
        );
        $query->leftJoin(
            Language::$definition['table'],
            'l',
            'l.`'.Language::$definition['primary'].'` = orde.`'.Language::$definition['primary'].'`'
        );
        return Db::getInstance()->executeS($query);
    }

    public function getFullFileName()
    {
        return $this->filename.'.csv';
    }

    public function getFullPathFile()
    {
        return SC_CSV_EXPORT_DIR . 'orders/' . $this->getFullFileName();
    }

    /**
     * @param $id_filter
     * @return array|false
     */
    public static function getExportByFilter($id_filter)
    {
        $query = new DbQuery();
        $query->select(self::$definition['primary']);
        $query->from(self::$definition['table']);
        $query->where(ExportOrderFilter::$definition['primary'].' = '.(int)$id_filter);
        $exportList = Db::getInstance()->executeS($query);
        if($exportList)
        {
            return array_column($exportList, self::$definition['primary']);
        }
        return false;
    }

    /**
     * @param $id_mapping
     * @return array|false
     */
    public static function getExportByMapping($id_mapping)
    {
        $query = new DbQuery();
        $query->select(self::$definition['primary']);
        $query->from(self::$definition['table']);
        $query->where(ExportOrderMapping::$definition['primary'].' = '.(int)$id_mapping);
        $exportList = Db::getInstance()->executeS($query);
        if($exportList)
        {
            return array_column($exportList, self::$definition['primary']);
        }
        return false;
    }
}
