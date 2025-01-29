<?php
if (!defined('STORE_COMMANDER')) { exit; }

    echo '<body style="font-family:Arial,sans-serif;">';
    echo '<h2>'._l('Store Commander translations update', 1).'</h2>';

    if (!file_exists('lang'))
    {
        @mkdir('lang');
    }

    // check rights
    $notWritableFiles = array();
    $writePermissions = octdec('0'.substr(decoct(fileperms(realpath(SC_PS_PATH_DIR.'img/p'))), -3));
    $writePermissionsOCT = substr(decoct(fileperms(realpath(SC_PS_PATH_DIR.'img/p'))), -3);
    dirCheckWritable(SC_DIR.'lang/', $notWritableFiles);
    if (count($notWritableFiles))
    {
        $dirStrSize = strlen(SC_PS_PATH_ADMIN_DIR);
        echo _l('Some files are not writable, please change the permission of these files:').' ('.$writePermissionsOCT.')'.'<br/><br/>';
        foreach ($notWritableFiles as $k => $file)
        {
            echo substr($file, $dirStrSize).'<br/>';
            if ($k > 20)
            {
                echo '...';
                exit;
            }
        }
        exit;
    }

    $tmp_folder = SC_DIR.'sc_update_tmp';
    $fileLanguageZipPath = $tmp_folder.'/SCLanguages.zip';

    if (!is_dir($tmp_folder))
    {
        $old = umask(0);
        mkdir($tmp_folder, $writePermissions);
        umask($old);
    }

    echo _l('Updating...').'<br/><br/>';
    echo _l('Downloading pack').' SCLanguages.zip...<br/>';
    $url = 'https://www.storecommander.com/files/SCLanguages.zip';
    $data = sc_file_get_contents($url);
    if (Tools::getValue('DEBUG', 0) == 1)
    {
        echo $url.'<br/>';
    }
    echo ' ('.(strlen($data) / 1000).'K)<br/>';
    file_put_contents($fileLanguageZipPath, $data);
    if (filesize($fileLanguageZipPath) == 0)
    {
        echo _l('Error with archive (filesize = 0 Ko)').'<br/>';
    }
    else
    {
        echo _l('Extracting zip archive...').'<br/>';
        $old = umask(0);
        Tools::ZipExtract($fileLanguageZipPath, SC_DIR.'lang/');
        umask($old);

        echo _l('End of extraction').'<br/><br/>';
    }

    if (!isset($_GET['updatekeepzipfile']))
    { // for debug purpose
        dirRemove($tmp_folder);
    }

    echo _l('Update finished!').' '.'<a href="index.php" target="_top">'._l('Click here to refresh the application').'</a>';
    echo '</body>';
