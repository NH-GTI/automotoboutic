<?php

class ndkSqlInstall
{
	public static function debugDuplicateNdk($table)
	{
		$sql_create = array();
		$sql_repair = array();
		$sql_create[] = 'CREATE TABLE IF NOT EXISTS '._DB_PREFIX_.$table['name'].'2 ( remove_me_after float NOT NULL )  ENGINE='._MYSQL_ENGINE_;
		
		foreach($table['cols'] as $col)
		{
		 //check if col exists
		 $sqlCheck = 'SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
		 WHERE table_name = "'._DB_PREFIX_.$table['name'].'2" 
		 AND table_schema = "'._DB_NAME_.'" 
		 AND column_name = "'.$col['name'].'" ';
		 
		 $check = Db::getInstance()->executeS($sqlCheck);
		 
		 if(sizeof($check) == 0)
		 	$sql_create[]= "ALTER TABLE `"._DB_PREFIX_.$table['name']."2` ADD  `".$col["name"]."` ".$col["opts"];
		 }
		
		foreach ($sql_create as $query)
			if (Db::getInstance()->execute($query) == false)
				return false;
				
		//on enlève la premiere colonne
		//check if col exists
		$sqlCheckRemove = 'SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
		WHERE table_name = "'._DB_PREFIX_.$table['name'].'2" 
		AND table_schema = "'._DB_NAME_.'" 
		AND column_name = "remove_me_after" ';
		
		$checkRemove = Db::getInstance()->executeS($sqlCheckRemove);
		
		if(sizeof($checkRemove) > 0)
			$sql_repair[]= "ALTER TABLE "._DB_PREFIX_.$table['name']."2 DROP COLUMN remove_me_after";
		
		$chekIndex = Db::getInstance()->executeS('SHOW INDEX FROM '._DB_PREFIX_.$table['name'].'2');
			if(sizeof($chekIndex) > 0)
				Db::getInstance()->execute('ALTER TABLE '._DB_PREFIX_.$table['name'].'2 DROP PRIMARY KEY');
				
				
		$k = 0;
		$where = ' WHERE ';
		foreach($table['index'] as $index)
		{
			$where .= ($k > 0 ? ' AND ' : '').$index.' > 0';
			$k++;
		}
		
		$sql_repair[]= "INSERT INTO "._DB_PREFIX_.$table['name']."2 (SELECT * FROM  "._DB_PREFIX_.$table['name'].$where." GROUP BY ".implode(',', $table['index']).")";
		$sql_repair[]="RENAME TABLE "._DB_PREFIX_.$table['name']." TO "._DB_PREFIX_.$table['name']."_bak";
		$sql_repair[]="RENAME TABLE "._DB_PREFIX_.$table['name']."2 TO "._DB_PREFIX_.$table['name'];
		$sql_repair[] = "DROP TABLE IF EXISTS "._DB_PREFIX_.$table['name']."_bak";
		
		foreach ($sql_repair as $query)
			if (Db::getInstance()->execute($query) == false)
				return false;
	}
}

