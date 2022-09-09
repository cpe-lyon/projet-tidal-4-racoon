<?php

namespace Helper\App;

/*
 * Constantes de l'application
 */
class Constant
{
    const DEBUG = true;

    const DB_HOST = 'localhost';
    const DB_NAME = 'racoon';
    const DB_USER = 'postgres';
    const DB_PASS = '*';
    const DB_DSN = 'pgsql:host=' . self::DB_HOST . ';dbname=' . self::DB_NAME;

    const DIR_ROOT = __DIR__ . '/../../';
    const DIR_VENDOR = self::DIR_ROOT . 'vendor/';
    const DIR_TEMPLATES = self::DIR_ROOT . 'templates/';
    const DIR_CACHE = self::DIR_ROOT . 'cache/';
    const DIR_HELPER = self::DIR_ROOT . 'Helper/';
}