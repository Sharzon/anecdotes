<?php

namespace App;

class User
{
    private $id;
    private $login;
    private $password;

    private function __construct($login, $password, $id = null)
    {
        $this->login = $login;
        $this->password = md5($password);
        $this->id = $id;
    }

    public static function create($login, $password)
    {
        $user = new User($login, $password);
        $password = $user->password;

        $pdo = PdoSingleton::get();

        $query = "INSERT INTO users (login, password)
                  VALUES (:login, :password)";

        $statement = $pdo->prepare($query);
        $statement->execute(compact('login', 'password'));

        return $user;
    }

    public static function getById($id)
    {
        $pdo = PdoSingleton::get();

        $query = "SELECT * FROM users WHERE id = ?";

        $statement = $pdo->prepare($query);
        $statement->execute([$id]);

        $result = $statement->fetch();
        if ($result) {
            return new User(
                $result->login,
                $result->password,
                $result->id
            );
        } else {
            return null;
        }
    }

    public static function auth($login, $password)
    {
        $user = new User($login, $password);

        if ($user->isPairValid()) {
            session_start();
            $_SESSION['auth']
            return $user;
        }

        return null;
    }

    private function isPairValid()
    {
        $pdo = PdoSingleton::get();

        $query = "SELECT password FROM users WHERE login = ?";

        $statement = $pdo->prepare($query);
        $statement->execute([$this->login]);
        $password = $statement->fetch();

        return $password === $this->password;
    }
}
