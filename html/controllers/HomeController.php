<?php

use Tmdb\Client;
use Tmdb\Repository\MovieRepository;
use Tmdb\Factory\ImageFactory;
use Tmdb\Helper\ImageHelper;
use Tmdb\Model\Image;
use Tmdb\Repository\ConfigurationRepository;

final class HomeController {
    function get(\Base $f3, array $args): void {
        $client = ApiClient::makeClient();

        $searchString = $f3->GET['search'] ?? NULL;

        if(isset($searchString) && !empty($searchString)) {
            $searchString = urldecode(urldecode($searchString));
            $searchResult = $client->getSearchApi()->searchMovies($searchString)['results'];
            $data = [];
            foreach ($searchResult as $value) {
                $releaseDate = new DateTime($value['release_date']);
                array_push($data, [
                    'title' => $value['title'],
                    'image' => 'http://image.tmdb.org/t/p/w92' .$value['poster_path'],
                    'voteAverage' => $value['vote_average'],
                    'voteCount' => $value['vote_count'],
                    'releaseDate' => $releaseDate->format('l d F Y'),
                    'id' => $value['id']
                ]);
            }
            $f3->set('searchString', $searchString);
            $f3->set('list', $data);
            $f3->set('pageTitle', 'Results for: '. $searchString);
        } else {
            $repository = new MovieRepository($client);
            $response = [];

            if(isset($f3->GET) && isset($f3->GET['mode'])) {
                if($f3->GET['mode'] === 'BestRated') {
                    $response = $repository->getTopRated();
                    $f3->set('pageTitle', 'Best rated');
                    $f3->set('isTopRated', TRUE);
                } else if($f3->GET['mode'] === 'Upcoming'){
                    $response = $repository->getUpcoming();
                    $f3->set('pageTitle', 'Upcoming');
                    $f3->set('isUpcoming', TRUE);
                }else if($f3->GET['mode'] === 'NowPlaying'){
                    $response = $repository->getNowPlaying();
                    $f3->set('pageTitle', 'Now playing');
                    $f3->set('isNowPlaying', TRUE);
                } else {
                    $response = $repository->getPopular();
                    $f3->set('pageTitle', 'Popular');
                    $f3->set('isPopular', TRUE);
                }
            } else {
                $response = $repository->getPopular();
                $f3->set('pageTitle', 'Popular');
                $f3->set('isPopular', TRUE);
            }
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
            $f3->set('list', $data);
        }

        
        
        $f3->set('content', 'home.htm');
        echo \Template::instance()->render('main.htm');
    }
}