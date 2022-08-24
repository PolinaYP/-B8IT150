<?php

final class MoviesController {
    function get(Base $f3, array $args): void {
        $f3->set('pageTitle', 'Movies');
        $f3->set('content', 'movies.htm');
        echo \Template::instance()->render('main.htm');
    }
}