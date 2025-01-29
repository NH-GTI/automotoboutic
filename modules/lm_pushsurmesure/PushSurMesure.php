<?php

class PushSurMesure extends ObjectModel
{
	public $title;
	public $cta_url;
	public $cta_wording;
	public $image;
	public $active;
	public $position;
	public $id_shop;

	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'lm_pushsurmesure_pushs',
		'primary' => 'id_push',
		'multilang' => true,
		'fields' => array(
			'active' =>			array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
			'position' =>		array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),

			// Lang fields
			'title' =>			array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'required' => true, 'size' => 255),
			'cta_wording' =>	array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'required' => true, 'size' => 255),
			'cta_url' =>		array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isUrl', 'required' => true, 'size' => 255),
			'image' =>			array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCleanHtml', 'required' => true, 'size' => 255),
		)
	);

	public	function __construct($id_push = null, $id_lang = null, $id_shop = null, Context $context = null)
	{
		parent::__construct($id_push, $id_lang, $id_shop);
	}

	public function add($autodate = true, $null_values = false)
	{
		$context = Context::getContext();
		$id_shop = $context->shop->id;

		$res = parent::add($autodate, $null_values);
		$res &= Db::getInstance()->execute('
			INSERT INTO `'._DB_PREFIX_.'lm_pushsurmesure` (`id_shop`, `id_push`)
			VALUES('.(int)$id_shop.', '.(int)$this->id.')'
		);
		return $res;
	}

	public function delete()
	{
		$res = true;

		$images = $this->image;
		foreach ($images as $image)
		{
			if (preg_match('/sample/', $image) === 0)
				if ($image && file_exists(dirname(__FILE__).'/images/'.$image))
					$res &= @unlink(dirname(__FILE__).'/images/'.$image);
		}

		$res &= $this->reOrderPositions();

		$res &= Db::getInstance()->execute('
			DELETE FROM `'._DB_PREFIX_.'lm_pushsurmesure`
			WHERE `id_push` = '.(int)$this->id
		);

		$res &= parent::delete();
		return $res;
	}

	public function reOrderPositions()
	{
		$id_push = $this->id;
		$context = Context::getContext();
		$id_shop = $context->shop->id;

		$max = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT MAX(hss.`position`) as position
			FROM `'._DB_PREFIX_.'lm_pushsurmesure_pushs` hss, `'._DB_PREFIX_.'lm_pushsurmesure` hs
			WHERE hss.`id_push` = hs.`id_push` AND hs.`id_shop` = '.(int)$id_shop
		);

		if ((int)$max == (int)$id_push)
			return true;

		$rows = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT hss.`position` as position, hss.`id_push` as id_push
			FROM `'._DB_PREFIX_.'lm_pushsurmesure_pushs` hss
			LEFT JOIN `'._DB_PREFIX_.'lm_pushsurmesure` hs ON (hss.`id_push` = hs.`id_push`)
			WHERE hs.`id_shop` = '.(int)$id_shop.' AND hss.`position` > '.(int)$this->position
		);

		foreach ($rows as $row)
		{
			$current_push = new PushSurMesure($row['id_push']);
			--$current_push->position;
			$current_push->update();
			unset($current_push);
		}

		return true;
	}

	public static function getAssociatedIdsShop($id_push)
	{
		$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
			SELECT hs.`id_shop`
			FROM `'._DB_PREFIX_.'lm_pushsurmesure` hs
			WHERE hs.`id_push` = '.(int)$id_push
		);

		if (!is_array($result))
			return false;

		$return = array();

		foreach ($result as $id_shop)
			$return[] = (int)$id_shop['id_shop'];

		return $return;
	}

}
