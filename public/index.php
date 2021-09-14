<?php
require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/utils/Database.php';
$f3 = \Base::instance();
$f3->set('AUTOLOAD', __DIR__ . '/../src/');
$f3->set('UI', __DIR__ . '/../views/');
$f3->set('TEMP', __DIR__ . '/tmp/');

require __DIR__ . '/../src/config/config.php';
$DB = new Utils\Db($config);
$DB->connect();

$f3->set('db', $DB);

$f3->route('GET /', 'Controllers\Main->getMainPage');
$f3->route('GET /tracking/@id/edit', 'Controllers\Main->getEditPage');
$f3->route('POST /tracking/@id/edit', 'Controllers\Main->update');
$f3->route('POST /tracking/@id/delete', 'Controllers\Main->delete');
$f3->route('POST /tracking/create', 'Controllers\Main->create');
$f3->run();