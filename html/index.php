<?php

require '../vendor/autoload.php';

use DB\SQL;

$f3 = \Base::instance();
$f3->set('BASE_URL', $f3->SCHEME.'://'.$f3->HOST.':'.$f3->PORT.$f3->BASE.'/');
$f3->config('routes.ini');
$f3->config('config.ini');

$options = array(
    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
    \PDO::ATTR_PERSISTENT => TRUE,
    \PDO::MYSQL_ATTR_COMPRESS => TRUE
);

$f3->set('DB', new SQL('mysql:host=localhost;port=3306;dbname=Polina_Proj',$f3->get('DB_USER'),$f3->get('DB_PASSWORD'), $options));

$f3->run();