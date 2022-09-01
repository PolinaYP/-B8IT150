<?php

use Tmdb\Client;
use Tmdb\Repository\PeopleRepository;
use Tmdb\Repository\SearchRepository;
use Tmdb\Model\Search\SearchQuery\PersonSearchQuery;
use Tmdb\Factory\ImageFactory;
use Tmdb\Helper\ImageHelper;
use Tmdb\Model\Image;
use Tmdb\Repository\ConfigurationRepository;
use \DB\SQL\Session;

final class ActorsController {
    function get(Base $f3, array $args): void {
        new \DB\SQL\Session($f3->DB);
        $client = ApiClient::makeClient();
        $searchString = $f3->GET['search'] ?? NULL;
        $people = [];
        if(isset($searchString)) {
            $query = new PersonSearchQuery();
            $query->page(1);
            $repository = new SearchRepository($client);
            $people = $repository->searchPerson($searchString, $query);['results'];
            $f3->set('searchString', $searchString);
        } else {
            $repository = new PeopleRepository($client);
            $people = $repository->getPopular();
        }
        
        $data = [];
        foreach($people as $person) {
            array_push($data, [
                'name' => $person->getName(),
                'image' => $person->getProfilePath() !== null ? 'http://image.tmdb.org/t/p/w300' . $person->getProfilePath() : '../images/No_image_available.jpeg',
                'id' => $person->getId()
            ]);
        }
        $f3->set('people', $data);
        $f3->set('pageTitle', 'Actors');
        $f3->set('content', 'actors.htm');
        echo \Template::instance()->render('main.htm');
    }

    public function getActorDetails(Base $f3, array $args): void {
        new \DB\SQL\Session($f3->DB);
        $client = ApiClient::makeClient();
        $repository = new PeopleRepository($client);
        $actor = $repository->load($args['actorId']);
        $f3->set('BREADCRUMB', [
            ['title' => 'Actors', 'path' => '/actors'],
            ['title' => $actor->getName(), 'path' => '/actors/'.$args['actorId']],
        ]);
        $f3->set('actor', $actor);
        $f3->set('pageTitle', $actor->getName());
        $f3->set('content', 'actor.htm');
        echo \Template::instance()->render('main.htm');
    }
}