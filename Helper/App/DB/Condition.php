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
    public bool $isLike = false;

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
     * @param string $brutValue Permet de mettre un valeur sans guillemet
     */
    public function __construct(string $key, mixed $value, string $operator = "=", string $brutValue = "")
    {
        $this->key = $key;
        $this->value = $value;
        if($brutValue != "")
        { $this->value = $brutValue; }

        $this->operator = $operator;
    }

    public function generateQuery(bool $first): string
    {
        $result = "";
        if (!$first) {
            $result .= " " . static::$queryOperator . " ";
        }
        if ($this->value === null) {
            $result .= $this->key . " IS NULL";
        } elseif (is_array($this->value)) {
            $result .= $this->key . " " . $this->operator . " (";
            foreach ($this->value as $key => $value) {
                $result .= ':' . str_replace('.', '_', $this->key) . substr("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ" , $key, $key+1) . ',';
            }
            $result = substr($result, 0, -1);
            $result .= ")";
            $this->isLike = true;
        } else {
            $result .= $this->key . " " . $this->operator . " :" . str_replace('.', '_', $this->key);
        }
        return $result;
    }

    public function generateParam(): array
    {
        if ($this->isLike) {
            $result = [];
            foreach ($this->value as $key => $value) {
                $result[] = [str_replace('.', '_', $this->key) . substr("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ" , $key, $key+1), $value];
            }
            return $result;
        } else {
            return [$this->key, $this->value];
        }
    }
}
