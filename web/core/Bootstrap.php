<?php
/**
 * Initialize the framework.
 */
namespace core;

date_default_timezone_set('Asia/Shanghai');
error_reporting(-1);

$aCoreFiles = [
    'Const',
    'Config',
    'Error',
    'Function',
    'Interface',
    'Loader',
    'Output',
    'Router',
    'Controller',
];

foreach ($aCoreFiles as $sCoreFile) {
    require __DIR__ . DIRECTORY_SEPARATOR . $sCoreFile . '.php';
}

require APP_PATH . 'controller/Base.php';
new Loader;
set_error_handler('err');
mb_internal_encoding("UTF-8");

$oController = Router::route($_SERVER['REQUEST_URI'], APP_PATH);
$oController->before();
Output::handle($oController->handle(), $oController->outputType);
$oController->after();

# end of this file
