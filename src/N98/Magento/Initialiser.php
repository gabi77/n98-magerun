<?php

/*
 * this file is part of magerun
 *
 * @author Tom Klingenberg <https://github.com/ktomk>
 */

namespace N98\Magento;

use N98\Util\AutoloadRestorer;

/**
 * Magento initialiser (Magento 1)
 *
 * @package N98\Magento
 */
class Initialiser
{
    const PATH_APP_MAGE_PHP = 'app/Mage.php';

    /**
     * @var string path to Magento root directory
     */
    private $magentoPath;

    /**
     * Bootstrap Magento application
     */
    public static function bootstrap($magentoPath)
    {
        $initialiser = new Initialiser($magentoPath);
        $initialiser->requireMage();
    }

    /**
     * Initialiser constructor.
     *
     * @param string $magentoPath
     */
    public function __construct($magentoPath)
    {
        $this->magentoPath = $magentoPath;
    }


    /**
     * Require app/Mage.php if class Mage does not yet exists. Preserves auto-loaders
     *
     * @see \Mage (final class)
     */
    public function requireMage()
    {
        if (!class_exists('Mage', false)) {
            // Create a new AutoloadRestorer to capture current auto-loaders
            $restorer = new AutoloadRestorer();

            $path = $this->magentoPath . '/' . self::PATH_APP_MAGE_PHP;

            // require app/Mage.php from Magento in a function of it's own to have it's own variable scope
            $this->requireOnce($path);
            // Restore auto-loaders that might be removed by extensions that overwrite Varien/Autoload
            $restorer->restore();
        }
    }

    /**
     * use require-once inside a function with it's own variable scope w/o any other variables
     * and $this unbound.
     *
     * @param string $path
     */
    private function requireOnce($path)
    {
        initialiser_require_once($path);
    }
}

/**
 * use require-once inside a function with it's own variable scope and no $this (?)
 */
function initialiser_require_once()
{
    require_once func_get_arg(0);
}
