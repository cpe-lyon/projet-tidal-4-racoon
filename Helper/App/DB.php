<?php

namespace Helper\App;

use Helper\Models\Condition;
use PDO;

/**
 * Contexte de la BDD
 * 
 * Exemple : 
 * ```
 * $Context = new DB();
 * ```
 * 
 * @author Alexis L.
 */
class DB
{
    private PDO $db;

    /**
     * Juste une requête SQL a executer
     * 
     * @param string $query la requête a executer sur le serveur
     * 
     * @return array|false Retourne le resultat de la requête ou False si un truc a merdé
     */
    public function rawQuery(string $query): false|array
    {
        return $this->getDb()->query($query)->fetchAll();
    }

    //$Context->getAll("keywords");
    /**
     * Recupère tous les éléments d'une table en BDD
     * 
     * Exemples :
     * ```
     * $Context->getAll("keywords")                             // retourne la liste de Keyword de la table keywords
     * $Context->getAll("keywords", [new Condition("idk", 5)])  // Cherche tous les keywords ayant l'ID 5
     * ```
     * @param mixed $class La classe de l'objet `Foo::class`
     * @param Condition[] $conditions Liste des conditions à appliquer
     * 
     * 
     * @return array|false Retourne le resultat de la requête ou `False` si un truc a merdé
     * 
     */
    public function getAll($table, $conditions = NULL)
    {
        if (str_contains($table, '\\')) {
            return $this->getAllClass($table, $conditions);
        }
        $qString = "SELECT * FROM $table";

        if ($conditions != NULL && sizeof($conditions)) {
            $qString .= " WHERE ";
            foreach ($conditions as $i => $condition) {
                $qString .= $condition->key . " " . $condition->op . " " . $condition->value;
                if ($i + 1 < sizeof($conditions)) {
                    $qString .= " AND ";
                }
            }
        }

        $query = $this->getDb()->prepare($qString);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Recupère tous les éléments d'une table en BDD
     * 
     * Le resultat est formatté en fonction de la classe donnée
     * 
     * Exemples :
     * ```
     * $Context->getAll(Keyword::class)                             // retourne la liste de Keyword de la table keywords
     * $Context->getAll(Keyword::class, [new Condition("idk", 5)])  // Cherche tous les keywords ayant l'ID 5
     * ```
     * @param mixed $class La classe de l'objet `Foo::class`
     * @param Condition[] $conditions Liste des conditions à appliquer
     * 
     * 
     * @return array|false Retourne le resultat de la requête formatté sous forme de classes ou `False` si un truc a merdé
     * 
     */
    private function getAllClass($class, $conditions = NULL)
    {
        $tablename = $this->parseTableName($class);
        $qString = "SELECT * FROM $tablename";

        if ($conditions != NULL && sizeof($conditions)) {
            $qString .= " WHERE ";
            foreach ($conditions as $i => $condition) {
                $qString .= $condition->key . " " . $condition->op . " " . $condition->value;
                if ($i + 1 < sizeof($conditions)) {
                    $qString .= " AND ";
                }
            }
        }

        $query = $this->getDb()->prepare($qString);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_CLASS, $class);
    }



    /**
     * Recupere tous les elements d'une classe avec une jointure avec une autre classe
     * 
     * Exemple : 
     * ```
     * $❤️ = $Context->getAllJoin(Keywords::class, Symptome::class, KeySympt::class);
     * ```
     * 
     * @param string $classLeft La classe de la jointure à gauche
     * @param string $classRight La classe de la jointure à droite
     * @param string $classJoin La classe faisant la jointure entre les deux classes
     * @param Condition[] $conditions La liste des conditions pour filtrer le resultat
     * 
     * @return array|false Le resultat de fetchAll PDO formatté sous forme d'objet
     */
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


        if ($conditions != NULL && sizeof($conditions)) {
            $q .= " WHERE ";
            foreach ($conditions as $i => $condition) {
                $q .= $condition->key . " " . $condition->op . " " . $condition->value;
                if ($i + 1 < sizeof($conditions)) {
                    $q .= " AND ";
                }
            }
        }


