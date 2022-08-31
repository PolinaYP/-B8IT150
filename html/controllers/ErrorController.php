<?php

final class ErrorController {
    function onError(Base $f3): void {
        $f3->set('pageTitle', 'Error');
        $f3->set('content', 'error.htm');
        echo \Template::instance()->render('main.htm');
    }
}