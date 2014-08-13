<?php
/**
 * Controll configure information.
 *
 * Pattern:
 * [
 *     'result' => false,
 *     'msg' => '',
 *     'data' => [],
 * ];
 */
namespace core;

class Config
{
    public static $aConfig = null;

    /**
     * Get a option.
     * @param string $sKey
     * @return array
     */
    public static function get($sKey)
    {
        $aResult = [
            'result' => false,
            'msg' => '',
            'data' => []
        ];

        $aConfigInfo = self::read();

        if (!isset($aConfigInfo[$sKey])) {
            $aResult['msg'] = "Option does not exist : " . $sKey;
            return $aResult;
        }

        $aResult = [
            'result' => true,
            'msg' => '',
            'data' => $aConfigInfo[$sKey]
        ];

        return $aResult;
    }

    /**
     * Get all configure information.
     * @return array
     */
    private static function read()
    {
        if (!is_null(self::$aConfig)) {
            return self::$aConfig;
        }

        $sConfigFile = realpath('./') . DS . 'config.ini';

        if (!is_readable($sConfigFile)) {
            $sConfigFile = realpath('./') . DS . 'config-dev.ini';
        }

        if (!is_readable($sConfigFile)) {
            trigger_error("Configure file does not exist : " . $sConfigFile);
        }

        self::$aConfig = parse_ini_file($sConfigFile, true);

        return self::$aConfig;
    }
}

# end of this file
