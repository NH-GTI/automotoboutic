<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to a commercial license from SARL Ether Création
 * Use, copy, modification or distribution of this source file without written
 * license agreement from the SARL Ether Création is strictly forbidden.
 * In order to obtain a license, please contact us: contact@ethercreation.com
 * ...........................................................................
 * INFORMATION SUR LA LICENCE D'UTILISATION
 *
 * L'utilisation de ce fichier source est soumise a une licence commerciale
 * concedee par la societe Ether Création
 * Toute utilisation, reproduction, modification ou distribution du present
 * fichier source sans contrat de licence ecrit de la part de la SARL Ether Création est
 * expressement interdite.
 * Pour obtenir une licence, veuillez contacter la SARL Ether Création a l'adresse: contact@ethercreation.com
 * ...........................................................................
 *  @package    ec_import
 *  @author     Alec Page
 *  @copyright  Copyright (c) 2010-2018 S.A.R.L Ether Création (http://www.ethercreation.com)
 *  @license    Commercial license
 */

class bigjson
{
    CONST EXACT = 0;
    CONST IN = 1;
    CONST REGEX = 2;
    CONST CI = 4;
    CONST NOT = 8;

    const CHUNK_SIZE = 64;
    const BEGIN_ARRAY = '[';
    const END_ARRAY = ']';
    const BEGIN_OBJ = '{';
    const END_OBJ = '}';
    const OBJ_SEP = ',';
    const ASSOC_SEP = ':';
    const STRING_MARK = '"';

    private $dir;
    private $file;
    private $data;
    private $offsets;

    public $maxLine;
    public $nLines;
    public $currentLine;
    public $autoIndex = true;
    private $tempIndex;
    private $nextLine;
    private $pos;
    private $len;
    public $indexes = array();

    public function __construct($fileName, $dir, $tempIndex = true)
    {
        $dir = rtrim($dir, '/').'/';

        if (!file_exists($dir.$fileName.'.txt')) {
            throw new Exception('Data file is missing.');
        }
        if (!file_exists($dir.$fileName.'.json')) {
            throw new Exception('Offsets file is missing.');
        }

        if (($this->data = @fopen($dir.$fileName.'.txt', 'r')) === false) {
            throw new Exception('Data file cannot be opened for reading.');
        }
        if (($offsetsJson = file_get_contents($dir.$fileName.'.json')) === false) {
            throw new Exception('Offsets file cannot be read.');
        }
        if (($this->offsets = json_decode($offsetsJson)) === null) {
            throw new Exception('Offsets file is not a good json.');
        }
        if (!is_array($this->offsets)) {
            throw new Exception('Offsets file is not an array.');
        }
        if (count($this->offsets) < 2) {
            throw new Exception('Offsets file is empty or shorter than 2 values.');
        }
        if ($this->offsets[0] !== 0) {
            throw new Exception('Offsets file is not a proper file of offsets.');
        }

        $this->dir = $dir;
        $this->file = $fileName;
        $this->tempIndex = (bool) $tempIndex;
        if (!$tempIndex) {
            $this->loadIndex();
        }
        $this->pos = 0;
        $this->currentLine = 0;
        $this->nextLine = 0;
        $this->maxLine = count($this->offsets) - 2;
        $this->nLines = $this->maxLine + 1;
        $this->len = $this->offsets[1];

        return $this;
    }

    public function read($nLine = null)
    {
        if (!is_null($nLine) && !is_int($nLine)) {
            return false;
        }

        if (is_null($nLine) && ($this->nextLine <= $this->maxLine)) {
            // if $nLine is null, read the next line and go forward
            fseek($this->data, $this->pos);
            $json = fread($this->data, $this->len);
            $this->currentLine = $this->nextLine;
            $this->nextLine++;
            if ($this->nextLine <= $this->maxLine) {
                $this->pos += $this->len;
                $this->len = $this->offsets[$this->nextLine + 1];
            }

            return json_decode($json, true);
        } elseif (is_null($nLine)) {
            // return false if totalLines is reached
            return false;
        } elseif ($nLine <= $this->maxLine) {
            $this->currentLine = $this->nextLine = $nLine;
            if ($this->go($this->currentLine)) {
                return $this->read();
            }
        }

        $this->currentLine = $this->nextLine = $nLine;

        return false;
    }

    public function go($nLine)
    {
        if ($nLine > $this->maxLine) {
            return false;
        }

        $this->pos = array_sum(array_slice($this->offsets, 0, $nLine + 1));
        $this->len = $this->offsets[$nLine + 1];
        $this->nextLine = $this->currentLine = $nLine;

        return true;
    }

    public function get($nLine)
    {
        if ($nLine > $this->maxLine) {
            return false;
        }

        $pos = array_sum(array_slice($this->offsets, 0, $nLine + 1));
        $len = $this->offsets[$nLine + 1];
        fseek($this->data, $pos);
        $json = fread($this->data, $len);

        return json_decode($json, true);
    }

    public function searchOne($key, $value, $compare = self::EXACT)
    {
        if (isset($this->indexes[$key])) {
            foreach ($this->indexes[$key] as $indexedValue => $listIndexes) {
                if (self::_isFound($indexedValue, $value, $compare)) {
                    return $listIndexes[0];
                }
            }
        } elseif ($this->autoIndex) {
            $this->buildIndex($key);
            return $this->searchOne($key, $value, $compare);
        } else {
            $pos = 0;
            for ($i = 0; $i <= $this->maxLine; $i++) {
                $pos += $this->offsets[$i];
                $len = $this->offsets[$i + 1];
                fseek($this->data, $pos);
                $json = fread($this->data, $len);
                $tab = json_decode($json, true);
                if (!isset($tab[$key])) {
                    continue;
                }
                if (self::_isFound($tab[$key], $value, $compare)) {
                    return $i;
                }
            }
        }

        return false;
    }

    public function searchAll($key, $value, $compare = self::EXACT)
    {
        $found = array();
        if (isset($this->indexes[$key])) {
            foreach ($this->indexes[$key] as $indexedValue => $listIndexes) {
                if (self::_isFound($indexedValue, $value, $compare)) {
                    $found = array_merge($found, $listIndexes);
                }
            }
            //$found = array_unique($found);
            $found = array_keys(array_flip($found));//faster than array_unique()
            sort($found);
        } elseif ($this->autoIndex) {
            $this->buildIndex($key);
            $found = $this->searchAll($key, $value, $compare);
        } else {
            $pos = 0;
            for ($i = 0; $i <= $this->maxLine; $i++) {
                $pos += $this->offsets[$i];
                $len = $this->offsets[$i + 1];
                fseek($this->data, $pos);
                $json = fread($this->data, $len);
                $tab = json_decode($json, true);
                if (!isset($tab[$key])) {
                    continue;
                }
                if (self::_isFound($tab[$key], $value, $compare)) {
                    $found[] = $i;
                }
            }
        }

        return $found;
    }

    private static function _isFound($tabValue, $searchValue, $compare = self::EXACT)
    {
        if (!@($tabValue == (string) $tabValue) || !@($searchValue == (string) $searchValue)) {
            return false;
        }

        switch ($compare) {
            case self::IN:
                if (strpos($tabValue, $searchValue) !== false) {
                    return true;
                }
                break;
            case self::NOT + self::IN:
                if (strpos($tabValue, $searchValue) === false) {
                    return true;
                }
                break;
            case self::REGEX:
                if (preg_match($searchValue, $tabValue)) {
                    return true;
                }
                break;
            case self::NOT + self::REGEX:
                if (!preg_match($searchValue, $tabValue)) {
                    return true;
                }
                break;
            case self::EXACT + self::CI:
                if (!strcasecmp($tabValue, $searchValue)) {
                    return true;
                }
                break;
            case self::NOT + self::EXACT + self::CI:
                if (0 !== strcasecmp($tabValue, $searchValue)) {
                    return true;
                }
                break;
            case self::IN + self::CI:
                if (stripos($tabValue, $searchValue) !== false) {
                    return true;
                }
                break;
            case self::NOT + self::IN + self::CI:
                if (stripos($tabValue, $searchValue) === false) {
                    return true;
                }
                break;
            case self::NOT:
                if (0 !== strcmp($tabValue, $searchValue)) {
                    return true;
                }
                break;
            default:
                if (!strcmp($tabValue, $searchValue)) {
                    return true;
                }
                break;
        }

        return false;
    }

    public function buildIndex($key)
    {
        $pos = 0;
        $found = array();
        for ($i = 0; $i <= $this->maxLine; $i++) {
            $pos += $this->offsets[$i];
            $len = $this->offsets[$i + 1];
            fseek($this->data, $pos);
            $json = fread($this->data, $len);
            $tab = json_decode($json, true);
            if (!isset($tab[$key]) || !@($tab[$key] == (string) $tab[$key])) {
                continue;
            }
            $found[$tab[$key]][] = $i;
        }

        $this->indexes[$key] = $found;
    }

    public function rebuildIndex()
    {
        foreach ($this->indexes as $key => $values) {
            $this->buildIndex($key);
        }
        $this->_writeIndexes();
    }

    public function loadIndex()
    {
        if (file_exists($this->dir.$this->file.'_indexes.json')) {
            $this->indexes = json_decode(file_get_contents($this->dir.$this->file.'_indexes.json'), true);
        }
    }

    public function deleteIndex($key = false)
    {
        if (!$key) {
            $this->indexes = array();
        } else {
            unset($this->indexes[$key]);
        }
        $this->_writeIndexes();
    }

    private function _writeIndexes()
    {
        file_put_contents($this->dir.$this->file.'_indexes.json', json_encode($this->indexes));
    }

    /**
     * get values instead of indexes
     * $rfields : array of field names that will be returned
     * $wheres : array of arrays of three parameter (searchAll parameters)
     * return array of associative arrays matching all wheres (joined by AND)
     */
    public function select($rfields, $wheres)
    {
        if (!is_array($wheres)) {
            return false;
        }
        if (!is_array($rfields)) {
            $rfields = array($rfields);
        }

        $fields = array_flip($rfields);

        $indexes = array();
        foreach ($wheres as $where) {
            if (!is_array($where) || 2 > count($where) || 3 < count($where)) {
                continue;
            }
            $where[2] = isset($where[2]) ? $where[2] : self::EXACT;

            $indexes = $indexes
                ? array_intersect($indexes, $this->searchAll($where[0], $where[1], (int) $where[2]))
                : $this->searchAll($where[0], $where[1], (int) $where[2]);
        }

        if (!$indexes) {
            return array();
        }

        sort($indexes);

        $return = array();
        foreach ($indexes as $i) {
            if (($inter = array_intersect_key($this->get($i), $fields))) {
                $return[$i] = $inter;
            }
        }

        return $return;
    }

    public function __destruct()
    {
        fclose($this->data);
        if (!$this->tempIndex) {
            $this->_writeIndexes();
        }
    }

    /**
     *  specific csv (products, customers,...) to bigjson transform
     */
    public static function csv2bjs($path, $encoding = 'UTF-8', $continue = false)
    {

    }

    /**
     *  specific json object (products, customers,...) to bigjson transform
     */
    public static function jso2bjs($path, $continue = false)
    {
        $path_parts = pathinfo($path);
        $fileName = $path_parts['dirname'] . '/' . $path_parts['filename'];
        $fileExt = '.txt';
        $offsetsExt = '.json';
        if (!$continue && file_exists($fileName . $fileExt)) {
            @unlink($fileName . $fileExt);
        }
        if ($continue && file_exists($fileName . $offsetsExt)) {
            $offsets = json_decode(file_get_contents($fileName . $offsetsExt));
        } else {
            $offsets = array(0);
        }
        $handle = fopen($path, 'rb');

        $buffer = '';
        $pos = 0;
        $ob_switch = 0;
        while (false !== ($chunk = fread($handle, 8192))) {
            $pointer = 0;
            while (isset($chunk[$pointer])) {
                $char = ord($chunk[$pointer]);
                if($char < 128){
                    $str = $chunk[$pointer];
                    $bytes = 1;
                }else{
                    if($char < 224){
                        $bytes = 2;
                    }elseif($char < 240){
                        $bytes = 3;
                    }elseif($char < 248){
                        $bytes = 4;
                    }elseif($char == 252){
                        $bytes = 5;
                    }else{
                        $bytes = 6;
                    }
                    $str = substr($chunk, $pointer, $bytes);
                    if (($pointer + $bytes > 8192) || false === ($str = substr($chunk, $pointer, $bytes))) {
                        break;
                    }
                }
                $pointer += $bytes;
                if (0 === $pos && 0 === $pointer && self::BEGIN_ARRAY === $str) {
                    continue;
                }
                if (self::BEGIN_OBJ === $str) {
                    $ob_switch++;
                }
                if ($ob_switch >= 1) {
                    $buffer .= $str;
                }
                if (self::END_OBJ === $str) {
                    $ob_switch--;
                    if (0 === $ob_switch) {
                        file_put_contents($fileName . $fileExt, $buffer, FILE_APPEND);
                        $offsets[] = strlen($buffer);
                        $buffer = '';
                    }
                }
            }
            if (feof($handle)) {
                break;
            }
            $pos += $pointer;
            fseek($handle, $pos);
        }
        fclose($handle);
        file_put_contents($fileName . $offsetsExt, json_encode($offsets));

        return true;
    }

    /**
     *  specific xml object (products, customers,...) to bigjson transform
     */
    public static function xmlo2bjs($path, $tagName, $continue = false)
    {
        $startPatrn0 = '/^\<(\w+)?:?(?:' . implode('|$)(?:', str_split($tagName)) . '|$)(?:(\ |\>)|$)/';
        $startPatrn1 = '/^\<(\w+:)?' . $tagName . '\ |\>/';
        $endPatrn = '/\<\/(\w+:)?' . $tagName . '\>$/';

        $path_parts = pathinfo($path);
        $fileName = $path_parts['dirname'] . '/' . $path_parts['filename'];
        $fileExt = '.txt';
        $offsetsExt = '.json';
        if (!$continue && file_exists($fileName . $fileExt)) {
            @unlink($fileName . $fileExt);
        }
        if ($continue && file_exists($fileName . $offsetsExt)) {
            $offsets = json_decode(file_get_contents($fileName . $offsetsExt));
        } else {
            $offsets = array(0);
        }
        $handle = fopen($path, 'rb');

        $pos = 0;
        $buffer = $item = '';
        $sw = false;
        while ($chunk = fread($handle, 8192)) {
            $pointer = 0;
            while (isset($chunk[$pointer])) {
                $char = ord($chunk[$pointer]);
                if ($char < 128) {
                    $str = $chunk[$pointer];
                    $bytes = 1;
                } else {
                    if ($char < 224) {
                        $bytes = 2;
                    } elseif ($char < 240) {
                        $bytes = 3;
                    } elseif ($char < 248) {
                        $bytes = 4;
                    } elseif ($char == 252) {
                        $bytes = 5;
                    } else {
                        $bytes = 6;
                    }
                    if (($pointer + $bytes > 8192) || false === ($str = substr($chunk, $pointer, $bytes))) {
                        break;
                    }
                }
                $pointer += $bytes;
                $item .= $str;
                if (!$sw) {
                    if (!preg_match($startPatrn0, $item)) {
                        $item = '';
                    } elseif (preg_match($startPatrn1, $item)) {
                        $sw = true;
                    }
                }
                if ($sw && preg_match($endPatrn, $item)) {
                    $item = preg_replace(array('#(</?)\w+:([^>]+>)#', '# \w+:(\w*=".*?")#'), array('$1$2', ' $1'), $item);
                    $jitem = json_encode(simplexml_load_string($item, "SimpleXMLElement", LIBXML_COMPACT | LIBXML_NOCDATA));
                    $buffer .= $jitem;
                    $offsets[] = strlen($jitem);
                    if (strlen($buffer) > 81920) {
                        file_put_contents($fileName . $fileExt, $buffer, FILE_APPEND);
                        $buffer = '';
                    }
                    $item = '';
                    $sw = false;
                }
            }
            $pos += $pointer;
            fseek($handle, $pos);
        }

        if ($buffer) {
            file_put_contents($fileName . $fileExt, $buffer, FILE_APPEND);
        }
        file_put_contents($fileName . $offsetsExt, json_encode($offsets));

        return true;
    }

    public static function mergebjs($first, $second)
    {
        $fileExt = '.txt';
        $offsetsExt = '.json';

        $path_parts = pathinfo($first);
        $firstfileName = $path_parts['dirname'] . '/' . $path_parts['filename'];

        $path_parts = pathinfo($second);
        $secondfileName = $path_parts['dirname'] . '/' . $path_parts['filename'];

        file_put_contents($firstfileName . $fileExt, file_get_contents($secondfileName . $fileExt), FILE_APPEND);

        $firstOffsets = json_decode(file_get_contents($firstfileName . $offsetsExt));
        $secondOffsets = json_decode(file_get_contents($secondfileName . $offsetsExt));
        array_shift($secondOffsets);
        file_put_contents($firstfileName . $offsetsExt, json_encode(array_merge($firstOffsets, $secondOffsets)));

        return true;
    }
}
