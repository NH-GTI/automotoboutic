<?php

class UISettings
{
    public static function getFilename()
    {
        $sc_agent = SC_Agent::getInstance();
        if(isset($sc_agent)){
            return SC_TOOLS_DIR.'UISettings/'.$sc_agent->id_employee.'.ini';
        }
    }

    // ----------------------------------------------------------------------------
    //
    //  Function:   loadJS
    //  Purpose:        Load settings from ini file and build JS cache inside SC
    //
    // ----------------------------------------------------------------------------
    public static function loadJS($page = 'cat_tree')
    {
        global $sc_agent;
        if (isset($_GET['resetuisettings']) && $_GET['resetuisettings'] == 1)
        {
            self::resetSettings();
        }
        echo "ui_settings=new Object();\n";
        if (!is_dir(SC_TOOLS_DIR.'UISettings'))
        {
            $writePermissions = octdec('0'.substr(decoct(fileperms(realpath(SC_PS_PATH_DIR.'img/p'))), -3));
            $old = umask(0);
            mkdir(SC_TOOLS_DIR.'UISettings', $writePermissions);
            umask($old);
        }

        $prefix = 'cat_';
        if (!empty($page))
        {
            $exp = explode('_', $page);
            $prefix = $exp[0].'_';
        }

        // skip loading of biggggg array in page
        $employee_settings = array();

        $filename = self::getFilename();
        if (file_exists($filename))
        {
            $employee_settings = parse_ini_file($filename, true);
        }
        $employee_settings = UISettingsConvert::convert($employee_settings);
        foreach ($employee_settings as $key => $value)
        {
            if (strpos($key, 'start_'.$prefix) !== false)
            {
                echo 'ui_settings["'.$key.'"]="'.(string) htmlspecialchars($value).'";'."\n";
            }
        }
    }

    // ----------------------------------------------------------------------------
    //
    //  Purpose:        Get a setting from ini file
    //
    // ----------------------------------------------------------------------------
    public static function getSetting($name)
    {
        $employee_settings = array();
        $filename = self::getFilename();
        if (file_exists($filename))
        {
            $employee_settings = parse_ini_file($filename, true);
        }
        $employee_settings = UISettingsConvert::convert($employee_settings);
        foreach ($employee_settings as $key => $value)
        {
            if ($key == $name)
            {
                return $value;
            }
        }

        return null;
    }

    public static function load_ini_file()
    {
        $return = array();
        $filename = self::getFilename();
        if (file_exists($filename))
        {
            $return = parse_ini_file($filename, true);
            $return = UISettingsConvert::convert($return);
        }

        return $return;
    }

    public static function write_ini_file($assoc_arr, $has_sections = false)
    {
        $path = self::getFilename();
        $content = '';
        $assoc_arr['version'] = SC_UISETTINGS_VERSION;
        if ($has_sections)
        {
            foreach ($assoc_arr as $key => $elem)
            {
                $content .= '['.$key."]\n";
                foreach ($elem as $key2 => $elem2)
                {
                    if (is_array($elem2))
                    {
                        for ($i = 0; $i < count($elem2); ++$i)
                        {
                            $content .= $key2.'[] = "'.$elem2[$i]."\"\n";
                        }
                    }
                    elseif ($elem2 == '')
                    {
                        $content .= $key2." = \n";
                    }
                    else
                    {
                        $content .= $key2.' = "'.$elem2."\"\n";
                    }
                }
            }
        }
        else
        {
            foreach ($assoc_arr as $key => $elem)
            {
                if (is_array($elem))
                {
                    for ($i = 0; $i < count($elem); ++$i)
                    {
                        $content .= $key.'[] = "'.$elem[$i]."\"\n";
                    }
                }
                elseif ($elem == '')
                {
                    $content .= $key." = \n";
                }
                else
                {
                    $content .= $key.' = "'.$elem."\"\n";
                }
            }
        }
        if(!$path){
            return false;
        }
        if (!$handle = @fopen($path, 'w'))
        {
            return false;
        }
        if (!@fwrite($handle, $content))
        {
            return false;
        }
        @fclose($handle);

        return true;
    }

    public static function resetSettings()
    {
        dirEmpty(SC_TOOLS_DIR.'UISettings');
    }

    /**
     * Return saved size of each column
     * @param $uisettings
     * @return array
     */
    public static function formatSize($uisettings)
    {
        $size = [];
        $tmp = explode('|', $uisettings);
        if(isset($tmp[2]))
        {
            $tmp = explode('-', $tmp[2]);
            foreach ($tmp as $v)
            {
                $s = explode(':', $v);
                if(isset($s[1]))
                {
                    $size[$s[0]] = $s[1];
                }
            }
        }
        return $size;
    }

    /**
     * Return saved hidden column
     * @param $uisettings
     * @return array
     */
    public static function formatHidden($uisettings)
    {
        $hidden = [];
        $tmp = explode('|', $uisettings);

        if(empty($tmp[0]))
        {
            return $hidden;
        }

        $tmp = explode('-', $tmp[0]);
        foreach ($tmp as $v)
        {
            $s = explode(':', $v);
            if(isset($s[1]))
            {
                $hidden[$s[0]] = $s[1];
            }
        }

        return $hidden;
    }
}
