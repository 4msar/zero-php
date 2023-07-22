<?php

/**
 * Simple Recursive Autoloader
 * 
 * A simple autoloader that loads class files recursively starting in the directory
 * where this class resides.  Additional options can be provided to control the naming
 * convention of the class files.
 *
 * @package Autoloader
 * @license http://opensource.org/licenses/MIT  MIT License
 * @author  Rob Dunham <contact@robunham.info>
 * @author  Saiful Alam <hello@msar.me>
 */
class Autoloader
{
    /**
     * File extension as a string. Defaults to ".php".
     */
    protected static $fileExt = '.php';

    /**
     * The top level directory where recursion will begin. Defaults to the current
     * directory.
     */
    protected static $pathTop = __DIR__;

    /**
     * A placeholder to hold the file iterator so that directory traversal is only
     * performed once.
     */
    protected static $fileIterator = null;

    /**
     * Autoload function for registration with spl_autoload_register
     *
     * Looks recursively through project directory and loads class files based on
     * filename match.
     *
     * @param string $className
     */
    public static function loader($className)
    {
        $directory = new RecursiveDirectoryIterator(static::$pathTop, RecursiveDirectoryIterator::SKIP_DOTS);
        if (is_null(static::$fileIterator)) {
            static::$fileIterator = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::LEAVES_ONLY);
        }

        /** 
         * Check the file has namespace or not
         */

        $isNamspacedFile = strpos($className, "\\") !== false;
        $startsWithApp = substr($className, 0, strlen('App')) === "App";

        if ($isNamspacedFile && $startsWithApp) {
            $namespacePath = str_replace(["\\"], DIRECTORY_SEPARATOR, substr($className, 3));
            $filePath = self::$pathTop . lcfirst($namespacePath) . static::$fileExt;
            if (file_exists($filePath)) {
                include_once $filePath;
            }
            return;
        }

        /** 
         *Otherwise load the file instead
         */
        $parts = explode('\\', $className);
        $className = end($parts);
        $filename = $className . static::$fileExt;
        foreach (static::$fileIterator as $file) {
            if (strtolower($file->getFilename()) === strtolower($filename)) {
                if ($file->isReadable()) {
                    include_once $file->getPathname();
                }
                break;
            }
        }
    }

    /**
     * Sets the $fileExt property
     *
     * @param string $fileExt The file extension used for class files.  Default is "php".
     */
    public static function setFileExt($fileExt)
    {
        static::$fileExt = $fileExt;
    }

    /**
     * Sets the $path property
     *
     * @param string $path The path representing the top level where recursion should
     *                     begin. Defaults to the current directory.
     */
    public static function setPath($path)
    {
        static::$pathTop = $path;
    }
}

// EOF