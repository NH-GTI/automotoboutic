<?php


namespace Sc;
use Tools;

class ScCleanup
{
    public function removeOldFiles()
    {
        if (!Tools::isSubmit('ajax') && !isset($CRON)) {
            $scToolsDir = realpath(SC_DIR.'../SC_TOOLS');
            $oldFiles = [
                $scToolsDir . '/fixmyprestashop',
                $scToolsDir . '/win_grids_editor',
                $scToolsDir . '/win_grids_editor_pro',
                $scToolsDir . '/pmcachemanager',
                $scToolsDir . '/multiplefeatures',
                $scToolsDir . '/segmentation',
                $scToolsDir . '/segmentproperties',
                $scToolsDir . '/affiliation',
                SC_DIR . 'lib/cat/cat_product_update.php',
                SC_DIR . 'lib/php/service/ConfigurationModel.php',
                SC_DIR . 'lib/php/service/ServiceConfigurationModel.php',
                SC_DIR . 'lib/php/service/ScService.php',
                SC_DIR . 'lib/php/service/ScServiceModelInterface.php',
                SC_DIR . 'lib/php/service/ScServiceInterface.php',
                SC_DIR . 'lib/php/service/ServiceModel.php',
                SC_DIR . 'lib/php/service/ServiceLockerModel.php',
                SC_DIR . 'lib/php/service/ScLogger/',
                SC_DIR . 'lib/php/service/Shippingbo/ShippingboService.php',
                SC_DIR . '/lib/cat/win-sbo/dashboard/cat_win-sbo_dashboard_stats.json.php',
                SC_DIR . '/lib/cat/win-sbo/dashboard/templates/block_title.html.php',
                SC_DIR . '/lib/cat/win-sbo/faq/',
                SC_DIR . '/lib/cat/win-sbo/forms/cat_win-sbo_forms_advanced_form.json.php',
                SC_DIR . '/lib/cat/win-sbo/forms/cat_win-sbo_forms_logs_form.json.php',
                SC_DIR . '/lib/cat/win-sbo/forms/cat_win-sbo_forms_api_form.json.php',
                SC_DIR . '/lib/cat/win-sbo/forms/cat_win-sbo_forms_export_form.json.php',
                SC_DIR . 'lib/php/service/Shippingbo/GridFactory/',
                SC_DIR . 'lib/php/service/Shippingbo/Entity/SboAccount.php',
                SC_DIR . 'lib/php/service/Shippingbo/Entity/SboShopRelation.php',
                SC_DIR . 'lib/php/service/Shippingbo/Model/AdditionalRefs.php',
                SC_DIR . 'lib/php/service/Shippingbo/Model/PackComponent.php',
                SC_DIR . 'lib/php/service/Shippingbo/Model/Product.php',
                SC_DIR . 'lib/php/service/Shippingbo/Model/ShopRelation.php',
                SC_DIR . 'lib/php/service/Shippingbo/Model/SboAccountModel.php',
                SC_DIR . 'lib/php/service/Shippingbo/Process/ShippingboCollect.php',
                SC_DIR . 'lib/php/service/Shippingbo/Process/ShippingboImport.php',
                SC_DIR . 'lib/php/service/Shippingbo/Process/ShippingboMatch.php',
                SC_DIR . 'lib/php/service/Shippingbo/Repository/SboAccountRepository.php',
                SC_DIR . 'lib/php/service/Shippingbo/Repository/Prestashop/ShopRelationRepository.php',
                SC_DIR . 'shared/Process/',
            ];
            foreach ($oldFiles as $filePath) {
                if(is_dir($filePath)){
                    self::removeDir($filePath);
                    continue;
                }

                if(is_file($filePath)){
                    unlink($filePath);
                }
            }
        }
    }

    protected function removeDir($directory)
    {
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory), \RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($iterator as $path) {

            if (preg_match('#[/\\\\]\.\.?$#', $path->__toString())) {
                continue;
            }

            if ($path->isDir()) {
                rmdir($path->__toString());
            } else {
                unlink($path->__toString());
            }
        }
        rmdir($directory);

    }
}