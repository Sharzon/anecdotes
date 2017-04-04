<?php

namespace App;

class AnecdoteType
{
    protected $id;
    protected $name;

    private function __construct($name, $id = null)
    {
        $this->name = $name;
        if ($id) {
            $this->id = $id;
        }
    }

    public static function getById($id)
    {
        $query = "SELECT * FROM types WHERE id = ?";

        $pdo = PdoSingleton::get();

        $statement = $pdo->prepare($query);
        $statement->execute([$id]);

        $db_anecdote_type = $statement->fetch();

        return new AnecdoteType(
            $db_anecdote_type->name,
            $db_anecdote_type->id
        );
    }

    public static function create($name)
    {
        $pdo = PdoSingleton::get();

        $query = "INSERT INTO types (name) VALUES (?)";

        $statement = $pdo->prepare($query);
        $statement->execute([$name]);
        $id = $pdo->lastInsertId();

        return self::getById($id);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }
}
