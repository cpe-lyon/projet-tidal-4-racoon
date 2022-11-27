<?php

namespace Helper\App\DB;

/**
 * Classe Condition pour parametrer des conditions SQL sur le DBContext
 * 
 * @author Alexis L. 
 */
class Condition
{
    public static string $queryOperator = 'AND';

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
    public string $operator;

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
     * @param string $key      La clé sous forme de string
     * @param mixed  $value    Valeur de la condition
     * @param string $operator Opération de la condition. Par défaut : `=`
     */
    public function __construct(string $key, mixed $value, string $operator = "=", string $brutBalue = "")
    {
        $this->key = $key;
        if(is_bool($value))
        { $this->value = $value?"TRUE":"FALSE"; }
        if(is_string($value))
        { $this->value = "'$value'"; }
        if(is_int($value))
        { $this->value = $value; }
        if($brutBalue != "")
        { $this->value = $brutBalue; }

        $this->operator = $operator;
    }

    public function generateQuery(bool $first): string
    {
        return ($first ? '': ' ' . self::$queryOperator ) . ' ' . $this->key . ' ' . $this->operator . ' :' . $this->key;
    }

    public function generateParam(): array
    {
        return [$this->key, $this->value];
    }
}
