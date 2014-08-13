<?php
/**
 * Router.
 */
namespace core;
use controller;

class Router
{
    /**
     * Generate URL.
     * @param string $sAction The request's action
     * @param array $aParams URL params.
     */
    public static function genURL($sAction, $aParams = [])
    {
        array_map(function ($v) {
            return urlencode($v);
        }, $aParams);

        $sURL = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $sAction . '/?' . http_build_query($aParams);

        return $sURL;
    }

    /**
     * Page redirection.
     * @param string $sURL Target URL.
     * @param int $iStatusCode HTTP code.
     */
    public static function redirect($sURL, $iStatusCode = 0)
    {
        if ($iStatusCode) {
            header('Location:' . $sURL, true, $iStatusCode);
        } else {
            header('Location:' . $sURL);
        }
    }

    /**
     * URL parser.
     * @param string $sURI The request uri.
     * @param string $sAppPath The application's path.
     */
    public static function route($sURI, $sAppPath)
    {
        $aTmp = explode('/', $sURI);
        $sAction = ($aTmp[1] && $aTmp[1]{0} !== '?') ? $aTmp[1] : 'Home';
        $sControllerName = 'controller\\' . $sAction;
        $sControllerFile = $sAppPath . str_replace('\\', '/', $sControllerName) . '.php';

        if (file_exists($sControllerFile)) {
            require $sControllerFile;
        } else {
            $sControllerFile = $sAppPath . 'controller/NotFound.php';
            require $sControllerFile;
            $sControllerName = 'controller\\NotFound';
        }

        return new $sControllerName;
    }

}

# end of this file
