<?php

use \DB\SQL\Mapper;
use \DB\SQL\Session;

final class FavouritesController {
    function __construct() {
        new \DB\SQL\Session(Base::instance()->DB);
    }

    function post(Base $f3): void {
        if($f3->get('SESSION.isLoggedIn')) {
            $body = $f3->BODY ?? NULL;
            if(isset($body)) {
                $jsonObject = json_decode($body);
                if(isset($jsonObject->title) && isset($jsonObject->movieId) && isset($jsonObject->poster)) {
                    $mapper = new Mapper($f3->DB, 'favourites');
                    $mapper->user = $f3->get('SESSION.userId');
                    $mapper->title = $jsonObject->title;
                    $mapper->movie_id = $jsonObject->movieId;
                    $mapper->poster = $jsonObject->poster;

                    try {
                        $mapper->save();
                    } catch(PDOException $e) {
                        if($e->getCode() == 23000) {
                            $this->printError(f3: $f3, code: 400, message: 'This movie is already added to your favourites');
                            return;
                        }
                        $this->printError(f3: $f3, code: 400, message: 'Database error. Check your data');
                        return;
        
                    } catch(Throwable $e) {
                        $this->printError(f3: $f3, code: 400, message: 'Database error. Check your data');
                        return;
                    }
                    echo $this->printSuccess(message: $jsonObject->title . ' added to your favourites.');
                } else {
                    echo $this->printError(f3: $f3, code: 400, message: 'Invalid JSON provided');
                    return;
                }
            } else {
                echo $this->printError(f3: $f3, code: 400, message: 'No JSON provided');
                return;
            }

        } else {
            echo $this->printError(f3: $f3, code: 401, message: 'You are not permitted to perform this action');
            return;
        }
    }

    function delete(Base $f3): void {
        if($f3->get('SESSION.isLoggedIn')) {
            $body = $f3->BODY ?? NULL;
            if(isset($body)) {
                $jsonObject = json_decode($body);
                if(isset($jsonObject->movieId)) {
                    $mapper = new Mapper($f3->DB, 'favourites');
                    $mapper->load(['movie_id = :movieId and user = :userId', ':movieId' => $jsonObject->movieId, ':userId' => $f3->get('SESSION.userId')]);

                    try {
                        $mapper->erase();
                    } catch(PDOException $e) {
                        $this->printError(f3: $f3, code: 400, message: 'Database error. Check your data');
                        return;
        
                    } catch(Throwable $e) {
                        $this->printError(f3: $f3, code: 400, message: 'Database error. Check your data');
                        return;
                    }
                    echo $this->printSuccess(message: 'Movie removed from your favourites.');
                } else {
                    echo $this->printError(f3: $f3, code: 400, message: 'Invalid JSON provided');
                    return;
                }
            } else {
                echo $this->printError(f3: $f3, code: 400, message: 'No JSON provided');
                return;
            }

        } else {
            echo $this->printError(f3: $f3, code: 401, message: 'You are not permitted to perform this action');
            return;
        }
    }

    private function printError(Base $f3, int $code, string $message): void {
        $f3->status($code);
        echo json_encode([
            'status' => 'error',
            'message' => $message
        ]);
    }

    private function printSuccess(string $message): void {
        echo json_encode([
            'status' => 'success',
            'message' => $message
        ]);
    }
}