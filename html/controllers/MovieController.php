<?php

use Tmdb\Client;
use Tmdb\Repository\MovieRepository;

final class MovieController {
    function get(Base $f3, array $args): void {
        $client = ApiClient::makeClient();
        $repository = new MovieRepository($client);
        $details = $repository->load($args['movieId']);
        $f3->set('BREADCRUMB', [
            ['title' => 'Movies', 'path' => ''],
            ['title' => $details->getTitle(), 'path' => '/movies/'.$args['movieId']],
        ]);
        $f3->set('movie', $details);
        $f3->set('pageTitle', 'Movie details');
        $f3->set('content', 'movie.htm');
        echo \Template::instance()->render('main.htm');
    }
}