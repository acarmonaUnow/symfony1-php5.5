<?php

/*
 * This file is part of SwiftMailer.
 * (c) 2004-2009 Chris Corbyn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * General utility class in Swift Mailer, not to be instantiated.
 * 
 * @package Swift
 * 
 * @author Chris Corbyn
 */
abstract class Swift
{
  
  static $initialized = false;
  static $initPath;
  
  /** Swift Mailer Version number generated during dist release process */
  public const VERSION = '4.1.8';
  
  /**
   * Internal autoloader for spl_autoload_register().
   * 
   * @param string $class
   */
  public static function autoload($class)
  {
    //Don't interfere with other autoloaders
    if (!str_starts_with($class, 'Swift_'))
    {
      return;
    }

    $path = __DIR__.'/'.str_replace('_', '/', $class).'.php';

    if (!file_exists($path))
    {
      return;
    }

    if (self::$initPath && !self::$initialized)
    {
      self::$initialized = true;
      require self::$initPath;
    }

    require $path;
  }
  
  /**
   * Configure autoloading using Swift Mailer.
   * 
   * This is designed to play nicely with other autoloaders.
   *
   * @param string $initPath The init script to load when autoloading the first Swift class
   */
  public static function registerAutoload($initPath = null)
  {
    self::$initPath = $initPath;
    spl_autoload_register(['Swift', 'autoload']);
  }
  
}