        $query = $this->getDb()->prepare($q);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }


    /**
     * Recupère un objet en BDD
     * 
     * Les filtres sont configurés avec un tableau de conditions
     * 
     * Example : 
     * ```
     *  $Context->getAll(Keyword::class, [new Condition("idk", 5)])
     * ```
     * 
     * @param string $table  Soit la classe passée en parametre (`Foo::class`) ou son nom en toutes lettres (`"Foo"`)
     * @param Condition[] $conditions   La liste des conditions à remplir pour filtrer l'objet `[new Condition("id1", "5")]`
     * 
     * @return mixed Retourne le resultat parsé sous forme d'objet PDO ou sous forme de classe si précisée.
     */
    public function getItem($table, $conditions = NULL)
    {
        if (str_contains($table, '\\')) {
            return $this->getItemClass($table, $conditions);
        }

        $qString = "SELECT * FROM $table WHERE ";

        if ($conditions == NULL || !sizeof($conditions)) {
            return False;
        }

        foreach ($conditions as $i => $condition) {
            $qString .= $condition->key . " " . $condition->op . " " . $condition->value;
            if ($i + 1 < sizeof($conditions)) {
                $qString .= " AND ";
            }
        }


        $query = $this->getDb()->prepare($qString);
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ);
    }


    /**
     * @author Louisa C.
     * Recupère un ou plusieurs attributs d'une table en BDD, avec une condition
     * Les filtres sont configurés avec un tableau de conditions
     * Exemple : $Context->getAll(Keyword::class, [new Condition("idk", 5)])
     * 
     * @param string $table  Soit la classe passée en parametre (`Foo::class`) ou son nom en toutes lettres (`"Foo"`)
     * @param string[] $attributs  Tableau des attributs à récupérer dans la requête
     * @param Condition[] $conditions   La liste des conditions à remplir pour filtrer l'objet `[new Condition("id1", "5")]`
     * 
     * @return mixed Retourne le resultat parsé sous forme d'objet PDO ou sous forme de classe si la condition est précisée.
     */
    public function getItemsWhere($table, $attributs = ['*'], $conditions = NULL)
    {
        // Construction de la liste des attributs
        $attributsString = implode(",", $attributs);

        if ($conditions == NULL || !sizeof($conditions)) {
            // Si aucune condition précisée, on construit quand même une requête valide
            $qString = "SELECT $attributsString FROM $table";
        } else {
            $qString = "SELECT $attributsString FROM $table WHERE ";

            foreach ($conditions as $i => $condition) {
                $qString .= $condition->key . " " . $condition->op . " " . $condition->value;
                if ($i + 1 < sizeof($conditions)) {
                    $qString .= " AND ";
                }
            }
        }

        $query = $this->getDb()->prepare($qString);
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ);
    }


    /**
     * Recupère un objet en BDD, parsé avec sa classe
     * 
     * @param mixed $class La classe de l'objet `Foo::class`
     * @param Condition[] $conditions Tableau de conditions pour identifier l'objet à retourner
     * 
     * @return mixed Resultat de fetch ou false si un truc a merdé
     */
    private function getItemClass($class, $conditions)
    {
        $tablename = $this->parseTableName($class);
        $qString = "SELECT * FROM $tablename WHERE ";

        if ($conditions == NULL || !sizeof($conditions)) {
            return False;
        }

        foreach ($conditions as $i => $condition) {
            $qString .= $condition->key . " " . $condition->op . " " . $condition->value;
            if ($i + 1 < sizeof($conditions)) {
                $qString .= " AND ";
            }
        }

        $query = $this->getDb()->prepare($qString);
        $query->execute();
        return $query->fetch(PDO::FETCH_CLASS, $class);
    }

    //Exemple : 
    //  $oToInsert = new something;
    //  $answer = $Context->insert($oToInsert);
    /**
     * Ajoute un objet dans la BDD
     * 
     * L'objet est automatiquement ajouté dans la table correspondante.
     * 
     * Exemple : 
     * ```
     * $m = new Meridien();
     * $m->code = "A";
     * $m->nom = "aaa";
     * $m->element = "b";
     * $m->yin = true;
     * $a = $Context->insert($m);
     * ```
     * 
     * @param object $object L'objet à ajouter dans la BDD.
     * 
     * @return boolean Reussite ou non de la requête 
     */
    public function insert($object)
    {
        $classname = get_class($object);
        $tablename = $this->parseTableName($classname);
        $query = 'INSERT INTO ' . $tablename . ' (';

        $keys = get_class_vars($classname);
        foreach ($object as $key => $value) {
            $query .= $key;

            if ($key != array_key_last($keys)) {
                $query .= ', ';
            }
        }

        $query .= ") VALUES (";

        foreach ($object as $key => $value) {
            switch (gettype($value)) {
                case 'boolean':
                    $query .= $value ? "'True'" : "'False'";
                    break;
                case 'integer':
                    $query .= $value;
                    break;

                default: //string ou autres fonctionnent avec des quotes.
                    $query .= '\'' . $value . '\'';
                    break;
            }

            if ($key != array_key_last($keys)) {
                $query .= ', ';
            }
        }

        $query .= ")";

        $ans = $this->getDb()->prepare($query);
        $ans = $ans->execute();
        return $ans;
    }


    /**
     * @author Louisa C.
     * Ajoute un objet dans une table voulue de la BDD, et prend en compte les attributs vides (liés à une insertion automatique par PostgreSQL)
     * 
     * @param object $object L'objet à ajouter dans la BDD.
     * @return boolean Reussite ou non de la requête 
     */
    public function insertNotAll($object)
    {
        $classname = get_class($object);
        $tablename = $this->parseTableName($classname);
        $query = 'INSERT INTO ' . $tablename . ' (';

        $keys = get_class_vars($classname);
        foreach ($object as $key => $value) {
            if ($value) {
                $query .= $key . ', ';
            }
        }
        $query = substr($query, 0, -2);

        $query .= ") VALUES (";

        foreach ($object as $key => $value) {
            if ($value) {
                switch (gettype($value)) {
                    case 'boolean':
                        $query .= $value ? "'True'" : "'False'";
                        break;
                    case 'integer':
                        $query .= $value;
                        break;

                    default: //string ou autres fonctionnent avec des quotes.
                        $query .= '\'' . $value . '\'';
                        break;
                }
                $query .= ', ';
            }
        }
        $query = substr($query, 0, -2);

        $query .= ")";

        $ans = $this->getDb()->prepare($query);
        $ans = $ans->execute();
        return $ans;
    }


    /**
     * Met a jout un objet de la base de données. 
     * 
     * Par défaut, la fonction prendra la première propriété de la classe en tant qu'ID. 
     * Il faut donc spécifier les IDs dans le cas d'un objet avec une clé composée.
     * 
     * Example :
     * 
     * ```
     *  $a = $Context->update($m);                          // Update un objet
     *  $a = $Context->update($s, array("ids", "idp"));     // Update un objet en spécifiant les IDs de l'objet
     *  ```
     * 
     * @param object $object  L'objet à mettre à jour
     * @param string[] $ids     La liste des IDs sous la forme `array('id1', 'id2', ...)`
     * 
     * @return boolean `True` si la mise à jour a réussi ou si aucune mise à jour n'etait nécessaire, `False` si la mise à jour a échoué
     */
    public function update($object, $ids = null): bool
    {
        $arr = (array)$object;
        $classname = get_class($object);
        $tablename = $this->parseTableName($classname);


        //On parse les IDs pour identifier l'objet à mettre à jour. Par défaut on prends le premier champ si l'ID n'est pas spécifié
        if ($ids == null) {
            $ids = array(array_keys($arr)[0] => array_values($arr)[0]);
        } else {
            $temp = array();
            foreach ($ids as $key => $value) {
                $temp[$value] = $arr[$value];
            }
            $ids = $temp;
        }

        //Condition builder
        $cond = array();
        foreach ($ids as $idk => $idv) {
            $cond[] = new Condition($idk, $idv);
        }

        //On récupère l'objet d'origine
        $orig = $this->getItem($tablename, $cond);
        $origArr = (array)$orig;

        //On check les différences entre l'objet do'irigine et l'objet mis à jour
        $diff = array();
        foreach ($arr as $key => $val) {
            if ($origArr[$key] != $val) {
                $diff[$key] = $val;
            }
        }

        //Si l'objet est identique, on retourne
        if (count($diff) == 0) {
            return false;
        }


        //Query builder
        $query = "UPDATE $tablename SET ";
        foreach ($diff as $key => $value) {
            switch (gettype($val)) {
                case 'integer':
                    $query .= "$key = $val";
                    break;

                case 'boolean':
                    $query .= "$key = ";
                    $query .= $value ? "'True'" : "'False'";
                    break;

                default: //string ou autres fonctionnent avec des quotes.
                    $query .= "$key = '$val'";
                    break;
            }
            if ($key != array_key_last($diff)) {
                $query .= ', ';
            }
        }

        $query .= " WHERE ";

        foreach ($ids as $key => $val) {
            switch (gettype($val)) {
                case 'integer':
                    $query .= "$key = $val";
                    break;

                default: //string ou autres fonctionnent avec des quotes.
                    $query .= "$key = '$val'";
                    break;
            }
            if ($key != array_key_last($ids)) {
                $query .= ' AND ';
            }
        }
        $query .= " RETURNING *";

        $ans = $this->getDb()->prepare($query);
        $ans = $ans->execute();
        return $ans;
    }



    /**
     * Recupère la DB ou l'initialise
     * 
     * @return PDO Le contexte PDO de la BDD 
     */
    private function getDb(): PDO
    {
        if (!isset($this->db)) {
            $this->db = new PDO(Constant::DB_DSN, Constant::DB_USER, Constant::DB_PASS);
        }
        return $this->db;
    }


    /**
     * Parse le nom de la table pour les requêtes SQL
     * 
     * @param string $table Le nom de la classe PHP : `get_class($object)`
     * 
     * @return string Le nom de la table SQL
     */
    private function parseTableName(string $table): string
    {
        $temp = explode('\\', $table);
        return end($temp);
    }
}
