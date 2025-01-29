<?php

/**
 * @since   1.5.0
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

include_once(_PS_MODULE_DIR_.'lm_pushsurmesure/PushSurMesure.php');

class lm_pushsurmesure extends Module implements WidgetInterface
{
    protected $_html = '';
    protected $default_width = 779;
    protected $default_title = 'Sur mesure';
    protected $templateFile;

    public function __construct()
    {
        $this->name = 'lm_pushsurmesure';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Lemon-interactive';
        $this->need_instance = 0;
        $this->secure_key = Tools::encrypt($this->name);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->getTranslator()->trans('Lemon-interactive : Push Sur-mesure', array(), 'Modules.Imagepushr.Admin');
        $this->description = $this->trans('Displays push for the block "Sur mesure".', array(), 'Modules.Imagepushr.Admin');
       
        $this->ps_versions_compliancy = array('min' => '1.7.1.1', 'max' => _PS_VERSION_);

        $this->templateFile = 'module:lm_pushsurmesure/views/templates/hook/pushr.tpl';
    }

    /**
     * @see Module::install()
     */
    public function install()
    {
        /* Adds Module */
        if (parent::install() &&
            $this->registerHook('displayHomeTop') &&
            $this->registerHook('actionShopDataDuplication')
        ) {
            $shops = Shop::getContextListShopID();
            $shop_groups_list = array();

            /* Setup each shop */
            foreach ($shops as $shop_id) {
                $shop_group_id = (int)Shop::getGroupFromShop($shop_id, true);

                if (!in_array($shop_group_id, $shop_groups_list)) {
                    $shop_groups_list[] = $shop_group_id;
                }

                /* Sets up configuration */
                $res = Configuration::updateValue('lm_pushsurmesure_TITLE', $this->default_title, false, $shop_group_id, $shop_id);
            }

            /* Sets up Shop Group configuration */
            if (count($shop_groups_list)) {
                foreach ($shop_groups_list as $shop_group_id) {
                    $res &= Configuration::updateValue('lm_pushsurmesure_TITLE', $this->default_title, false, $shop_group_id);
                }
            }

            /* Sets up Global configuration */
            $res &= Configuration::updateValue('lm_pushsurmesure_TITLE', $this->default_title);

            /* Creates tables */
            $res &= $this->createTables();

            /* Adds samples */
            if ($res) {
                $this->installSamples();
            }

            return (bool)$res;
        }

        return false;
    }

    /**
     * Adds samples
     */
    protected function installSamples()
    {
        $languages = Language::getLanguages(false);
        for ($i = 1; $i <= 3; ++$i) {
            $push = new PushSurMesure();
            $push->position = $i;
            $push->active = 1;
            foreach ($languages as $language) {
                $push->title[$language['id_lang']] = 'Sample '.$i;
                $push->cta_wording[$language['id_lang']] = $this->getTranslator()->trans('View All Products', array(), 'Modules.Imagepushr.Admin');
                $push->cta_url[$language['id_lang']] = Context::getContext()->link->getBaseLink();
                $push->image[$language['id_lang']] = 'sample-'.$i.'.jpg';
            }
            $push->add();
        }
    }

    /**
     * @see Module::uninstall()
     */
    public function uninstall()
    {
        /* Deletes Module */
        if (parent::uninstall()) {
            /* Deletes tables */
            $res = $this->deleteTables();

            /* Unsets configuration */
            $res &= Configuration::deleteByName('lm_pushsurmesure_TITLE');

            return (bool)$res;
        }

        return false;
    }

    /**
     * Creates tables
     */
    protected function createTables()
    {
        /* Pushs */
        $res = (bool)Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'lm_pushsurmesure` (
                `id_push` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `id_shop` int(10) unsigned NOT NULL,
                PRIMARY KEY (`id_push`, `id_shop`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
        ');

        /* Pushs configuration */
        $res &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'lm_pushsurmesure_pushs` (
              `id_push` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `position` int(10) unsigned NOT NULL DEFAULT \'0\',
              `active` tinyint(1) unsigned NOT NULL DEFAULT \'0\',
              PRIMARY KEY (`id_push`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
        ');

        /* Pushs lang configuration */
        $res &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'lm_pushsurmesure_pushs_lang` (
              `id_push` int(10) unsigned NOT NULL,
              `id_lang` int(10) unsigned NOT NULL,
              `title` varchar(255) NOT NULL,
              `cta_wording` varchar(255) NOT NULL,
              `cta_url` varchar(255) NOT NULL,
              `image` varchar(255) NOT NULL,
              PRIMARY KEY (`id_push`,`id_lang`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
        ');

        return $res;
    }

    /**
     * deletes tables
     */
    protected function deleteTables()
    {
        $pushs = $this->getPushs();
        foreach ($pushs as $push) {
            $to_del = new PushSurMesure($push['id_push']);
            $to_del->delete();
        }

        return Db::getInstance()->execute('
            DROP TABLE IF EXISTS `'._DB_PREFIX_.'lm_pushsurmesure`, `'._DB_PREFIX_.'lm_pushsurmesure_pushs`, `'._DB_PREFIX_.'lm_pushsurmesure_pushs_lang`;
        ');
    }

    public function getContent()
    {
        $this->_html .= $this->headerHTML();

        /* Validate & process */
        if (Tools::isSubmit('submitPush') || Tools::isSubmit('delete_id_push') ||
            Tools::isSubmit('submitPushr') ||
            Tools::isSubmit('changeStatus')
        ) {
            if ($this->_postValidation()) {
                $this->_postProcess();
                $this->_html .= $this->renderForm();
                $this->_html .= $this->renderList();
            } else {
                $this->_html .= $this->renderAddForm();
            }

            $this->clearCache();
        } elseif (Tools::isSubmit('addPush') || (Tools::isSubmit('id_push') && $this->pushExists((int)Tools::getValue('id_push')))) {
            if (Tools::isSubmit('addPush')) {
                $mode = 'add';
            } else {
                $mode = 'edit';
            }

            if ($mode == 'add') {
                if (Shop::getContext() != Shop::CONTEXT_GROUP && Shop::getContext() != Shop::CONTEXT_ALL) {
                    $this->_html .= $this->renderAddForm();
                } else {
                    $this->_html .= $this->getShopContextError(null, $mode);
                }
            } else {
                $associated_shop_ids = PushSurMesure::getAssociatedIdsShop((int)Tools::getValue('id_push'));
                $context_shop_id = (int)Shop::getContextShopID();

                if ($associated_shop_ids === false) {
                    $this->_html .= $this->getShopAssociationError((int)Tools::getValue('id_push'));
                } elseif (Shop::getContext() != Shop::CONTEXT_GROUP && Shop::getContext() != Shop::CONTEXT_ALL && in_array($context_shop_id, $associated_shop_ids)) {
                    if (count($associated_shop_ids) > 1) {
                        $this->_html = $this->getSharedPushWarning();
                    }
                    $this->_html .= $this->renderAddForm();
                } else {
                    $shops_name_list = array();
                    foreach ($associated_shop_ids as $shop_id) {
                        $associated_shop = new Shop((int)$shop_id);
                        $shops_name_list[] = $associated_shop->name;
                    }
                    $this->_html .= $this->getShopContextError($shops_name_list, $mode);
                }
            }
        } else {
            $this->_html .= $this->getWarningMultishopHtml().$this->getCurrentShopInfoMsg().$this->renderForm();

            if (Shop::getContext() != Shop::CONTEXT_GROUP && Shop::getContext() != Shop::CONTEXT_ALL) {
                $this->_html .= $this->renderList();
            }
        }

        return $this->_html;
    }

    protected function _postValidation()
    {
        $errors = array();

        /* Validation for Pushr configuration */
        if (Tools::isSubmit('submitPushr')) {
            if (Tools::strlen(Tools::getValue('lm_pushsurmesure_TITLE')) == 0) {
                $errors[] = $this->getTranslator()->trans('The block title is required.', array(), 'Modules.Imagepushr.Admin');
            }
        } elseif (Tools::isSubmit('changeStatus')) {
            if (!Validate::isInt(Tools::getValue('id_push'))) {
                $errors[] = $this->getTranslator()->trans('Invalid push', array(), 'Modules.Imagepushr.Admin');
            }
        } elseif (Tools::isSubmit('submitPush')) {
            /* Checks state (active) */
            if (!Validate::isInt(Tools::getValue('active_push')) || (Tools::getValue('active_push') != 0 && Tools::getValue('active_push') != 1)) {
                $errors[] = $this->getTranslator()->trans('Invalid push state.', array(), 'Modules.Imagepushr.Admin');
            }
            /* Checks position */
            if (!Validate::isInt(Tools::getValue('position')) || (Tools::getValue('position') < 0)) {
                $errors[] = $this->getTranslator()->trans('Invalid push position.', array(), 'Modules.Imagepushr.Admin');
            }
            /* If edit : checks id_push */
            if (Tools::isSubmit('id_push')) {
                if (!Validate::isInt(Tools::getValue('id_push')) && !$this->pushExists(Tools::getValue('id_push'))) {
                    $errors[] = $this->getTranslator()->trans('Invalid push ID', array(), 'Modules.Imagepushr.Admin');
                }
            }
            /* Checks title/cta_url/cta_wording/description/image */
            $languages = Language::getLanguages(false);
            foreach ($languages as $language) {
                if (Tools::strlen(Tools::getValue('title_' . $language['id_lang'])) > 255) {
                    $errors[] = $this->getTranslator()->trans('The title is too long.', array(), 'Modules.Imagepushr.Admin');
                }
                if (Tools::strlen(Tools::getValue('cta_wording_' . $language['id_lang'])) > 255) {
                    $errors[] = $this->getTranslator()->trans('The caption is too long.', array(), 'Modules.Imagepushr.Admin');
                }
                if (Tools::strlen(Tools::getValue('cta_url_' . $language['id_lang'])) > 255) {
                    $errors[] = $this->getTranslator()->trans('The URL is too long.', array(), 'Modules.Imagepushr.Admin');
                }
                if (Tools::strlen(Tools::getValue('cta_url_' . $language['id_lang'])) > 0 && !Validate::isUrl(Tools::getValue('cta_url_' . $language['id_lang']))) {
                    $errors[] = $this->getTranslator()->trans('The URL format is not correct.', array(), 'Modules.Imagepushr.Admin');
                }
                if (Tools::getValue('image_' . $language['id_lang']) != null && !Validate::isFileName(Tools::getValue('image_' . $language['id_lang']))) {
                    $errors[] = $this->getTranslator()->trans('Invalid filename.', array(), 'Modules.Imagepushr.Admin');
                }
                if (Tools::getValue('image_old_' . $language['id_lang']) != null && !Validate::isFileName(Tools::getValue('image_old_' . $language['id_lang']))) {
                    $errors[] = $this->getTranslator()->trans('Invalid filename.', array(), 'Modules.Imagepushr.Admin');
                }
            }

            /* Checks title/cta_url/cta_wording/description for default lang */
            $id_lang_default = (int)Configuration::get('PS_LANG_DEFAULT');
            if (Tools::strlen(Tools::getValue('cta_url_' . $id_lang_default)) == 0) {
                $errors[] = $this->getTranslator()->trans('The URL is not set.', array(), 'Modules.Imagepushr.Admin');
            }
            if (!Tools::isSubmit('has_picture') && (!isset($_FILES['image_' . $id_lang_default]) || empty($_FILES['image_' . $id_lang_default]['tmp_name']))) {
                $errors[] = $this->getTranslator()->trans('The image is not set.', array(), 'Modules.Imagepushr.Admin');
            }
            if (Tools::getValue('image_old_'.$id_lang_default) && !Validate::isFileName(Tools::getValue('image_old_'.$id_lang_default))) {
                $errors[] = $this->getTranslator()->trans('The image is not set.', array(), 'Modules.Imagepushr.Admin');
            }
        } elseif (Tools::isSubmit('delete_id_push') && (!Validate::isInt(Tools::getValue('delete_id_push')) || !$this->pushExists((int)Tools::getValue('delete_id_push')))) {
            $errors[] = $this->getTranslator()->trans('Invalid push ID', array(), 'Modules.Imagepushr.Admin');
        }

        /* Display errors if needed */
        if (count($errors)) {
            $this->_html .= $this->displayError(implode('<br />', $errors));

            return false;
        }

        /* Returns if validation is ok */

        return true;
    }

    protected function _postProcess()
    {
        $errors = array();
        $shop_context = Shop::getContext();

        /* Processes Pushr */
        if (Tools::isSubmit('submitPushr')) {
            $shop_groups_list = array();
            $shops = Shop::getContextListShopID();

            foreach ($shops as $shop_id) {
                $shop_group_id = (int)Shop::getGroupFromShop($shop_id, true);

                if (!in_array($shop_group_id, $shop_groups_list)) {
                    $shop_groups_list[] = $shop_group_id;
                }

                $res = Configuration::updateValue('lm_pushsurmesure_TITLE', Tools::getValue('lm_pushsurmesure_TITLE'), false, $shop_group_id, $shop_id);
            }

            /* Update global shop context if needed*/
            switch ($shop_context) {
                case Shop::CONTEXT_ALL:
                    $res &= Configuration::updateValue('lm_pushsurmesure_TITLE', Tools::getValue('lm_pushsurmesure_TITLE'));
                    if (count($shop_groups_list)) {
                        foreach ($shop_groups_list as $shop_group_id) {
                            $res &= Configuration::updateValue('lm_pushsurmesure_TITLE', Tools::getValue('lm_pushsurmesure_TITLE'), false, $shop_group_id);
                        }
                    }
                    break;
                case Shop::CONTEXT_GROUP:
                    if (count($shop_groups_list)) {
                        foreach ($shop_groups_list as $shop_group_id) {
                            $res &= Configuration::updateValue('lm_pushsurmesure_TITLE', Tools::getValue('lm_pushsurmesure_TITLE'), false, $shop_group_id);
                        }
                    }
                    break;
            }

            $this->clearCache();

            if (!$res) {
                $errors[] = $this->displayError($this->getTranslator()->trans('The configuration could not be updated.', array(), 'Modules.Imagepushr.Admin'));
            } else {
                Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', true) . '&conf=6&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name);
            }
        } elseif (Tools::isSubmit('changeStatus') && Tools::isSubmit('id_push')) {
            $push = new PushSurMesure((int)Tools::getValue('id_push'));
            if ($push->active == 0) {
                $push->active = 1;
            } else {
                $push->active = 0;
            }
            $res = $push->update();
            $this->clearCache();
            $this->_html .= ($res ? $this->displayConfirmation($this->getTranslator()->trans('Configuration updated', array(), 'Admin.Notifications.Success')) : $this->displayError($this->getTranslator()->trans('The configuration could not be updated.', array(), 'Modules.Imagepushr.Admin')));
        } elseif (Tools::isSubmit('submitPush')) {
            /* Sets ID if needed */
            if (Tools::getValue('id_push')) {
                $push = new PushSurMesure((int)Tools::getValue('id_push'));
                if (!Validate::isLoadedObject($push)) {
                    $this->_html .= $this->displayError($this->getTranslator()->trans('Invalid push ID', array(), 'Modules.Imagepushr.Admin'));
                    return false;
                }
            } else {
                $push = new PushSurMesure();
            }
            /* Sets position */
            $push->position = (int)Tools::getValue('position');
            /* Sets active */
            $push->active = (int)Tools::getValue('active_push');

            /* Sets each langue fields */
            $languages = Language::getLanguages(false);

            foreach ($languages as $language) {
                $push->title[$language['id_lang']] = Tools::getValue('title_'.$language['id_lang']);
                $push->cta_url[$language['id_lang']] = Tools::getValue('cta_url_'.$language['id_lang']);
                $push->cta_wording[$language['id_lang']] = Tools::getValue('cta_wording_'.$language['id_lang']);
               

                /* Uploads image and sets push */
                $type = Tools::strtolower(Tools::substr(strrchr($_FILES['image_'.$language['id_lang']]['name'], '.'), 1));
                $imagesize = @getimagesize($_FILES['image_'.$language['id_lang']]['tmp_name']);
                if (isset($_FILES['image_'.$language['id_lang']]) &&
                    isset($_FILES['image_'.$language['id_lang']]['tmp_name']) &&
                    !empty($_FILES['image_'.$language['id_lang']]['tmp_name']) &&
                    !empty($imagesize) &&
                    in_array(
                        Tools::strtolower(Tools::substr(strrchr($imagesize['mime'], '/'), 1)), array(
                            'jpg',
                            'gif',
                            'jpeg',
                            'png'
                        )
                    ) &&
                    in_array($type, array('jpg', 'gif', 'jpeg', 'png'))
                ) {
                    $temp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS');
                    $salt = sha1(microtime());
                    if ($error = ImageManager::validateUpload($_FILES['image_'.$language['id_lang']])) {
                        $errors[] = $error;
                    } elseif (!$temp_name || !move_uploaded_file($_FILES['image_'.$language['id_lang']]['tmp_name'], $temp_name)) {
                        return false;
                    } elseif (!ImageManager::resize($temp_name, dirname(__FILE__).'/images/'.$salt.'_'.$_FILES['image_'.$language['id_lang']]['name'], null, null, $type)) {
                        $errors[] = $this->displayError($this->getTranslator()->trans('An error occurred during the image upload process.', array(), 'Admin.Notifications.Error'));
                    }
                    if (isset($temp_name)) {
                        @unlink($temp_name);
                    }
                    $push->image[$language['id_lang']] = $salt.'_'.$_FILES['image_'.$language['id_lang']]['name'];
                } elseif (Tools::getValue('image_old_'.$language['id_lang']) != '') {
                    $push->image[$language['id_lang']] = Tools::getValue('image_old_' . $language['id_lang']);
                }
            }

            /* Processes if no errors  */
            if (!$errors) {
                /* Adds */
                if (!Tools::getValue('id_push')) {
                    if (!$push->add()) {
                        $errors[] = $this->displayError($this->getTranslator()->trans('The push could not be added.', array(), 'Modules.Imagepushr.Admin'));
                    }
                } elseif (!$push->update()) {
                    $errors[] = $this->displayError($this->getTranslator()->trans('The push could not be updated.', array(), 'Modules.Imagepushr.Admin'));
                }
                $this->clearCache();
            }
        } elseif (Tools::isSubmit('delete_id_push')) {
            $push = new PushSurMesure((int)Tools::getValue('delete_id_push'));
            $res = $push->delete();
            $this->clearCache();
            if (!$res) {
                $this->_html .= $this->displayError('Could not delete.');
            } else {
                Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', true) . '&conf=1&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name);
            }
        }

        /* Display errors if needed */
        if (count($errors)) {
            $this->_html .= $this->displayError(implode('<br />', $errors));
        } elseif (Tools::isSubmit('submitPush') && Tools::getValue('id_push')) {
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', true) . '&conf=4&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name);
        } elseif (Tools::isSubmit('submitPush')) {
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', true) . '&conf=3&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name);
        }
    }

    public function renderWidget($hookName = null, array $configuration = [])
    {
        if (!$this->isCached($this->templateFile, $this->getCacheId())) {
            $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));
        }

        return $this->fetch($this->templateFile, $this->getCacheId());
    }

    public function getWidgetVariables($hookName = null, array $configuration = [])
    {
        $pushs = $this->getPushs(true);
        if (is_array($pushs)) {
            foreach ($pushs as &$push) {
                $push['sizes'] = @getimagesize((dirname(__FILE__) . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . $push['image']));
                if (isset($push['sizes'][3]) && $push['sizes'][3]) {
                    $push['size'] = $push['sizes'][3];
                }
            }
        }

        $config = $this->getConfigFieldsValues();

        return [
            'lm_pushsurmesure' => [
                'title' => $config['lm_pushsurmesure_TITLE'] ? $config['lm_pushsurmesure_TITLE'] : $this->getTranslator()->trans('Sur mesure', array(), 'Modules.Imagepushr.Admin'),
                'pushs' => $pushs,
            ],
        ];
    }

    public function clearCache()
    {
        $this->_clearCache($this->templateFile);
    }

    public function hookActionShopDataDuplication($params)
    {
        Db::getInstance()->execute('
            INSERT IGNORE INTO '._DB_PREFIX_.'lm_pushsurmesure (id_push, id_shop)
            SELECT id_push, '.(int)$params['new_id_shop'].'
            FROM '._DB_PREFIX_.'lm_pushsurmesure
            WHERE id_shop = '.(int)$params['old_id_shop']
        );
        $this->clearCache();
    }

    public function headerHTML()
    {
        if (Tools::getValue('controller') != 'AdminModules' && Tools::getValue('configure') != $this->name) {
            return;
        }

        $this->context->controller->addJqueryUI('ui.sortable');
        /* Style & js for fieldset 'pushs configuration' */
        $html = '<script type="text/javascript">
            $(function() {
                var $myPushs = $("#pushs");
                $myPushs.sortable({
                    opacity: 0.6,
                    cursor: "move",
                    update: function() {
                        var order = $(this).sortable("serialize") + "&action=updatePushsPosition";
                        $.post("'.$this->context->shop->physical_uri.$this->context->shop->virtual_uri.'modules/'.$this->name.'/ajax_'.$this->name.'.php?secure_key='.$this->secure_key.'", order);
                        }
                    });
                $myPushs.hover(function() {
                    $(this).css("cursor","move");
                    },
                    function() {
                    $(this).css("cursor","auto");
                });
            });
        </script>';

        return $html;
    }

    public function getNextPosition()
    {
        $row = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
            SELECT MAX(hss.`position`) AS `next_position`
            FROM `'._DB_PREFIX_.'lm_pushsurmesure_pushs` hss, `'._DB_PREFIX_.'lm_pushsurmesure` hs
            WHERE hss.`id_push` = hs.`id_push` AND hs.`id_shop` = '.(int)$this->context->shop->id
        );

        return (++$row['next_position']);
    }

    public function getPushs($active = null)
    {
        $this->context = Context::getContext();
        $id_shop = $this->context->shop->id;
        $id_lang = $this->context->language->id;

        $pushs = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT hs.`id_push` as id_push, hss.`position`, hss.`active`, hssl.`title`,
            hssl.`cta_url`, hssl.`cta_wording`, hssl.`image`
            FROM '._DB_PREFIX_.'lm_pushsurmesure hs
            LEFT JOIN '._DB_PREFIX_.'lm_pushsurmesure_pushs hss ON (hs.id_push = hss.id_push)
            LEFT JOIN '._DB_PREFIX_.'lm_pushsurmesure_pushs_lang hssl ON (hss.id_push = hssl.id_push)
            WHERE id_shop = '.(int)$id_shop.'
            AND hssl.id_lang = '.(int)$id_lang.
            ($active ? ' AND hss.`active` = 1' : ' ').'
            ORDER BY hss.position'
        );

        foreach ($pushs as &$push) {
            $push['image_url'] = $this->context->link->getMediaLink(_MODULE_DIR_.'lm_pushsurmesure/images/'.$push['image']);
        }

        return $pushs;
    }

    public function getAllImagesByPushsId($id_pushs, $active = null, $id_shop = null)
    {
        $this->context = Context::getContext();
        $images = array();

        if (!isset($id_shop))
            $id_shop = $this->context->shop->id;

        $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT hssl.`image`, hssl.`id_lang`
            FROM '._DB_PREFIX_.'lm_pushsurmesure hs
            LEFT JOIN '._DB_PREFIX_.'lm_pushsurmesure_pushs hss ON (hs.id_push = hss.id_push)
            LEFT JOIN '._DB_PREFIX_.'lm_pushsurmesure_pushs_lang hssl ON (hss.id_push = hssl.id_push)
            WHERE hs.`id_push` = '.(int)$id_pushs.' AND hs.`id_shop` = '.(int)$id_shop.
            ($active ? ' AND hss.`active` = 1' : ' ')
        );

        foreach ($results as $result)
            $images[$result['id_lang']] = $result['image'];

        return $images;
    }

    public function displayStatus($id_push, $active)
    {
        $title = ((int)$active == 0 ? $this->getTranslator()->trans('Disabled', array(), 'Admin.Global') : $this->getTranslator()->trans('Enabled', array(), 'Admin.Global'));
        $icon = ((int)$active == 0 ? 'icon-remove' : 'icon-check');
        $class = ((int)$active == 0 ? 'btn-danger' : 'btn-success');
        $html = '<a class="btn '.$class.'" href="'.AdminController::$currentIndex.
            '&configure='.$this->name.
                '&token='.Tools::getAdminTokenLite('AdminModules').
                '&changeStatus&id_push='.(int)$id_push.'" title="'.$title.'"><i class="'.$icon.'"></i> '.$title.'</a>';

        return $html;
    }

    public function pushExists($id_push)
    {
        $req = 'SELECT hs.`id_push` as id_push
                FROM `'._DB_PREFIX_.'lm_pushsurmesure` hs
                WHERE hs.`id_push` = '.(int)$id_push;
        $row = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($req);

        return ($row);
    }

    public function renderList()
    {
        $pushs = $this->getPushs();
        foreach ($pushs as $key => $push) {
            $pushs[$key]['status'] = $this->displayStatus($push['id_push'], $push['active']);
            $associated_shop_ids = PushSurMesure::getAssociatedIdsShop((int)$push['id_push']);
            if ($associated_shop_ids && count($associated_shop_ids) > 1) {
                $pushs[$key]['is_shared'] = true;
            } else {
                $pushs[$key]['is_shared'] = false;
            }
        }

        $this->context->smarty->assign(
            array(
                'link' => $this->context->link,
                'pushs' => $pushs,
                'image_baseurl' => $this->_path.'images/'
            )
        );

        return $this->display(__FILE__, 'list.tpl');
    }

    public function renderAddForm()
    {
        $fields_form = array(
            'form' => array(
                'cta_wording' => array(
                    'title' => $this->getTranslator()->trans('Push information', array(), 'Modules.Imagepushr.Admin'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'file_lang',
                        'label' => $this->getTranslator()->trans('Image', array(), 'Admin.Global'),
                        'name' => 'image',
                        'required' => true,
                        'lang' => true,
                        'desc' => sprintf($this->getTranslator()->trans('Maximum image size: %s.', array(), 'Admin.Global'), ini_get('upload_max_filesize'))
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->getTranslator()->trans('Title', array(), 'Admin.Global'),
                        'name' => 'title',
                        'required' => true,
                        'lang' => true,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->getTranslator()->trans('CTA Wording', array(), 'Modules.Imagepushr.Admin'),
                        'name' => 'cta_wording',
                        'required' => true,
                        'lang' => true,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->getTranslator()->trans('CTA URL', array(), 'Modules.Imagepushr.Admin'),
                        'name' => 'cta_url',
                        'required' => true,
                        'lang' => true,
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->getTranslator()->trans('Enabled', array(), 'Admin.Global'),
                        'name' => 'active_push',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->getTranslator()->trans('Yes', array(), 'Admin.Global')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->getTranslator()->trans('No', array(), 'Admin.Global')
                            )
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->getTranslator()->trans('Save', array(), 'Admin.Actions'),
                )
            ),
        );

        if (Tools::isSubmit('id_push') && $this->pushExists((int)Tools::getValue('id_push'))) {
            $push = new PushSurMesure((int)Tools::getValue('id_push'));
            $fields_form['form']['input'][] = array('type' => 'hidden', 'name' => 'id_push');
            $fields_form['form']['images'] = $push->image;

            $has_picture = true;

            foreach (Language::getLanguages(false) as $lang) {
                if (!isset($push->image[$lang['id_lang']])) {
                    $has_picture &= false;
                }
            }

            if ($has_picture) {
                $fields_form['form']['input'][] = array('type' => 'hidden', 'name' => 'has_picture');
            }
        }

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();
        $helper->module = $this;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitPush';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $language = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->tpl_vars = array(
            'base_url' => $this->context->shop->getBaseURL(),
            'language' => array(
                'id_lang' => $language->id,
                'iso_code' => $language->iso_code
            ),
            'fields_value' => $this->getAddFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
            'image_baseurl' => $this->_path.'images/'
        );

        $helper->override_folder = '/';

        $languages = Language::getLanguages(false);

        if (count($languages) > 1) {
            return $this->getMultiLanguageInfoMsg() . $helper->generateForm(array($fields_form));
        } else {
            return $helper->generateForm(array($fields_form));
        }
    }

    public function renderForm()
    {
        $fields_form = array(
            'form' => array(
                'cta_wording' => array(
                    'title' => $this->getTranslator()->trans('Settings', array(), 'Admin.Global'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->getTranslator()->trans('Block title', array(), 'Modules.Imagepushr.Admin'),
                        'name' => 'lm_pushsurmesure_TITLE',
                        'desc' => $this->getTranslator()->trans('The main title of the block.', array(), 'Modules.Imagepushr.Admin')
                    ),
                ),
                'submit' => array(
                    'title' => $this->getTranslator()->trans('Save', array(), 'Admin.Actions'),
                )
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitPushr';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($fields_form));
    }

    public function getConfigFieldsValues()
    {
        $id_shop_group = Shop::getContextShopGroupID();
        $id_shop = Shop::getContextShopID();

        return array(
            'lm_pushsurmesure_TITLE' => Tools::getValue('lm_pushsurmesure_TITLE', Configuration::get('lm_pushsurmesure_TITLE', null, $id_shop_group, $id_shop)),
        );
    }

    public function getAddFieldsValues()
    {
        $fields = array();

        if (Tools::isSubmit('id_push') && $this->pushExists((int)Tools::getValue('id_push'))) {
            $push = new PushSurMesure((int)Tools::getValue('id_push'));
            $fields['id_push'] = (int)Tools::getValue('id_push', $push->id);
        } else {
            $push = new PushSurMesure();
        }

        $fields['active_push'] = Tools::getValue('active_push', $push->active);
        $fields['has_picture'] = true;

        $languages = Language::getLanguages(false);

        foreach ($languages as $lang) {
            $fields['image'][$lang['id_lang']] = Tools::getValue('image_'.(int)$lang['id_lang']);
            $fields['title'][$lang['id_lang']] = Tools::getValue('title_'.(int)$lang['id_lang'], $push->title[$lang['id_lang']]);
            $fields['cta_url'][$lang['id_lang']] = Tools::getValue('cta_url_'.(int)$lang['id_lang'], $push->cta_url[$lang['id_lang']]);
            $fields['cta_wording'][$lang['id_lang']] = Tools::getValue('cta_wording_'.(int)$lang['id_lang'], $push->cta_wording[$lang['id_lang']]);
        }

        return $fields;
    }

    protected function getMultiLanguageInfoMsg()
    {
        return '<p class="alert alert-warning">'.
                    $this->getTranslator()->trans('Since multiple languages are activated on your shop, please mind to upload your image for each one of them', array(), 'Modules.Imagepushr.Admin').
                '</p>';
    }

    protected function getWarningMultishopHtml()
    {
        if (Shop::getContext() == Shop::CONTEXT_GROUP || Shop::getContext() == Shop::CONTEXT_ALL) {
            return '<p class="alert alert-warning">' .
            $this->getTranslator()->trans('You cannot manage pushs items from a "All Shops" or a "Group Shop" context, select directly the shop you want to edit', array(), 'Modules.Imagepushr.Admin') .
            '</p>';
        } else {
            return '';
        }
    }

    protected function getShopContextError($shop_contextualized_name, $mode)
    {
        if (is_array($shop_contextualized_name)) {
            $shop_contextualized_name = implode('<br/>', $shop_contextualized_name);
        }

        if ($mode == 'edit') {
            return '<p class="alert alert-danger">' .
            sprintf($this->getTranslator()->trans('You can only edit this push from the shop(s) context: %s', array(), 'Modules.Imagepushr.Admin'), $shop_contextualized_name) .
            '</p>';
        } else {
            return '<p class="alert alert-danger">' .
            sprintf($this->getTranslator()->trans('You cannot add pushs from a "All Shops" or a "Group Shop" context', array(), 'Modules.Imagepushr.Admin')) .
            '</p>';
        }
    }

    protected function getShopAssociationError($id_push)
    {
        return '<p class="alert alert-danger">'.
                        sprintf($this->getTranslator()->trans('Unable to get push shop association information (id_push: %d)', array(), 'Modules.Imagepushr.Admin'), (int)$id_push).
                '</p>';
    }


    protected function getCurrentShopInfoMsg()
    {
        $shop_info = null;

        if (Shop::isFeatureActive()) {
            if (Shop::getContext() == Shop::CONTEXT_SHOP) {
                $shop_info = sprintf($this->getTranslator()->trans('The modifications will be applied to shop: %s', array(),'Modules.Imagepushr.Admin'), $this->context->shop->name);
            } else if (Shop::getContext() == Shop::CONTEXT_GROUP) {
                $shop_info = sprintf($this->getTranslator()->trans('The modifications will be applied to this group: %s', array(), 'Modules.Imagepushr.Admin'), Shop::getContextShopGroup()->name);
            } else {
                $shop_info = $this->getTranslator()->trans('The modifications will be applied to all shops and shop groups', array(), 'Modules.Imagepushr.Admin');
            }

            return '<div class="alert alert-info">'.
                        $shop_info.
                    '</div>';
        } else {
            return '';
        }
    }

    protected function getSharedPushWarning()
    {
        return '<p class="alert alert-warning">'.
                    $this->getTranslator()->trans('This push is shared with other shops! All shops associated to this push will apply modifications made here', array(), 'Modules.Imagepushr.Admin').
                '</p>';
    }
}
