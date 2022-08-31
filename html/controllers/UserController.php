<?php

use \DB\SQL\Mapper;

final class UserController {
    function get(Base $f3, array $args): void {
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
            $mapper->email = $body->email;
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

            $this->printSuccess(message: 'User login success');

        } else {
            $this->printError(f3: $f3, code: 400, message: 'Invalid request');
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

    private function createDbConnection(): \DB\SQL {
        $options = array(
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_PERSISTENT => TRUE,
            \PDO::MYSQL_ATTR_COMPRESS => TRUE
        );
        return new \DB\SQL('mysql:host=localhost;port=3306;dbname=Polina_Proj',$f3->get('DB_USER'),$f3->get('DB_PASSWORD'), $options);
    }
}