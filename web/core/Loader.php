<?php
/**
 * Class loader.
 */
namespace core;

class Loader
{

    public function __construct()
    {
        spl_autoload_register([__CLASS__, 'util']);
        spl_autoload_register([__CLASS__, 'model']);

        if (file_exists(VENDOR_PATH . 'autoload.php')) {
            require VENDOR_PATH . 'autoload.php';
        }
    }

    /**
     * Load utility.
     */
    private function util($sClass)
    {
        list($sNameSpace, $sClassName) = explode('\\', $sClass);

        if ($sNameSpace !== 'util') {
            return false;
        }

        $sClassFile = UTIL_PATH . $sClassName . DS . $sClassName . '.php';

        if (file_exists($sClassFile)) {
            require $sClassFile;
        } else {
            return false;
        }
    }

    /**
     * Load model.
     */
    private function model($sClass)
    {
        list($sNameSpace, $sClassName) = explode('\\', $sClass);

        if ($sNameSpace !== 'model') {
            return false;
        }

        $sClassFile = APP_PATH . 'model' . DS . $sClassName . '.php';

        if (file_exists($sClassFile)) {
            require $sClassFile;
        } else {
            return false;
        }
    }
}

# end of this file
