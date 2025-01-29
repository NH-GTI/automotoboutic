<?php

class SC_Agent
{
    private static $instance = null;

    public $id_employee;

    /** @var int Determine employee profile */
    // conservé en public pour le moment
    public $id_profile;

    /** @var int PS BO id_lang */
    public $ps_id_lang;

    /** @var int id_lang to use in application */
    // conservé en public pour le moment
    public $id_lang;

    /** @var string Lastname */
    public $lastname;

    /** @var string Firstname */
    public $firstname;

    /** @var string e-mail */
    public $email;

    /** @var datetime Password */
    public $last_passwd_gen;

    /** @var bool Status */
    public $active = 1;
    /**
     * @var EmployeeCore
     */
    private $employee;
    private $credentials;

    private function __construct()
    {
        if (!defined('SC_INSTALL_MODE'))
        {
            return false;
        }
        if (SC_INSTALL_MODE == 0)
        {
            global $cookie;
            $this->id_employee = $cookie->id_employee;
            $this->id_lang = $cookie->id_lang;
            $result = Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'employee` WHERE `id_employee` = '.(int) $this->id_employee);
            $this->id_profile = (int) $result['id_profile'];
            $this->lastname = psql($result['lastname']);
            $this->firstname = psql($result['firstname']);
            $this->email = psql($result['email']);
            $this->last_passwd_gen = psql($result['last_passwd_gen']);
            $this->ps_id_lang = (int) $result['id_lang'];
            $this->active = (int) $result['active'];
        }
        else
        {
            global $sc_cookie;
            if (empty($sc_cookie))
            {
                $sc_cookie = new Cookie('scAdmin');
                $result = (array) Context::getContext()->employee;
                $result['id_employee'] = $sc_cookie->ide = (int) $result['id'];
            }
            else
            {
                $result = Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'employee` WHERE `id_employee` = '.(int) $sc_cookie->ide);
            }
            $this->id_employee = (int) $result['id_employee'];
            $this->id_profile = (int) $result['id_profile'];
            $this->lastname = psql($result['lastname']);
            $this->firstname = psql($result['firstname']);
            $this->email = psql($result['email']);
            $this->last_passwd_gen = psql($result['last_passwd_gen']);
            $this->ps_id_lang = (int) $result['id_lang'];
            $this->active = (int) $result['active'];

            $sc_cookie->id_employee = (int) $result['id_employee'];
            $sc_cookie->passwd = $result['passwd'];

            $this->id_lang = (int) $result['id_lang'];
        }
    }

    /**
     * @param int $idEmployee
     * @return void
     */
    public function setEmployee($idEmployee){
        $employee = Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'employee` WHERE `id_employee` = '.(int) $idEmployee);
        $this->id_employee = (int) $employee['id_employee'];
        $this->id_profile = (int) $employee['id_profile'];
        $this->lastname = psql($employee['lastname']);
        $this->firstname = psql($employee['firstname']);
        $this->email = psql($employee['email']);
        $this->last_passwd_gen = psql($employee['last_passwd_gen']);
        $this->ps_id_lang = (int) $employee['id_lang'];
        $this->active = (int) $employee['active'];
        $this->id_lang = (int) $employee['id_lang'];
    }

    /**
     * @return SC_Agent
     */
    public static function getInstance()
    {
        if (!self::$instance)
        {
            self::$instance = new SC_Agent();
        }

        return self::$instance;
    }

    public function isAdmin()
    {
        return $this->id_profile == (int)_PS_ADMIN_PROFILE_
                   && $this->active;
    }

    public function getPSToken($tab)
    {
        if ($tab == 'AdminCatalog')
        {
            $tab = 'AdminProducts';
        }

        return Tools::getAdminToken($tab.(int)Tab::getIdFromClassName($tab).(int)$this->id_employee);
    }

    public function isLoggedBack()
    {
        return Context::getContext()->employee->isLoggedBack();
    }

    public function hasAuthOnShop($idShop)
    {
        return Context::getContext()->employee->hasAuthOnShop($idShop);
    }

    public function getDefaultShopID()
    {
        return Context::getContext()->employee->getDefaultShopID();
    }

    public function isSuperAdmin()
    {
        return Context::getContext()->employee->isSuperAdmin();
    }

    /**
     * @return array
     */
    public function getAllowedShops()
    {
        $dbquery = new DbQuery();
        $dbquery->select('DISTINCT(`id_shop`)')
                ->from('employee_shop')
                ->where('`id_employee` = '.(int)$this->id_employee);

        return Db::getInstance()
                    ->getLink()
                    ->query($dbquery)
                    ->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * get page to load interface following employee permissions
     * @param $calledPage
     * @return string
     */
    public function getPageByPermissions($calledPage)
    {

        if(!$this->isGranted(ScCredentials::TYPE_MENU,$calledPage)){
            return 'cat_tree';
        }
        $allowedInterfaces = $this->getAllowedInterfaces();
        if(in_array($calledPage, $allowedInterfaces))
        {
            return $calledPage;
        }

        if(count($allowedInterfaces) == 0)
        {
            return null;
        }

        return $allowedInterfaces[0];
    }

    /**
     * get all interfaces allowed by permissions
     * @return array
     */
    public function getAllowedInterfaces()
    {
        $allowedIntefaces = [];

        if (_r('MEN_CAT_CATALOG')) {
            $allowedIntefaces[] = 'cat_tree';

            if (_r('MEN_MAN_MANUFACTURERS')) {
                $allowedIntefaces[] = 'man_tree';
            }

            if (_r('MEN_SUP_SUPPLIERS')) {
                $allowedIntefaces[] = 'sup_tree';
            }
        }

        if (_r('MENU_ORD_ORDERS')) {
            $allowedIntefaces[] = 'ord_tree';
        }

        if (_r('MENU_CUS_CUSTOMERS')) {
            $allowedIntefaces[] = 'cus_tree';
        }

        if (_r('MENU_CMS_CMSPAGE')) {
            $allowedIntefaces[] = 'cms_tree';
        }

        $allowedIntefaces[] = 'cusm_tree';

        return $allowedIntefaces;
    }

    /**
     * @return int
     */
    public function getIdLang()
    {
        return $this->id_lang;
    }

    /**
     * @return int
     */
    public function getIdProfile()
    {
        return $this->id_profile;
    }

    /**
     * @return mixed
     */
    public function getCredentials()
    {
        if(!$this->credentials){
            $this->credentials = new ScCredentials();
        }
        return $this->credentials;
    }

    public function setLicenseData($licenseData){
        $this->getCredentials()->setLicenseData($licenseData);
    }

    public function getLicenseName(){
        return $this->getCredentials()->getLicenseName();
    }

    public function getLicenseId(){
        return $this->getCredentials()->getLicenseId();
    }


    public function getGrantedId($type, $id){
        return $this->getCredentials()->getGrantedId($type, $id);
    }

    public function isGranted($type, $id){
        return $this->getCredentials()->isGranted($type, $id);
    }

    public function filterGridViews($grids, $type){
        return $this->getCredentials()->filterGridViews($grids, $type);
    }

    public function hasPermission($key){
        return $this->getCredentials()->hasPermission($key);
    }


}
