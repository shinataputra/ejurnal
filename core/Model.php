<?php
// core/Model.php

class Model
{
    protected $db;

    public function __construct()
    {
        $this->db = db();
    }

    public function getDb()
    {
        return $this->db;
    }
}
