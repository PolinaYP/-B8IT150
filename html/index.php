<?php

require '../vendor/autoload.php';
define('TMDB_BEARER_TOKEN', 'eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiIzZTNiMzhmMTUwYjlmZjQ1NjRjM2U5ODVhMDU1ZDE1YiIsInN1YiI6IjYyZWU2YTU3NDZhZWQ0MDA5MTdlMTlhNyIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.J_MFmVw0R_wAQb2Yf_xttF3TPLrBa7pI_HY6m-_RwKE');
define('TMDB_API_KEY', '3e3b38f150b9ff4564c3e985a055d15b');


$f3 = \Base::instance();
$f3->set('BASE_URL', $f3->SCHEME.'://'.$f3->HOST.':'.$f3->PORT.$f3->BASE.'/');
$f3->config('routes.ini');
$f3->config('config.ini');
$f3->run();