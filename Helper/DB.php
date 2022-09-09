<?php

namespace App;

use PDO;
use App\Keyword;

class DB
{
    private PDO $db;


    public function rawQuery(string $query): false|array
    {
        return $this->getDb()->query($query)->fetchAll();
    }

    public function getKeywords()
    {
        return $this->getDb()->query("SELECT * FROM keywords")->fetchAll(PDO::FETCH_CLASS, Keyword::class);
    }

    public function getKeyword(int $key)
    {
        return $this->getDb()->query("SELECT * FROM keywords WHERE idk = $key")->fetchObject(Keyword::class);
    }

    private function getDb(): PDO
    {
        if (!isset($this->db)) {
            $this->db = new PDO(Constant::DB_DSN, Constant::DB_USER, Constant::DB_PASS);
        }
        return $this->db;
    }
}