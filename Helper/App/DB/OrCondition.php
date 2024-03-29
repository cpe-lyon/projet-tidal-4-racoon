<?php

namespace Helper\App\DB;

/**
 * Classe Condition pour parametrer des conditions SQL sur le DBContext
 * 
 * @author Alexis L. 
 */
class OrCondition extends Condition
{
    public static string $queryOperator = 'OR';

    public function generateQuery(bool $first): string
    {
        return ($first ? '': ' ' . self::$queryOperator ) . ' ' . $this->key . ' ' . $this->operator . ' :' . $this->key;
    }
}