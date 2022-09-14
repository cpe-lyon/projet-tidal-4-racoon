<?php

namespace Helper\Models;






class Condition
{
    public string $key;
    public $value;
    public string $op;

    /// key est toujours un string
    /// val peut prendre plusieurs types :
    ///     - string    new Condition("key", "value");
    ///     - bool      new Condition("key", true);
    ///     - int       new Condition("key", 5);
    public function __construct(string $key, $val, string $op = "=")
    {
        $this->key = $key;

        if(is_bool($val))
        { $this->value = $val?"TRUE":"FALSE"; }
        if(is_string($val))
        { $this->value = "'$val'"; }
        if(is_int($val))
        { $this->value = $val; }

        $this->op = $op;
    }
}