<?php

use Tmdb\Client;
use Tmdb\Repository\MovieRepository;

final class HomeController {
    function get(\Base $f3, array $args): void {
        $client = ApiClient::makeClient();
        $repository = new MovieRepository($client);
        $popular = $repository->getPopular();
        $data = [];
        foreach ($popular as $value) {
            array_push($data, [
                'title' => $value->getTitle(),
                'image' => 'http://image.tmdb.org/t/p/w92' .$value->getPosterImage()->getFilePath(),
                'voteAverage' => $value->getVoteAverage(),
                'voteCount' => $value->getVoteCount(),
                'releaseDate' => $value->getReleaseDate()->format('l d F Y'),
                'id' => $value->getId()
            ]);
        }
        $f3->set('popular', $data);
        $f3->set('pageTitle', 'Home');
        $f3->set('content', 'home.htm');
        echo \Template::instance()->render('main.htm');
    }
}