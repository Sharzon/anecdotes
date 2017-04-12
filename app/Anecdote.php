<?php

namespace Sharzon\Anecdotes;

use PDO;

class Anecdote
{
    const PAGE_ANECDOTES_AMOUNT = 3;
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
        $pdo = PdoSingleton::get();

        $query = "SELECT * FROM anecdotes WHERE id = ?";

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

    public static function getAllAccepted()
    {
        $pdo = PdoSingleton::get();

        $query = "SELECT * FROM anecdotes 
                  WHERE accepted = 1
                  ORDER BY accepted_at DESC";

        $statement = $pdo->prepare($query);
        $statement->execute();

        $raw_anecdotes = $statement->fetchAll();
        $anecdotes = [];

        foreach ($raw_anecdotes as $raw_anecdote) {
            $anecdotes[] = new Anecdote(
                $raw_anecdote->text,
                AnecdoteType::getById($raw_anecdote->type_id),
                $raw_anecdote->id,
                $raw_anecdote->accepted,
                $raw_anecdote->got_at,
                $raw_anecdote->accepted_at
            );
        }

        return $anecdotes;
    }

    public static function getAllNotAccepted()
    {
        $pdo = PdoSingleton::get();

        $query = "SELECT * FROM anecdotes 
                  WHERE accepted = 0
                  ORDER BY got_at";

        $statement = $pdo->prepare($query);
        $statement->execute();

        $raw_anecdotes = $statement->fetchAll();
        $anecdotes = [];

        foreach ($raw_anecdotes as $raw_anecdote) {
            $anecdotes[] = new Anecdote(
                $raw_anecdote->text,
                AnecdoteType::getById($raw_anecdote->type_id),
                $raw_anecdote->id,
                $raw_anecdote->accepted,
                $raw_anecdote->got_at
            );
        }

        return $anecdotes;
    }

    public static function getAllAcceptedByType($type)
    {
        $pdo = PdoSingleton::get();

        $query = "SELECT * FROM anecdotes 
                  WHERE type_id = ? AND 
                        accepted = 1
                  ORDER BY accepted_at DESC";

        $statement = $pdo->prepare($query);
        $statement->execute([$type->getId()]);

        $raw_anecdotes = $statement->fetchAll();
        $anecdotes = [];

        foreach ($raw_anecdotes as $raw_anecdote) {
            $anecdotes[] = new Anecdote(
                $raw_anecdote->text,
                $type,
                $raw_anecdote->id,
                $raw_anecdote->accepted,
                $raw_anecdote->got_at,
                $raw_anecdote->accepted_at
            );
        }

        return $anecdotes;
    }

    public static function getAllByType($type)
    {
        $pdo = PdoSingleton::get();

        $query = "SELECT * FROM anecdotes 
                  WHERE type_id = ?";

        $statement = $pdo->prepare($query);
        $statement->execute([$type->getId()]);

        $raw_anecdotes = $statement->fetchAll();
        $anecdotes = [];

        foreach ($raw_anecdotes as $raw_anecdote) {
            $anecdotes[] = new Anecdote(
                $raw_anecdote->text,
                $type,
                $raw_anecdote->id,
                $raw_anecdote->accepted,
                $raw_anecdote->got_at,
                $raw_anecdote->accepted_at
            );
        }

        return $anecdotes;
    }

    public static function getPageAcceptedByType($type, $page = 1)
    {
        $pdo = PdoSingleton::get();

        $query = "SELECT * FROM anecdotes 
                  WHERE type_id = :type_id AND
                        accepted = 1
                  ORDER BY accepted_at DESC
                  LIMIT :start, ".self::PAGE_ANECDOTES_AMOUNT;

        $type_id = $type->getId();
        $start = ($page - 1) * self::PAGE_ANECDOTES_AMOUNT;

        $statement = $pdo->prepare($query);
        $statement->execute(compact('type_id', 'start'));

        $raw_anecdotes = $statement->fetchAll();
        $anecdotes = [];

        foreach ($raw_anecdotes as $raw_anecdote) {
            $anecdotes[] = new Anecdote(
                $raw_anecdote->text,
                $type,
                $raw_anecdote->id,
                $raw_anecdote->accepted,
                $raw_anecdote->got_at,
                $raw_anecdote->accepted_at
            );
        }

        return $anecdotes;
    }

    public static function getPageAccepted($page = 1)
    {
        $pdo = PdoSingleton::get();

        $query = "SELECT * FROM anecdotes 
                  WHERE accepted = 1
                  ORDER BY accepted_at DESC
                  LIMIT ?, ".self::PAGE_ANECDOTES_AMOUNT;

        $start = ($page - 1) * self::PAGE_ANECDOTES_AMOUNT;

        $statement = $pdo->prepare($query);
        $statement->execute([$start]);

        $raw_anecdotes = $statement->fetchAll();
        $anecdotes = [];

        foreach ($raw_anecdotes as $raw_anecdote) {
            $anecdotes[] = new Anecdote(
                $raw_anecdote->text,
                AnecdoteType::getById($raw_anecdote->type_id),
                $raw_anecdote->id,
                $raw_anecdote->accepted,
                $raw_anecdote->got_at,
                $raw_anecdote->accepted_at
            );
        }

        return $anecdotes;
    }

    public static function pagesCount()
    {
        $pdo = PdoSingleton::get();

        $query = "SELECT COUNT(*) FROM anecdotes
                  WHERE accepted = 1";

        $statement = $pdo->prepare($query);
        $statement->execute();

        $rows_count = $statement->fetch(PDO::FETCH_NUM)[0];

        return ceil($rows_count / self::PAGE_ANECDOTES_AMOUNT);
    }

    public static function pagesCountByType($type_id)
    {
        $pdo = PdoSingleton::get();

        $query = "SELECT COUNT(*) 
                  FROM anecdotes 
                  WHERE type_id = ? AND accepted = 1";

        $statement = $pdo->prepare($query);
        $result = $statement->execute([$type_id]);

        $rows_count = $statement->fetch(PDO::FETCH_NUM)[0];

        return ceil($rows_count / self::PAGE_ANECDOTES_AMOUNT);
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

        $pdo = PdoSingleton::get();

        $query = "UPDATE anecdotes 
                  SET accepted = 1,
                      accepted_at = NOW() 
                  WHERE id = ?";

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

    public function decline()
    {
        $pdo = PdoSingleton::get();

        $query = "DELETE FROM anecdotes
                  WHERE id = ?";

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

    public function changeType($type_id)
    {
        if ($type_id == $this->type->getId()) {
            return;
        }

        $pdo = PdoSingleton::get();

        $query = "UPDATE anecdotes 
                  SET type_id = :type_id 
                  WHERE id = :id";

        $id = $this->id;

        $statement = $pdo->prepare($query);
        $statement->execute(compact('id', 'type_id'));

        $anecdote = self::getById($this->id);

        $this->id = $anecdote->id;
        $this->text = $anecdote->text;
        $this->type = $anecdote->type;
        $this->accepted = $anecdote->accepted;
        $this->got_at = $anecdote->got_at;
        $this->accepted_at = $anecdote->accepted_at;
    }

    public function getText()
    {
        return $this->text;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getType()
    {
        return $this->type;
    }
}
