<?php

final class ActorsController {
    function loadActors(Base $f3, array $args): void {
        $f3->set('pageTitle', 'Actors');
        $f3->set('content', 'actors.htm');
        echo \Template::instance()->render('main.htm');
    }
}