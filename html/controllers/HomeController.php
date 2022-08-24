<?php

use Tmdb\Client;
use Tmdb\Repository\MovieRepository;

final class HomeController {
    function get(\Base $f3, array $args): void {
        $client = ApiClient::makeClient();

        

        $repository = new MovieRepository($client);
        $response = [];
        if(isset($f3->GET) && isset($f3->GET['mode'])) {
            if($f3->GET['mode'] === 'BestRated') {
                $response = $repository->getTopRated();
                $f3->set('pageTitle', 'Best rated titles');
                $f3->set('isTopRated', TRUE);
            } else if($f3->GET['mode'] === 'Popular'){
                $response = $repository->getPopular();
                $f3->set('pageTitle', 'Popular titles');
                $f3->set('isPopular', TRUE);
            } else {
                $response = $repository->getPopular();
                $f3->set('pageTitle', 'Popular titles');
                $f3->set('isPopular', TRUE);
            }
        } else {
            $response = $repository->getPopular();
            $f3->set('pageTitle', 'Popular titles');
            $f3->set('isPopular', TRUE);
        }
        $repository->getPopular();
        $data = [];
        foreach ($response as $value) {
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
        
        $f3->set('content', 'home.htm');
        echo \Template::instance()->render('main.htm');
    }
}