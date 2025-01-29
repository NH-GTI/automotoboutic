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
 *  @package    ecordersandstock
 *  @author     Alec Page
 *  @copyright  Copyright (c) 2010-2018 S.A.R.L Ether Création (http://www.ethercreation.com)
 *  @license    Commercial license
 */
namespace ecordersandstock;

/**
 * Description of info
 *
 * this class is designed to manage logs
 *
 * @author alec
 */
class Info
{

    public $dir = '';
	public $family;
	public $file;
	public $limit = 10000;
	public $truncate;
    public $onlyDB = false;

    protected $level_value = array(
        0 => 'DEBUG',
        1 => 'INFO',
        2 => 'WARNING',
        3 => 'ERROR',
    );

    const DEBUG = 0;
    const INFO = 1;
    const WARNING = 2;
    const ERROR = 3;

	public function __construct($family, $truncate = false, $limit = 0)
	{
		$this->family = (string) $family;
		$this->file = $family . '.log';
		$this->truncate = (bool) $truncate;
		if ($this->truncate && is_numeric($limit)) {
			$this->limit = (int) $limit;
		} else {
            $this->truncate = false;
        }
	}

	public function logDebug($text)
	{
		return $this->_writeFile($text, self::DEBUG);
	}

	public function logInfo($text)
	{
        if ($this->onlyDB) {
            return $this->_writeDb($text, self::INFO, 1);
        }

		return $this->_writeFile($text, self::INFO);
	}

	public function logWarning($text)
	{
        if ($this->onlyDB) {
            return $this->_writeDb($text, self::WARNING, 1);
        }

		return $this->_writeFile($text, self::WARNING);
	}

	public function logError($text)
	{
        if ($this->onlyDB) {
            return $this->_writeDb($text, self::ERROR, 1);
        }

		return $this->_writeFile($text, self::ERROR);
	}

    private function _writeDb($text, $severity = self::INFO, $errc = 1)
    {
        return \Db::getInstance()->insert(
            'log',
            array(
                'severity' => (int) $severity,
                'error_code' => (int) $errc,
                'message' => pSQL($text),
                'object_type' => pSQL($this->family),
                'id_employee' => (int) 0,
                'date_add' => date('Y-m-d H:i:s'),
                'date_upd' => date('Y-m-d H:i:s')
            )
        );
    }

    private function _writeFile($text, $severity = self::INFO)
    {
        return (bool) file_put_contents(
			$this->dir . $this->file,
			'*' . $this->level_value[$severity] . '*' . "\t" . date('Y/m/d H:i:s') . ': '
            . (is_string($text) ? $text : var_export($text, true)) . "\n",
			FILE_APPEND
		);
    }

    public function logTruncate()
	{
		$new_f = fopen($this->dir . $this->file . '.new', 'w');
		$old_f = fopen($this->dir . $this->file, 'r');
		$nb = 0;
		while (fgets($old_f) !== false) {
			$nb++;
		}
		rewind($old_f);
		$keep = $this->limit < $nb ? $this->limit : $nb - 1;
		$i = 0;
		while (($line = fgets($old_f)) !== false) {
			if ($i >= ($nb - $keep)) {
				fwrite($new_f, $line);
			}
			$i++;
		}
		fclose($new_f);
		fclose($old_f);
		rename($this->dir . $this->file . '.new', $this->dir . $this->file);
	}

	public function __destruct()
	{
		if ($this->truncate && is_numeric($this->limit)) {
			$this->logTruncate();
		}
	}

    public function logDelete()
    {
        if ($this->onlyDB) {
            return;
        }

        $oldName = $this->dir . $this->file;
        if (file_exists($oldName)) {
            return @unlink($oldName);
        }

        return true;
    }

    public function logArchive()
    {
        if ($this->onlyDB) {
            return;
        }

        $oldName = $this->dir . $this->file;
        $newName = preg_replace('/\.log$/', date('_YmdHis').'.log', $oldName);

        return @rename($oldName, $newName);
    }

}
