<?php

final class ActorsController {
    function get(Base $f3, array $args): void {
        $f3->set('pageTitle', 'Actors');
        $f3->set('content', 'actors.htm');
        echo \Template::instance()->render('main.htm');
    }
}