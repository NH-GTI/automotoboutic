<?php
class AdminCarmatSelectorController extends ModuleAdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->bootstrap = true;
    }

    public function initContent()
    {
        parent::initContent();

        $brands = Db::getInstance()->executeS('SELECT id_carmatselector_brand as id, name FROM `' . _DB_PREFIX_ . 'carmatselector_brand`');
        $carbodies = Db::getInstance()->executeS('SELECT id_carmatselector_carbody as id, name FROM `' . _DB_PREFIX_ . 'carmatselector_carbody` WHERE active = 1');
        $attachments = Db::getInstance()->executeS('SELECT id_carmatselector_attachment as id, name FROM `' . _DB_PREFIX_ . 'carmatselector_attachment`');
        
        if(Tools::isSubmit('action')) {
            switch(Tools::getValue('action')) {
                case 'saveBrandForm':
                    $this->saveBrandForm();
                    break;
                case 'saveModelForm':
                    $this->saveModelForm();
                    break;
                case 'saveVersionForm':
                    $this->saveVersionForm();
                    break;
                default:
                    break;
            }
        }

        $this->context->smarty->assign([
            'carmatAdminData' => json_encode([
                // Initial data for admin
            ]),
            'adminAjaxUrl' => $this->context->link->getAdminLink('AdminCarmatSelector'),
            'urls' => [
                'base_url' => __PS_BASE_URI__
            ],
            'brands' => $brands,
            'carbodies' => $carbodies,
            'attachments' => $attachments,
            'token' => Tools::getAdminTokenLite('AdminCarmatSelector')
        ]);

        if(Tools::isSubmit('action')) {
            switch(Tools::getValue('action')) {
                case 'brandForm':
                    $this->setTemplate('brandForm.tpl');
                    break;
                case 'modelForm':
                    $this->setTemplate('modelForm.tpl');
                    break;
                case 'versionForm':
                    $this->setTemplate('form.tpl');
                    break;
                default:
                    $this->displayAjax();
                    break;
            }
        }
    }

    public function displayAjax()
    {
        $action = Tools::getValue('action');
        $brandId = Tools::getValue('brandId');

        // Handle AJAX requests from admin
        switch($action) {
            case 'getModels':
                $data = Db::getInstance()->executeS('SELECT id_carmatselector_model as id, name FROM `' . _DB_PREFIX_ . 'carmatselector_model`
                WHERE id_carmatselector_brand = '.(int)$brandId);
                break;
            default:
                $data = null;
        }

        die(json_encode([
            'success' => true,
            'data' => $data
        ]));
    }

    public function saveBrandForm(){
        $brandId = Tools::getValue('input-brand');
        $active = Tools::getValue('active');

        Db::getInstance()->execute('INSERT INTO `' . _DB_PREFIX_ . 'carmatselector_brand` (name, active) 
        values ("'.$brandId.'", '.(int)$active.')');
        
        $editProductLink = $this->context->link->getAdminLink('AdminModules'.'&configure=carmatselector&success=1&token='.Tools::getAdminTokenLite('AdminModules'), false);

        Tools::redirectAdmin($editProductLink);
    }

    public function saveModelForm(){
        $modelName = Tools::getValue('input-name');
        $brandId = Tools::getValue('select-brand');
        $active = Tools::getValue('active');

        Db::getInstance()->execute('INSERT INTO `' . _DB_PREFIX_ . 'carmatselector_model` (name, id_carmatselector_brand, active) 
        values ("'.$modelName.'", '.(int)$brandId.', '.(int)$active.')');
        
        $editProductLink = $this->context->link->getAdminLink('AdminModules'.'&configure=carmatselector&success=true&token='.Tools::getAdminTokenLite('AdminModules'), false);

        Tools::redirectAdmin($editProductLink);
    }

    public function saveVersionForm(){
        $carName = Tools::getValue('input-name');
        $modelId = Tools::getValue('select-model');
        $carbodyId = Tools::getValue('select-carbody');
        $attachmentId = Tools::getValue('select-attachment');
        $gabarit = Tools::getValue('gabarit');
        $active = Tools::getValue('active');

        Db::getInstance()->execute('INSERT INTO `' . _DB_PREFIX_ . 'carmatselector_version` (name, id_carmatselector_model, gabarit, attachment, carbody, active) 
        values ("'.$carName.'", '.(int)$modelId.', "'.$gabarit.'", '.(int)$attachmentId.', '.(int)$carbodyId.', '.(int)$active.')');
        
        $editProductLink = $this->context->link->getAdminLink('AdminModules'.'&configure=carmatselector&success=true&token='.Tools::getAdminTokenLite('AdminModules'), false);

        Tools::redirectAdmin($editProductLink);
    }
}