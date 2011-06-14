<?php

class Glitch_Console_Getopt
    extends Zend_Console_Getopt
{
    protected static $_instances = array();

    /**
     * Polymorphic Singleton ftw
     */
    public static function getInstance($name) {
        if(!isset(self::$_instances[$name])) {
            throw new Exception('No instance with name '.$name.' was set');

        }

        return self::$_instances[$name];
    }

    public function saveInstance($name)
    {
        self::$_instances[$name] = $this;
        return $this;
    }
}
