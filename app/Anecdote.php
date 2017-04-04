<?php

namespace App;

class Anecdote
{
    protected $id;
    protected $text;
    protected $type;
    protected $accepted;
    protected $got_at;
    protected $accepted_at;

    // Todo: figure out how to define mysql datetime string format
    protected function __construct(
        $text,
        $type,
        $id = null,
        $accepted = 0,
        $got_at = null,
        $accepted_at = null
    ) {
        $this->text = $text;
        $this->type = $type;
        $this->id = $id;
        $this->accepted = $accepted;
        $this->accepted_at = $accepted_at;

        if ($got_at) {
            $this->got_at = $got_at;
        } else {
            $this->got_at = date('Y-m-d H:i:s');
        }
    }

    public static function getById($id)
    {
        $query = "SELECT * FROM anecdotes WHERE id = ?";

        $pdo = PdoSingleton::get();

        $statement = $pdo->prepare($query);
        $statement->execute([$id]);

        $db_anecdote = $statement->fetch();

        $anecdote = new Anecdote(
            $db_anecdote->text,
            AnecdoteType::getById($db_anecdote->type_id),
            $db_anecdote->id,
            $db_anecdote->accepted,
            $db_anecdote->got_at,
            $db_anecdote->accepted_at
        );

        return $anecdote;
    }

    public static function create($text, $type)
    {
        $pdo = PdoSingleton::get();

        $type_id = $type->getId();
        $query = "INSERT INTO anecdotes (`text`, type_id, got_at)
                  VALUES (:text, :type_id, NOW())";

        $statement = $pdo->prepare($query);
        $statement->execute(compact('text', 'type_id'));
        $id = $pdo->lastInsertId();

        return self::getById($id);
    }

    public function accept()
    {
        if ($accepted) {
            return;
        }

        $query = "UPDATE anecdotes 
                  SET accepted = 1,
                      accepted_at = NOW() 
                  WHERE id = ?";

        $pdo = PdoSingleton::get();

        $statement = $pdo->prepare($query);
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

        $pdo = PdoSingleton::get();

        $statement = $pdo->prepare($query);
        $statement->execute([$this->id]);

        $query = "SELECT * FROM anecdotes WHERE id = ?";
        $statement = $pdo->prepare($query);
        $statement->execute([$this->id]);

        if ($statement->fetch()) {
            throw new Exception("Anecdote with id = ".$this->id." isn't deleted.");
        }
            
        $this->id = null;
        $this->text = null;
        $this->type = null;
        $this->accepted = null;
        $this->got_at = null;
        $this->accepted_at = null;
    }
}
