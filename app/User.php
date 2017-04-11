<?php

namespace Sharzon\Anecdotes;

class User
{
    private $id;
    private $login;
    private $pass_md5;

    private function __construct($login, $password, $id = null)
    {
        $this->login = $login;
        $this->pass_md5 = md5($password);
        $this->id = $id;
    }

    public static function create($login, $password)
    {
        $user = new User($login, $password);
        $pass_md5 = $user->pass_md5;

        $pdo = PdoSingleton::get();

        $query = "INSERT INTO users (login, pass_md5)
                  VALUES (:login, :pass_md5)";

        $statement = $pdo->prepare($query);
        $statement->execute(compact('login', 'pass_md5'));

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

    public static function getByLogin($login)
    {
        $pdo = PdoSingleton::get();

        $query = "SELECT * FROM users WHERE login = ?";

        $statement = $pdo->prepare($query);
        $statement->execute([$login]);

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
        if (self::isPairValid($login, $password)) {
            $user = self::getByLogin($login);

            session_start();
            $_SESSION['auth'] = true;
            $_SESSION['user_id'] = $user->id;

            return $user;
        }

        return null;
    }

    public static function isAuth()
    {
        session_start();
        return isset($_SESSION['auth']) ? $_SESSION['auth'] : false;
    }

    public static function getAuthUser()
    {
        session_start();
        if (isset($_SESSION['auth']) &&
            $_SESSION['auth'] &&
            isset($_SESSION['user_id'])) {
            return $_SESSION['user_auth'];
        } else {
            return null;
        }
    }

    private static function isPairValid($login, $password)
    {
        $pass_md5 = md5($password);

        $pdo = PdoSingleton::get();

        $query = "SELECT * FROM users 
                  WHERE login = :login AND pass_md5 = :pass_md5";

        $statement = $pdo->prepare($query);
        $statement->execute(compact('login', 'pass_md5'));
        $rows = $statement->fetchAll();

        return count($rows) > 0;
    }

    public static function logout()
    {
        session_start();
        session_destroy();
    }

    public function getLogin()
    {
        return $this->login;
    }
}
