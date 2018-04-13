<?php

namespace polux\CorePHP\Database\Interfaces;

/**
 * IDatabaseManager
 * 
 * Interface des classes d'accès aux données depuis une source 
 * de type Base de données.
 */
interface IDatabaseManager 
{
    /**
     * executeQuery
     * 
     * Execution d'une requete sur une base de données.
     */
    public static function executeQuery();
}//end interface

