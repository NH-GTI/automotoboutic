<?php

if (!defined('STORE_COMMANDER'))
{
    exit;
}

$idlist = Tools::getValue('idlist', '');
if (empty($idlist))
{
    exit;
}
$ids = explode(',', $idlist);
$id_group = Tools::getValue('id_group', '0');
if (empty($id_group))
{
    exit;
}

$action = Tools::getValue('action', '');
$id_lang = Tools::getValue('id_lang', '0');
$id_actual_shop = SCI::getSelectedShop();
$value = (bool) Tools::getValue('value');

switch ($action) {
    case 'present':
        addOrRemoveGroupToCustomerList($value, [$id_group], $ids);
        break;
    case 'default':
        if (!$value)
        {
            break;
        }
        foreach ($ids as $id)
        {
            $customer = new Customer((int) $id);
            $customer->id_default_group = $id_group;
            $updatedGroup = $customer->getGroups();
            if (!in_array($id_group, $updatedGroup))
            {
                $updatedGroup[] = (int) $id_group;
                $customer->updateGroup(array_values($updatedGroup));
            }
            $customer->update();
        }
        break;
    case 'mass_present':
        $groups = explode(',', $id_group);
        addOrRemoveGroupToCustomerList($value, $groups, $ids);
        break;
    default:
}

exit('success');

/**
 * @param bool  $actionAdd    0 remove 1 add
 * @param array $groupList
 * @param array $customerList
 *
 * @return void
 */
function addOrRemoveGroupToCustomerList($actionAdd, $groupList, $customerList)
{
    if (empty($groupList) || empty($customerList))
    {
        return;
    }

    foreach ($customerList as $idCustomer)
    {
        try
        {
            $customer = new Customer((int) $idCustomer);
            $updatedGroup = $customer->getGroups();
            foreach ($groupList as $idGroup)
            {
                switch (true)
                {
                    case $actionAdd && !in_array($idGroup, $updatedGroup):
                        $updatedGroup[] = (int) $idGroup;
                        break;
                    case !$actionAdd && $idGroup !== $customer->id_default_group && in_array($idGroup, $updatedGroup):
                        $toRemove = array_search($idGroup, $updatedGroup);
                        unset($updatedGroup[$toRemove]);
                        break;
                }
            }
            $customer->updateGroup(array_values($updatedGroup));
            $customer->hydrate($customer->getFields());
            $customer->update();
        }
        catch (Exception $e)
        {
            exit('id_customer: '.$idCustomer.'<br>'.$e->getMessage());
        }
    }
}
