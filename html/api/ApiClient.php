<?php

use Tmdb\Client;
use Tmdb\Event\BeforeRequestEvent;
use Tmdb\Event\Listener\Request\AcceptJsonRequestListener;
use Tmdb\Event\Listener\Request\ApiTokenRequestListener;
use Tmdb\Event\Listener\Request\ContentTypeJsonRequestListener;
use Tmdb\Event\Listener\Request\UserAgentRequestListener;
use Tmdb\Event\Listener\RequestListener;
use Tmdb\Event\RequestEvent;
use Tmdb\Token\Api\BearerToken;
use Tmdb\Token\Api\ApiToken;

final class ApiClient {

    static function makeClient(): Client {
        $ed = new Symfony\Component\EventDispatcher\EventDispatcher();
        $client = new Client(
            [
                /** @var BearerToken */
                'api_token' => new BearerToken('eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiIzZTNiMzhmMTUwYjlmZjQ1NjRjM2U5ODVhMDU1ZDE1YiIsInN1YiI6IjYyZWU2YTU3NDZhZWQ0MDA5MTdlMTlhNyIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.J_MFmVw0R_wAQb2Yf_xttF3TPLrBa7pI_HY6m-_RwKE'),
                'event_dispatcher' => [
                    'adapter' => $ed
                ],
                // We make use of PSR-17 and PSR-18 auto discovery to automatically guess these, but preferably set these explicitly.
                'http' => [
                    'client' => null,
                    'request_factory' => null,
                    'response_factory' => null,
                    'stream_factory' => null,
                    'uri_factory' => null
                ],
                'secure' => false
            ]
        );
        /**
         * Required event listeners and events to be registered with the PSR-14 Event Dispatcher.
         */
        $requestListener = new RequestListener($client->getHttpClient(), $ed);
        $ed->addListener(RequestEvent::class, $requestListener);

        $apiTokenListener = new ApiTokenRequestListener($client->getToken());
        $ed->addListener(BeforeRequestEvent::class, $apiTokenListener);

        $acceptJsonListener = new AcceptJsonRequestListener();
        $ed->addListener(BeforeRequestEvent::class, $acceptJsonListener);

        $jsonContentTypeListener = new ContentTypeJsonRequestListener();
        $ed->addListener(BeforeRequestEvent::class, $jsonContentTypeListener);

        $userAgentListener = new UserAgentRequestListener();
        $ed->addListener(BeforeRequestEvent::class, $userAgentListener);

        return $client;
    }

}