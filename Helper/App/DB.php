<?php

namespace Helper\App;

use App\Keyword as AppKeyword;
use PDO;
use Helper\Models\Keyword;
use Helper\Models\Keywords;

//Exemple : $Context = new DB();
class DB
{
    private PDO $db;


    public function rawQuery(string $query): false|array
    {
        return $this->getDb()->query($query)->fetchAll();
    }

    //$Context->getAll("keywords");
    public function getAll($table, $conditions = NULL)
    {
        if (str_contains($table, '\\')) {
            return $this->getAllClass($table, $conditions);
        }
        $qString = "SELECT * FROM $table";

        if($conditions != NULL && sizeof($conditions))
        {
            $qString .= " WHERE ";
            foreach ($conditions as $i=>$condition) {
                $qString .= $condition->key ." " . $condition->op . " " . $condition->value;
                if($i+1 < sizeof($conditions))
                { $qString .= " AND "; }
            }
        }

        $query = $this->getDb()->prepare($qString);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    //Exemple : $Context->getAll(Keyword::class)   // retourne la liste de Keyword de la table keywords
    //Exemple : $Context->getAll(Keyword::class, [new Condition("idk", 5)]) //Cherche tous les keywords ayant l'ID 5
    private function getAllClass($class, $conditions = NULL)
    {
        $tablename = $this->parseTableName($class);
        $qString = "SELECT * FROM $tablename";

        if($conditions != NULL && sizeof($conditions))
        {
            $qString .= " WHERE ";
            foreach ($conditions as $i=>$condition) {
                $qString .= $condition->key ." " . $condition->op . " " . $condition->value;
                if($i+1 < sizeof($conditions))
                { $qString .= " AND "; }
            }
        }

        $query = $this->getDb()->prepare($qString);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_CLASS, $class);
    }

    public function getAllJoin($classLeft, $classRight, $classJoin, $conditions = NULL)
    {
        $clv = get_class_vars($classLeft); //Class Left Variables
        $cjv = get_class_vars($classJoin); //Class Join Variables
        $crv = get_class_vars($classRight); //Class Right Variables

        $lj = array_keys(array_intersect_key($clv, $cjv))[0]; //Left and Join intersection
        $jr = array_keys(array_intersect_key($cjv, $crv))[0]; //Join and Right intersection

        $cnl = $this->parseTableName($classLeft); //ClassName Left
        $cnj = $this->parseTableName($classJoin); //ClassName Join
        $cnr = $this->parseTableName($classRight); //ClassName Right

        $q = "SELECT * FROM $cnl JOIN $cnj ON $cnl.$lj = $cnj.$lj JOIN $cnr ON $cnj.$jr = $cnr.$jr";


        if($conditions != NULL && sizeof($conditions))
        {
            $q .= " WHERE ";
            foreach ($conditions as $i=>$condition) {
                $q .= $condition->key ." " . $condition->op . " " . $condition->value;
                if($i+1 < sizeof($conditions))
                { $q .= " AND "; }
            }
        }


        $query = $this->getDb()->prepare($q);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }


    //Exemple : $Context->getAll(Keyword::class, [new Condition("idk", 5)]) //Cherche le premier keyword ayant l'ID 5
    public function getItem($table, $conditions = NULL)
    {
        if (str_contains($table, '\\')) {
            return $this->getItemClass($table, $conditions);
        }

        $qString = "SELECT * FROM $table WHERE ";

        foreach ($conditions as $i=>$condition) {
            $qString .= $condition->key ." " . $condition->op . " " . $condition->value;
            if($i+1 < sizeof($conditions))
            { $qString .= " AND "; }
        }

            
        $query = $this->getDb()->prepare($qString);
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ);
    }

    private function getItemClass($class, $conditions)
    {
        $tablename = $this->parseTableName($class);
        $qString = "SELECT * FROM $tablename WHERE ";

        foreach ($conditions as $i=>$condition) {
            $qString .= $condition->key ." " . $condition->op . " " . $condition->value;
            if($i+1 < sizeof($conditions))
            { $qString .= " AND "; }
        }

        $query = $this->getDb()->prepare($qString);
        $query->execute();
        return $query->fetch(PDO::FETCH_CLASS, $class);
    }


    public function insert($object)
    {
        $classname = get_class($object);
        $tablename = $this->parseTableName($classname);
        $query = 'INSERT INTO `' . $tablename . '` (';

        $keys = get_class_vars($classname);

        $i = 1;
        foreach ($object as $key=>$value) 
        {
            $query .= '`'.$key.'`';

            if($i < count($keys)) {
                $query .= ', ';
            }

            $i++;
        }

        $query .= ") VALUES (";

        foreach ($object as $value) 
        {
            $query .= '`'.$value.'`';

            if($i < count($keys)) {
                $query .= ', ';
            }

            $i++;
        }
    }




    private function getDb(): PDO
    {
        if (!isset($this->db)) {
            $this->db = new PDO(Constant::DB_DSN, Constant::DB_USER, Constant::DB_PASS);
        }
        return $this->db;
    }



    private function parseTableName($table)
    {
        $temp = explode('\\', $table);
        return end($temp);
    }
}