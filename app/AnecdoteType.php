<?php

namespace Sharzon\Anecdotes;

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

    public static function getAll()
    {
        $pdo = PdoSingleton::get();

        $query = "SELECT * FROM types";

        $statement = $pdo->prepare($query);
        $statement->execute();

        $raw_types = $statement->fetchAll();

        $types = [];

        foreach ($raw_types as $type) {
            $types[] = new AnecdoteType($type->name, $type->id);
        }

        return $types;
    }

    public static function getById($id)
    {
        $pdo = PdoSingleton::get();

        $query = "SELECT * FROM types WHERE id = ?";

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

    public function rename($name)
    {
        $pdo = PdoSingleton::get();

        $id = $this->id;

        $query = "UPDATE types
                  SET name = :name
                  WHERE id = :id";

        $statement = $pdo->prepare($query);
        $statement->execute(compact('id', 'name'));
        
        $this->name = self::getById($id)->getName();
    }

    public function remove($subst_type_id)
    {
        $anecdotes = Anecdote::getAllByType($this);

        foreach ($anecdotes as $anecdote) {
            $anecdote->changeType($subst_type_id);
        }

        $pdo = PdoSingleton::get();

        $query = "DELETE FROM types WHERE id = ?";

        $statement = $pdo->prepare($query);
        $statement->execute([$this->id]);

        $this->name = null;
        $this->id = null;
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
