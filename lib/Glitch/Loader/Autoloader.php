<?php
/**
 * Glitch
 *
 * This source file is proprietary and protected by international
 * copyright and trade secret laws. No part of this source file may
 * be reproduced, copied, adapted, modified, distributed, transferred,
 * translated, disclosed, displayed or otherwise used by anyone in any
 * form or by any means without the express written authorization of
 * 4worx software innovators BV (www.4worx.com)
 *
 * @category    Glitch
 * @package     Glitch_Loader
 * @author      4worx <info@4worx.com>
 * @copyright   2010, 4worx
 * @version     $Id$
 */

// Path must be set as no autoload() exists at this point
require_once 'Zend/Loader/Autoloader/Interface.php';

/**
 * Loader for autoloading classes
 *
 * This class exists for performance: loading classes instantly, bypassing ZF's
 * internal, cumbersome, autoloading mechanisms. Note, however, that ZF will still
 * try to set its own autoload function via Zend_Loader_Autoloader::getInstance().
 * That's alright, though, as the custom autoloader underneath preceeds ZF's and
 * therefore acts as the default one.
 *
 * @category   Glitch
 * @package    Glitch_Loader
 */
class Glitch_Loader_Autoloader implements Zend_Loader_Autoloader_Interface
{
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        spl_autoload_register(array($this, 'autoload'));
    }

    /**
     * Autoloads a class
     *
     * This method aims to load files and classes as soon as possible, so no additional
     * checks occur - e.g. whether or not the file exists nor whether or not the class
     * exists in the file.
     *
     * @param string $class
     * @return bool
     */
    public function autoload($class)
    {
        $filename = $this->getFileNameFromClassName($class);

        // Don't use require_once: halts execution instantly when file is not found
        $isLoaded = include_once $filename;

        if (!class_exists($class, false) && !interface_exists($class, false)) {
            $isLoaded = $this->fallbackAutoload($class);
        }

        return (false !== $isLoaded);
    }

    /**
     * Extract the file name from the class name
     *
     * @param string $class
     * @return string Class filename
     */
    protected function getFileNameFromClassName($class)
    {
        // E.g. "Zend_Application" --> "Zend/Application.php"
        return str_replace(array('_', '\\'), DIRECTORY_SEPARATOR, $class) . '.php';
    }

    /**
     * Autoload classes for all paths
     *
     * @param string $class
     * @return void
     */
    protected function fallbackAutoload($class)
    {
        $filename = $this->getFileNameFromClassName($class);

        $expath = explode(PATH_SEPARATOR, get_include_path());
        foreach ($expath as $path) {
            $path .= DIRECTORY_SEPARATOR . $filename;
            if (file_exists($path)) {
                require_once $path;
                if (class_exists($class, false) && !interface_exists($class, false)) {
                    return true;
                }
            }
        }
        return false;
    }
}
