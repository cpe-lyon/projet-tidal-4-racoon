<?php

namespace Helper\App\DB;

/**
 * Classe Condition pour parametrer des conditions SQL sur le DBContext
 * 
 * @author Alexis L. 
 */
final class AndCondition extends Condition
{
    public static string $queryOperator = 'AND';
}