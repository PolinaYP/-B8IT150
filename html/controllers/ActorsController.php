<?php

use Tmdb\Client;
use Tmdb\Repository\PeopleRepository;
use Tmdb\Factory\ImageFactory;
use Tmdb\Helper\ImageHelper;
use Tmdb\Model\Image;
use Tmdb\Repository\ConfigurationRepository;

final class ActorsController {
    function get(Base $f3, array $args): void {

        $client = ApiClient::makeClient();
        $repository = new PeopleRepository($client);
        $people = $repository->getPopular();
        $data = [];
        foreach($people as $person) {
            // var_dump($person);
            array_push($data, [
                'name' => $person->getName(),
                // 'dateOfBirth'=>$person->getBirthday()->format('l d F Y')
            ]);
        }
        $f3->set('list', $data);
        $f3->set('pageTitle', 'Actors');
        $f3->set('content', 'actors.htm');
        echo \Template::instance()->render('main.htm');
    }
}