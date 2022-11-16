<?php

namespace Helper\MVC\Model;





/**
 * Classe Condition pour parametrer des conditions SQL sur le DBContext
 * 
 * @author Alexis L. 
 */
class Condition
{
    /** 
     * Clé sur laquelle se base la condition
     * @var string Nom de la clé en string
     */
    public string $key;

    /**
     * Valeur de la condition
     * @var mixed peut être tout type de variable existant en BDD
     */
    public mixed $value;

    /**
     * Opération à executer sur la condition/ 
     * Par défaut : `=`
     * @var string C'est un string.
     */
    public string $op;

    /// key est toujours un string
    /// val peut prendre plusieurs types :
    ///     - string    new Condition("key", "value");
    ///     - bool      new Condition("key", true);
    ///     - int       new Condition("key", 5);
    /**
     * Constructeur de condition
     * 
     * Exemples : 
     * ```
     * new Condition("key", "value");   // Un string
     * new Condition("key", true);      // Un boolean
     * new Condition("key", 5);         // Un int
     * new Condition("key", 5, "!=")    // Pas un 5
     * ```
     * 
     * @param string $key   La clé sous forme de string
     * @param mixed $val    Valeur de la condition
     * @param string $op    Opération de la condition. Par défaut : `=`
     */
    public function __construct(string $key, $val, string $op = "=", string $brutBalue = "")
    {
        $this->key = $key;
        if(is_bool($val))
        { $this->value = $val?"TRUE":"FALSE"; }
        if(is_string($val))
        { $this->value = "'$val'"; }
        if(is_int($val))
        { $this->value = $val; }
        if($brutBalue != "")
        { $this->value = $brutBalue; }
        $this->op = $op;
    }
}