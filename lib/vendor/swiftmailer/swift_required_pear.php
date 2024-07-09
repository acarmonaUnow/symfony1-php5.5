<?php

/*
 * This file is part of SwiftMailer.
 * (c) 2004-2009 Chris Corbyn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/*
 * Autoloader and dependency injection initialization for Swift Mailer.
 */

if (defined('SWIFT_REQUIRED_LOADED'))
	return;

define('SWIFT_REQUIRED_LOADED', true);

//Load Swift utility class
require __DIR__ . '/Swift.php';

//Start the autoloader
Swift::registerAutoload();

//Load the init script to set up dependency injection
require __DIR__ . '/swift_init.php';
