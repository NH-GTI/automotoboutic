<?php
if (!defined('STORE_COMMANDER')) { exit; }

    $action = Tools::getValue('a', '');

    switch ($action){
        case 'displaysetform':
?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="<?php echo SC_JQUERY; ?>"></script>
    <script>
        $(document).ready(function() {
            $('input[name="cgu_agreed"]').click(function(){
                var buttondisabled = $('input#saveLicense');
                if ($(this).is(':checked')) {
                    buttondisabled.removeAttr('disabled');
                } else {
                    buttondisabled.prop("disabled",true);
                }
            });
        });
    </script>
</head>
<body>
    <div id="licenseContent" style="padding:10px;font-family:Arial,sans-serif">
        <?php echo _l('License key:'); ?><br/><br/>
        <input type="text" id="newLicense" value="<?php echo SCI::getConfigurationValue('SC_LICENSE_KEY'); ?>"/><br/>
        <input type="checkbox" name="cgu_agreed"/> <a href="<?php echo CGU_LOCAL_PATH; ?>" target="_blank"><?php echo _l('I accept Terms & Conditions'); ?></a><br/><br/>
        <input type="button" id="saveLicense" value="<?php echo _l('Save'); ?>" onclick="$.get('index.php?ajax=1&act=core_license&a=set&license='+$('#newLicense').val(),function(data){if (data=='OK') {alert('<?php echo _l('License key saved! Thank you!', 1); ?>');window.location='index.php?licupdate=1';}else{$('#licenseContent').html(data);}});" disabled/><br/><br/><br/>
        <?php echo _l('The application will be reloaded.'); ?>
    </div>
</body>
</html>
<?php
            break;

        case 'set':
            $newLicense = Tools::getValue('license', '');
            $checkSupport = sc_file_get_contents('https://www.storecommander.com/files/getsupport_'.$newLicense.'_checkonly.php');
            if ($checkSupport == 'LICENSENOTFOUND')
            {
                exit(_l('Error: your license is not found on our server, please contact support.'));
            }
            if ($checkSupport == 'EXPIRED')
            {
                exit(_l('The period entitling you to download Store Commander updates and benefit from support has expired.').'<br/><br/>'.
                        _l('If you wish to benefit from future updates and new features, please log onto your account here:').'<br/>'.
                        '<a href="https://www.storecommander.com/'.($user_lang_iso == 'fr' ? '' : 'en/').'my-licenses.php" target="_blank">https://www.storecommander.com/'.($user_lang_iso == 'fr' ? '' : 'en/').'my-licenses.php</a>'.'<br/><br/>'.
                        _l('and click on Updates & support 6 or 12 months, or upgrade to a higher license plan.').'<br/><br/>'.
                        '<a href="http://support.storecommander.com" target="_blank">'._l('Please contact us for any sales inquiries you may have.').'</a>'
                        );
            }
            SCI::updateConfigurationValue('SC_LICENSE_KEY', $newLicense);
            SCI::updateConfigurationValue('SC_LICENSE_DATA', '');
            $local_settings['APP_TRENDS']['value'] = 1;
            saveSettings();
            echo 'OK';
            break;
}
