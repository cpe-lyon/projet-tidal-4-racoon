<?php

namespace Helper\App\DB;

use Helper\App\Constant;
use PDO;
use PDOStatement;

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
    protected PDO $db;
    protected string $query;
    protected array $params;

    /**
     * Juste une requête SQL a executer
     * 
     * @param string $query la requête a executer sur le serveur
     * 
     * @return array|false Retourne le resultat de la requête ou False si un truc a merdé
     */
    public function query(string $query): false|array
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
     *
     * @param mixed            $table      La classe de l'objet `Foo::class`
     * @param Condition[]|null $conditions Liste des conditions à appliquer
     *
     *
     * @return array|false Retourne le resultat de la requête ou `False` si un truc a merdé
     *
     */
    public function get(mixed $table, array $conditions = NULL): bool|array
    {
        if (str_contains($table, '\\')) {
            return $this->getAllClass($table, $conditions);
        }
        $query = $this->getQuery($table, $conditions);
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
     *
     * @param mixed            $class      La classe de l'objet `Foo::class`
     * @param Condition[]|null $conditions Liste des conditions à appliquer
     * 
     * 
     * @return array|false Retourne le resultat de la requête formatté sous forme de classes ou `False` si un truc a merdé
     * 
     */
    private function getAllClass(mixed $class, array $conditions = NULL): bool|array
    {
        $tablename = $this->parseTableName($class);
        $query = $this->getQuery($tablename, $conditions);
        return $query->fetchAll(PDO::FETCH_CLASS, $class);
    }



    /**
     * Recupere tous les elements d'une classe avec une jointure avec une autre classe
     *
     * Exemple :
     * ```
     * $coeur = $Context->getAllJoin(Keywords::class, Symptome::class, KeySympt::class);
     * ```
     *
     * @param string           $fromClass  La classe de la jointure à gauche
     * @param string           $pivotClass La classe de la jointure à droite
     * @param string           $finalClass La classe faisant la jointure entre les deux classes
     * @param Condition[]|null $conditions La liste des conditions pour filtrer le resultat
     *
     * @return array|false Le resultat de fetchAll PDO formatté sous forme d'objet
     */
    public function getJoin(string $fromClass, string $pivotClass, string $finalClass, array $conditions = NULL): bool|array
    {
        $fromVar  = get_class_vars($fromClass);  //Class Left Variables
        $pivotVar = get_class_vars($pivotClass); //Class Join Variables
        $finalVar = get_class_vars($finalClass); //Class Right Variables

        $pivotKey = array_keys(array_intersect_key($fromVar, $pivotVar))[0];  //Left and Join intersection
        $finalKey = array_keys(array_intersect_key($pivotVar, $finalVar))[0]; //Join and Right intersection

        $fromClass  = $this->parseTableName($fromClass);  //ClassName Left
        $pivotClass = $this->parseTableName($pivotClass); //ClassName Join
        $finalClass = $this->parseTableName($finalClass); //ClassName Right

        $sQuery = "SELECT * FROM $fromClass JOIN $pivotClass ON $fromClass.$pivotKey = $pivotClass.$pivotKey JOIN $finalClass ON $pivotClass.$finalKey = $finalClass.$finalKey";

        if($conditions != NULL && sizeof($conditions))
        {
            $sQuery .= " WHERE ";
            foreach ($conditions as $i=>$condition) {
                $sQuery .= $condition->generateQuery($i + 1 < sizeof($conditions));
            }
        }


        $query = $this->getDb()->prepare($sQuery);
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
     * @param string           $table      Soit la classe passée en parametre (`Foo::class`) ou son nom en toutes lettres (`"Foo"`)
     * @param Condition[]|null $conditions La liste des conditions à remplir pour filtrer l'objet `[new Condition("id1", "5")]`
     * 
     * @return mixed Retourne le resultat parsé sous forme d'objet PDO ou sous forme de classe si précisée.
     */
    public function getOne(string $table, ?array $conditions = NULL): mixed
    {
        if (str_contains($table, '\\')) {
            return $this->getItemClass($table, $conditions);
        }
        $query = $this->getQuery($table, $conditions);
        return $query->fetch(PDO::FETCH_OBJ);
    }


    /**
     * Recupère un objet en BDD, parsé avec sa classe
     * 
     * @param string      $class      La classe de l'objet `Foo::class`
     * @param Condition[] $conditions Tableau de conditions pour identifier l'objet à retourner
     * 
     * @return mixed Resultat de fetch ou false si un truc a merdé
     */
    private function getItemClass(string $class, array $conditions): mixed
    {
        $tablename = $this->parseTableName($class);
        $query = $this->getQuery($tablename, $conditions);
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
    public function insert(object $object): bool
    {
        $classname = get_class($object);
        $tablename = $this->parseTableName($classname);
        $query = 'INSERT INTO ' . $tablename . ' (';
        
        $keys = get_class_vars($classname);
        foreach ($object as $key=>$value) 
        {
            $query .= $key;

            if($key != array_key_last($keys)) {
                $query .= ', ';
            }
        }

        $query .= ") VALUES (";

        foreach ($object as $key=>$value) 
        {
            $query .= match (gettype($value)) {
                'boolean' => $value ? "'True'" : "'False'",
                'integer' => $value,
                default   => '\'' . $value . '\'',
            };
            
            if($key != array_key_last($keys)) {
                $query .= ', ';
            }
        }

        $query .= ")";

        return $this
            ->getDb()
            ->prepare($query)
            ->execute();
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
     * @param object        $object L'objet à mettre à jour
     * @param string[]|null $ids    La liste des IDs sous la forme `array('id1', 'id2', ...)`
     * 
     * @return boolean `True` si la mise à jour a réussi ou si aucune mise à jour n'etait nécessaire, `False` si la mise à jour a échoué
     */
    public function update(object $object, array $ids = null) : bool
    {
        $arr = (array)$object;
        $classname = get_class($object);
        $tablename = $this->parseTableName($classname);


        //On parse les IDs pour identifier l'objet à mettre à jour. Par défaut on prends le premier champ si l'ID n'est pas spécifié
        if($ids == null)
            $ids = Array(array_keys($arr)[0] => array_values($arr)[0]);
        else
        {
            $temp = array();
            foreach ($ids as $key => $value) {
                $temp[$value] = $arr[$value];
            }
            $ids = $temp;
        }

        //Condition builder
        $cond = Array();
        foreach ($ids as $idk => $idv) {
            $cond[] = new AndCondition($idk, $idv);
        }

        //On récupère l'objet d'origine
        $orig = $this->getOne($tablename, $cond);
        $origArr = (array)$orig;

        //On check les différences entre l'objet do'irigine et l'objet mis à jour
        $diff = array();
        foreach ($arr as $key => $value) {
            if ($origArr[$key] != $value) {
                $diff[$key] = $value;
            }
        }

        //Si l'objet est identique, on retourne
        if(count($diff) == 0)
        { return false; } 

        
        //Query builder
        $query = "UPDATE $tablename SET ";
        foreach ($diff as $key => $value) {
            switch (gettype($value)) {
                case 'integer':
                    $query .= "$key = $value";
                    break;

                case 'boolean':
                    $query .= "$key = ";
                    $query .= $value ? "'True'" : "'False'";
                    break;
                
                default: //string ou autres fonctionnent avec des quotes.
                    $query .= "$key = '$value'";
                    break;
            }
            if($key != array_key_last($diff))
                $query .= ', ';
        }

        $query .= " WHERE ";

        foreach ($ids as $key=>$value) {
            $query .= match (gettype($value)) {
                'integer' => "$key = $value",
                default   => "$key = '$value'",
            };
            if($key != array_key_last($ids))
                $query .= ' AND ';
        }
        $query .= " RETURNING *";

        $ans = $this->getDb()->prepare($query);
        return $ans->execute();
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

    /**
     * @param string     $tablename
     * @param array|null $conditions
     *
     * @return false|PDOStatement
     */
    private function getQuery(string $tablename, ?array $conditions): PDOStatement|false
    {
        $this->query = "SELECT * FROM $tablename";

        if ($conditions != null && sizeof($conditions)) {
            $this->query .= " WHERE ";
            foreach ($conditions as $key => $condition) {
                /* @var Condition $condition */
                $this->query .= $condition->generateQuery($key == 0);
            }
            $query = $this->getDb()->prepare($this->query);
            foreach ($conditions as $condition) {
                $param = $condition->generateParam();
                $query->bindParam($param[0], $param[1]);
            }
        }
        return $query ?? $this->getDb()->prepare($this->query);
    }
}