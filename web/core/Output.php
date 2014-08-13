<?php
/**
 * Handle output.
 */
namespace core;

class Output
{
    const TYPE_HTML = 'html';
    const TYPE_JSON = 'json';
    const TYPE_PJAX = 'pjax';

    /**
     * The entrance method.
     * @param array $data Data to output.
     * @param string $type Output pettern.
     */
    public static function handle($aData, $sType = 'html')
    {
        $sHandlerName = $sType . 'Handler';

        if (method_exists(__CLASS__, $sHandlerName)) {
            self::$sHandlerName($aData);
        } else {
            trigger_error('Invalid pattern : ' . $sType);
        }
    }

    /**
     * Page handler.
     * @param array $aData
     */
    private static function htmlHandler($aData)
    {
        extract($aData['data']);

        foreach ($aData[self::TYPE_HTML] as $sHtml) {
            $sHtmlPath = APP_PATH . 'view' . DS . $sHtml . '.html';

            if (file_exists($sHtmlPath)) {
                require $sHtmlPath;
            }

        }
    }

    /**
     * Pjax handler.
     * @param array $aData
     */
    private static function pjaxHandler($aData)
    {
        extract($aData['data']);
        $sHtmlPath = APP_PATH . 'view' . DS . $aData[self::TYPE_PJAX] . '.html';

        if (file_exists($sHtmlPath)) {
            require $sHtmlPath;
        }
    }

    /**
     * Output JSON data.
     * @param array $aData Data to output.
     */
    private static function jsonHandler($aData)
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($aData['data']);
    }
}

# end of this file
