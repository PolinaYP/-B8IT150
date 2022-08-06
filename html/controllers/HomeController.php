<?php

final class HomeController {
    function loadINdexPage(\Base $f3, array $args): void {
        $f3->set('pageTitle', 'Home');
        $f3->set('content', 'home.htm');
        echo \Template::instance()->render('main.htm');
    }
}