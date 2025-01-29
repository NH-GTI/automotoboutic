<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

$autoloadPath = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoloadPath)) {
    require_once $autoloadPath;
}

use InstanWeb\Module\NHBusinessCentral\Model\Transaction;
use InstanWeb\Module\NHBusinessCentral\Model\TransactionDetail;
use InstanWeb\Module\NHBusinessCentral\Tab\TabManager;

class NHBusinessCentral extends Module
{
    public function __construct()
    {
        $this->name = 'nhbusinesscentral';
        $this->tab = 'migration_tools';
        $this->version = '1.0.0';
        $this->author = 'Haddad Nassim (GTI Sodifac) / InstanWeb';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.7.5.0',
            'max' => '8.99.99',
        ];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->trans('NH Business Central', [], 'Modules.NHBusinessCentral.Admin');
        $this->description = $this->trans('NH Business Central', [], 'Modules.NHBusinessCentral.Admin');
        $this->confirmUninstall = $this->trans('Are you sure you want to uninstall ?', [], 'Modules.NHBusinessCentral.Admin');
    }

    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }
        return (
            parent::install()
            && Transaction::installDb()
            && TransactionDetail::installDb()
            && (new TabManager($this))->installTabs()
            && $this->registerHook('actionValidateOrder')
            && $this->registerHook('actionOrderHistoryAddAfter')
        ); 
    }

    public function uninstall()
    {
        return (
            TransactionDetail::unInstallDb()
            && Transaction::unInstallDb()
            && (new TabManager($this))->removeTabs()
            && $this->removeConfiguration()
            && parent::uninstall()
        );
    }

    public function isUsingNewTranslationSystem()
    {
        return true;
    }    

    public function getContext()
    {
        return $this->context;
    }

    public function getTable()
    {
        return $this->table;
    }

    public function removeConfiguration()
    {
        if ($this->name) {
            $query = (new DbQuery())
                ->select('name')
                ->from('configuration')
                ->where('name LIKE "'.strtoupper($this->name).'_%"');
            if ($rows = Db::getInstance()->executeS($query)) {
                foreach ($rows as $row) {
                    Configuration::deleteByName($row['name']);
                }
            }
        }
        return true;
    }

    public function hookActionValidateOrder($params)
    {
        $repository = $this->get('instanweb.module.nhbusinesscentral.transaction.repository');

        if ($idTransaction = $repository->getIdsByTransaction('order')) {
            $idTransaction = array_pop($idTransaction);
        }

        $transaction = $repository->getById($idTransaction);

        $transactionName = strtolower($transaction->transaction);
        $process = $this->get("instanweb.module.nhbusinesscentral.transaction.$transactionName");
        $process->setIdTransaction($idTransaction)->addOrderAndProcess($transaction, $params['order']->id, 'Create');
    }

    public function hookActionOrderHistoryAddAfter($object)
    {
        $history = $object['order_history'];
        if ($history->id_order_state == Configuration::get('PS_OS_CANCELED')) {
            $repository = $this->get('instanweb.module.nhbusinesscentral.transaction.repository');

            if ($idTransaction = $repository->getIdsByTransaction('order')) {
                $idTransaction = array_pop($idTransaction);
            }
    
            $transaction = $repository->getById($idTransaction);
    
            $transactionName = strtolower($transaction->transaction);
            $process = $this->get("instanweb.module.nhbusinesscentral.transaction.$transactionName");
            $process->setIdTransaction($idTransaction)->addOrderAndProcess($transaction, $history->id_order, 'Cancel');
        }
    }
}
