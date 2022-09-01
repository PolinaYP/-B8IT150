<?php

use Tmdb\Client;
use Tmdb\Repository\MovieRepository;
use \DB\SQL\Session;
use \DB\SQL\Mapper;

final class MovieController {
    function get(Base $f3, array $args): void {
        new \DB\SQL\Session($f3->DB);
        $client = ApiClient::makeClient();
        $repository = new MovieRepository($client);
        $details = $repository->load($args['movieId']);
        $f3->set('BREADCRUMB', [
            ['title' => 'Movies', 'path' => ''],
            ['title' => $details->getTitle(), 'path' => '/movies/'.$args['movieId']],
        ]);

        if($f3->get('SESSION.isLoggedIn')) {
            $mapper = new Mapper($f3->DB, 'favourites');
            $mapper->load(['movie_id = :movieId and user = :userId', ':movieId' => $args['movieId'], ':userId' => $f3->get('SESSION.userId')]);
            $f3->set('movieInFavourites', !$mapper->dry());
        }

        $f3->set('movie', $details);
        $f3->set('pageTitle', 'Movie details');
        $f3->set('content', 'movie.htm');
        echo \Template::instance()->render('main.htm');
    }
}