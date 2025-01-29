<?php

namespace InstanWeb\Module\NHBusinessCentral\Tab;

use Module;
use Tab;
use Language;

class TabManager
{
    use TabConfigurationTrait;

    private $module;

    public function __construct(Module $module)
    {
        $this->module = $module;
    }

    public function installTabs() : bool
    {
        $res = true;
        foreach ($this->tabConfiguration() as $tab) {
            $icon = isset($tab['icon']) ? $tab['icon'] : null;
            $routeName = isset($tab['routeName']) ? $tab['routeName'] : null;
            $this->addTab($tab['className'], $tab['tabName'], $tab['tabNameFr'], $tab['parentClassName'], $routeName, $icon);
        }

        return $res;
    }

    private function addTab(string $className, string $tabName, string $tabNameFr, string $parentClassName, ?string $routeName = null, ?string $icon = null): bool
    {
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = $className;
        $tab->id_parent = $parentClassName ? (int)Tab::getIdFromClassName($parentClassName) : 0;
        $tab->module = $this->module->name;

        $tab->wording = $tabName;
        $tab->wording_domain = 'Modules.NHBusinessCentral.Admin';

        foreach (Language::getLanguages(true) as $lang) {
            switch($lang['id_lang']) {
                case 'fr' : $tab->name[$lang['id_lang']] = $tabNameFr; break;
                default: $tab->name[$lang['id_lang']] = $tabName;
            }
        }

        if (null !== $icon) {
            $tab->icon = $icon;
        }

        if (null !== $routeName) {
            $tab->route_name = $routeName;
        }

        return $tab->add();
    }

    public function removeTabs(): bool
    {
        foreach (Tab::getCollectionFromModule($this->module->name) as $tab) {
            $tab->delete();
        }

        return true;
    }
}
