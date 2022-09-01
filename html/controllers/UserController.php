<?php

use \DB\SQL\Mapper;
use \DB\SQL\Session;

final class UserController {

    function __construct() {
        new \DB\SQL\Session(Base::instance()->DB);
    }

    function get(Base $f3, array $args): void {
        if($f3->get('SESSION.isLoggedIn')) {
            $f3->reroute('/account');
            return;
        }
        $f3->set('pageTitle', 'Login / Register');
        $f3->set('content', 'login.htm');
        echo \Template::instance()->render('main.htm');
    }

    function post(Base $f3, array $args): void {
        $body = json_decode($f3->get('BODY'));
        if(!isset($body->action)) {
            $this->printError(f3: $f3, code: 400, message: 'Invalid request');
            return;
        }
    
        $crypt = \Bcrypt::instance();
        $mapper = new Mapper($f3->DB, 'user');

        if(strtolower($body->action) === 'register') {
            $mapper->email = $body->email ?? NULL;
            $mapper->name = $body->name ?? NULL;
            $mapper->password = \Bcrypt::instance()->hash($body->password);
            try {
                $mapper->save();
            } catch(PDOException $e) {
                if($e->getCode() == 23000) {
                    $this->printError(f3: $f3, code: 400, message: 'User with this email exists');
                    return;
                }
                $this->printError(f3: $f3, code: 400, message: 'Database error. Check your data');
                return;

            } catch(Throwable $e) {
                $this->printError(f3: $f3, code: 400, message: 'Database error. Check your data');
                return;
            }
            $f3->set('SESSION.isLoggedIn',true);
            $f3->set('SESSION.userId', $mapper->id);
            $f3->set('SESSION.email', $mapper->email);
            $f3->set('SESSION.name', $mapper->name);
            $this->printSuccess(message: 'user created');
            return;
        } else if (strtolower($body->action) === 'login') {
            $user = $mapper->findone(['email = :email', ':email' => $body->email]);
            if(!$user) {
                $this->printError(f3: $f3, code: 400, message: 'email and/or password invalid');
                return;
            }
            if(!$crypt->verify($body->password, $user->password)) {
                $this->printError(f3: $f3, code: 400, message: 'email and/or password invalid');
                return;
            }

            $f3->set('SESSION.isLoggedIn',true);    
            $f3->set('SESSION.userId', $user->id);
            $f3->set('SESSION.email', $user->email);
            $f3->set('SESSION.name', $user->name);
            $this->printSuccess(message: 'User login success');

        } else {
            $this->printError(f3: $f3, code: 400, message: 'Invalid request');
        }
    }

    public function logout(Base $f3): void {
        $f3->clear('SESSION');
        $f3->reroute('login');
    }

    public function getAccountScreen(Base $f3, array $args): void {
        if($f3->get('SESSION.isLoggedIn') && $f3->get('SESSION.userId') !== NULL) {
            $mapper = new Mapper($f3->DB, 'favourites');
            $result = $mapper->find(['user = :userId', ':userId' => $f3->get('SESSION.userId')]);
            $f3->set('list', $result);
            $f3->set('pageTitle', 'Movies');
            $f3->set('content', 'account.htm');
            echo \Template::instance()->render('main.htm');
        } else {
            $f3->reroute('/login');
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