<?php

class AdminExportController extends ModuleAdminController
{
    const DIR_MODULE = 'export';

    public function __construct()
    {
    	Tools::redirectAdmin('index.php?controller=AdminModules&configure='.self::DIR_MODULE.'&token='.Tools::getAdminTokenLite('AdminModules'));
	}

	public function viewAccess($disable = false)
	{
	    if (version_compare(_PS_VERSION_, '1.5.1.0', '<='))
	        return true;
	    return parent::viewAccess($disable);
	}

}