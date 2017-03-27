<?php

namespace App;

class Anecdote
{
    protected $type_filter = null;
    protected $page_filter = null;
    protected $accepting_filter = null;

    protected $pdo = PdoFactory::create();

    public function filterByType($type)
    {
        $this->type_filter = $type;

        return $this;
    }

    public function filterByPage($page)
    {
        $this->page_filter = $page;

        return $this;
    }
    public function filterNonAccepted()
    {
        $this->accepting_filter = false;

        return $this;
    }

    public function filterAccepted()
    {
        $this->accepting_filter = true;
    }

    public function getAll()
    {

    }

    public function getById($id)
    {
        $query = "SELECT * FROM anecdotes WHERE id = ?";
        $statement = $this->pdo->prepare($query);
        $statement->execute([$id]);

        return $statement->fetch();
    }
    
    public function accept()
    {
        
    }

    public function notAccept()
    {

    }
}
