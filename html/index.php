<?php

require '../vendor/autoload.php';

$f3 = \Base::instance();
$f3->set('BASE_URL', $f3->SCHEME.'://'.$f3->HOST.':'.$f3->PORT.$f3->BASE.'/');
$f3->config('routes.ini');
$f3->config('config.ini');
$f3->run();