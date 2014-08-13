<?php
/**
 * DB controller.
 */

namespace util;

use PDO;

class DB
{

    public static $oDB = null;

    /**
     * Connect to database.
     * @param string $sDSN
     * @param string $sUserName
     * @param string $sPassword
     * @param array $aOptions
     * @return PDO
     */
    public static function getInstance($sDSN = '', $sUserName = '', $sPassword = '', $aOptions = [])
    {
        if (is_null(self::$oDB)) {
            self::$oDB = new PDO($sDSN, $sUserName, $sPassword, $aOptions);
            $r = self::$oDB->query('set names utf8');

			if (!$r) {
				return false;
			}
			
            self::$oDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return self::$oDB;
    }

    /**
     * Get list.
     * @param string $sSQL
     * @param array $aParams
     * @return array
     */
    public static function getList($sSQL, $aParams = [])
    {
        $o = self::$oDB->prepare($sSQL);
        $o->execute($aParams);
        return $o->fetchall(PDO::FETCH_ASSOC);
    }

    /**
     * Insert a record.
     * @param array $aData
     * @param string $sTable
     * @return int
     */
    public static function add($aData, $sTable)
    {
        $aParsedData = self::parseArray($aData);
        $sSQL = "insert into $sTable (" . $aParsedData['keys'] . ") values (" . $aParsedData['marks'] . ")";
        $o = self::$oDB->prepare($sSQL);
        $o->execute($aParsedData['values']);
        return self::$oDB->lastInsertId();
    }

    /**
     * Get one record.
     * @param string $sSQL
     * @param array $aParams
     * @return array
     */
    public static function getOne($sSQL, $aParams = [])
    {
        $o = self::$oDB->prepare($sSQL);
        $o->execute($aParams);
        return $o->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Update data.
     * @param string $sTable
     * @param string $sWhere
     * @param array $aData
     * @return bool
     */
    public static function update($sTable, $sWhere, $aData)
    {
        $aTmp = [];

        foreach ($aData as $key => $value) {
            $aTmp[] = "$key='$value'";
        }

        $sKeys = implode(',', $aTmp);
        $sSQL = "update $sTable set $sKeys $sWhere";

        return self::$oDB->exec($sSQL);
    }

    /**
     * Import sql file.
     * @param string $sFile
     * @return bool
     */
    public static function import($sFile)
    {
        return self::$oDB->exec(file_get_contents($sFile));
    }

    /**
     * Excute a sql.
     * @param string sSQL
     * @return bool
     */
    public static function query($sSQL)
    {
        return self::$oDB->query($sSQL);
    }

    /**
     * Parse data inserted.
     * @param array $aData
     * @return type
     */
    private static function parseArray($aData)
    {
        $sKeysOrigin = array_map(function ($v) {
                return "`$v`";
        }, array_keys($aData));

        $sKeys = implode(',', $sKeysOrigin);
        $sMarks = implode(',', array_fill(0, count($sKeysOrigin), '?'));
        $aValues = array_values($aData);

        return [
            'keys' => $sKeys,
            'marks' => $sMarks,
            'values' => $aValues
        ];
    }
}

# end of this file
