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
 * @package     Glitch_Application
 * @subpackage  Resource
 * @author      4worx <info@4worx.com>
 * @copyright   2010, 4worx
 * @version     $Id$
 */

/**
 * Resource for setting translation options
 *
 * This resource allows just one adapter: an INI file. INI files have excellent performance
 * (also when compared to gettext files) and are easy to maintain. In addition, make sure
 * the cachemanager is configured properly for even better performance.
 *
 * Note that a primary feature of Zend_Translate is NOT used: scanning of translation files.
 * Although it's tempting to use it, directory scanning kills performance. Instead,
 * this resource looks for (1) a shared translation file (e.g. "nl_NL.ini") and, on request,
 * (2) a module-specific translation file (e.g. "nl_NL.Default.ini").
 *
 * @category    Glitch
 * @package     Glitch_Application
 * @subpackage  Resource
 */
class Glitch_Application_Resource_Translate
    extends Zend_Application_Resource_Translate
//    implements Glitch_Application_Resource_ModuleInterface
{

    /**
     * Retrieves the translate object
     *
     * @return Zend_Translate
     */
    public function getTranslate()
    {
        if (null === $this->_translate)
        {
            $options = $this->getOptions();
            $this->_setCache();
            if(isset($options['cache'])) {
                unset($options['cache']);
            }

            $this->_bootstrap->bootstrap('Log');
            $options['log'] = $this->_bootstrap->getResource('Log');

            if($options['modular']) {
                $iterator = new FilesystemIterator($options['dataDir'], FilesystemIterator::SKIP_DOTS);
                foreach($iterator as $item) {
                    if($item->isDir()) {
                        $options['content'][] = (string) $item;
                    }
                }
            } else {
                throw new Exception('Not implemented yet');
            }

            $this->_options = $options;
            parent::getTranslate();
        }

        return $this->_translate;
    }

    protected function _setCache()
    {
        $options = $this->getOptions();

        // Disable cache? If not defined, cache will be active
        if (isset($options['cache']['active']) && !$options['cache']['active'])
        {
            // Explicitly remove cache, in case it was set before
            Zend_Translate::removeCache();
            return;
        }

        // Get the cache using the config settings as input
        $this->_bootstrap->bootstrap('CacheManager');
        $manager = $this->_bootstrap->getResource('CacheManager');
        $cache = $manager->getCache('translate');

        // Write caching errors to log file (if activated in the config)
        $this->_bootstrap->bootstrap('Log');
        $logger = $this->_bootstrap->getResource('Log');
        $cache->setOption('logger', $logger);

        Zend_Translate::setCache($cache);
    }
}
