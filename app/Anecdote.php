<?php

namespace App;

class Anecdote
{
    protected static $pdo;

    public $id;
    public $text;
    public $type;
    public $accepted;
    public $got_at;
    public $accepted_at;

    protected function __construct(
        $id,
        $text,
        $type,
        $accepted,
        $got_at,
        $accepted_at
    ) {
        $this->id = $id;
        $this->text = $text;
        $this->type = $type;
        $this->accepted = $accepted;
        $this->got_at = $got_at;
        $this->accepted_at = $accepted_at;
    }

    public static function getById($id)
    {
        $query = "SELECT * FROM anecdotes WHERE id = ?";

        if (!self::$pdo)
            self::makePdo();
        $statement = self::$pdo->prepare($query);
        $statement->execute([$id]);

        $db_anecdote = $statement->fetch();

        $anecdote = new Anecdote(
            $db_anecdote->id,
            $db_anecdote->text,
            $db_anecdote->type_id,
            $db_anecdote->accepted,
            $db_anecdote->got_at,
            $db_anecdote->accepted_at
        );

        return $anecdote;
    }

    public function accept()
    {
        $query = "UPDATE anecdotes 
                  SET accepted = 1,
                      accepted_at = NOW() 
                  WHERE id = ?";

        if (!self::$pdo)
            self::makePdo();
        $statement = self::$pdo->prepare($query);
        $statement->execute([$this->id]);

        $anecdote = self::getById($this->id);

        $this->id = $anecdote->id;
        $this->text = $anecdote->text;
        $this->type = $anecdote->type;
        $this->accepted = $anecdote->accepted;
        $this->got_at = $anecdote->got_at;
        $this->accepted_at = $anecdote->accepted_at;
    }

    public function notAccept()
    {
        $query = "DELETE FROM anecdotes
                  WHERE id = ?";
    }

    protected static function makePdo()
    {
        self::$pdo = PdoFactory::create();
    }
}
