<?php

namespace C5Deeply;

use Exception;

class Connector
{
    /**
     * concrete5 already initialized?
     *
     * @return bool
     */
    public static function coreIsStarted()
    {
        return defined('C5_EXECUTE') ? true : false;
    }

    /**
     * Start concrete5 (if not already started).
     */
    public static function startCore($webRoot = '')
    {
        if (static::coreIsStarted() === false) {
            if (defined('DIR_BASE')) {
                if (static::isWebRoot(DIR_BASE) === false) {
                    throw new Exception("Unable to find the concrete5 installation in the directory specified by DIR_BASE ('".DIR_BASE."')");
                }
            } else {
                $wr = rtrim((string) $webRoot, '/'.DIRECTORY_SEPARATOR);
                if ($wr !== '') {
                    if (!static::isWebRoot($wr)) {
                        throw new Exception("'$webRoot' does not contain a concrete5 installation");
                    }
                } else {
                    $cd = rtrim(getcwd(), '/'.DIRECTORY_SEPARATOR);
                    if (static::isWebRoot($check = $cd)) {
                        $wr = $check;
                    } elseif (static::isWebRoot($check = $cd.'/web')) {
                        $wr = $check;
                    } elseif (static::isWebRoot($check = dirname($cd))) {
                        $wr = $check;
                    } elseif (static::isWebRoot($check = dirname(dirname($cd)))) {
                        $wr = $check;
                    } elseif (static::isWebRoot($check = dirname(dirname(dirname($cd))))) {
                        $wr = $check;
                    } elseif (static::isWebRoot($check = dirname(dirname(dirname(dirname($cd)))))) {
                        $wr = $check;
                    }
                    if ($wr === '') {
                        throw new Exception("Unable to find the concrete5 installation in current directory ('$cd')");
                    }
                }
                define('DIR_BASE', $wr);
            }
            defined('C5_ENVIRONMENT_ONLY') or define('C5_ENVIRONMENT_ONLY', true);
            require_once DIR_BASE.'/concrete/dispatcher.php';
        }
    }

    /**
     * Check if a directory is the web root of a concrete5 installation.
     *
     * @param string $dir
     *
     * @return bool
     */
    private static function isWebRoot($dir)
    {
        return (is_string($dir) && ($dir !== '') && is_dir($dir) && is_file($dir.'/index.php') && is_file($dir.'/concrete/dispatcher.php')) ? true : false;
    }
}
