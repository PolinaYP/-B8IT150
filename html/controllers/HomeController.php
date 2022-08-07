<?php

use Tmdb\Client;
use Tmdb\Repository\MovieRepository;

final class HomeController {
    function loadIndexPage(\Base $f3, array $args): void {
        $client = ApiClient::makeClient();
        $repository = new MovieRepository($client);
        $popular = $repository->getPopular();
        $data = [];
        foreach ($popular as $value) {
            array_push($data, [
                'title' => $value->getTitle(),
                'image' => 'http://image.tmdb.org/t/p/w92' .$value->getPosterImage()->getFilePath()
            ]);
        }
        $f3->set('popular', $data);
        $f3->set('pageTitle', 'Home');
        $f3->set('content', 'home.htm');
        echo \Template::instance()->render('main.htm');
    }
}