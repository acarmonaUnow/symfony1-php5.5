<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once(__DIR__.'/../../bootstrap/unit.php');

$t = new lime_test(1);

class ProjectConfiguration extends sfProjectConfiguration
{
  protected $plugins = ['sfPropelPlugin'];
}
new ProjectConfiguration();

// ->__construct()
$t->diag('->__construct()');

$configuration = ['propel' => ['datasources' => ['propel' => ['adapter' => 'mysql', 'connection' => ['dsn' => 'mysql:dbname=testdb;host=localhost', 'user' => 'foo', 'password' => 'bar', 'classname' => 'PropelPDO', 'options' => ['ATTR_PERSISTENT' => true, 'ATTR_AUTOCOMMIT' => false], 'settings' => ['charset' => ['value' => 'utf8'], 'queries' => []]]], 'default' => 'propel']]];

$parametersTests = ['dsn'        => 'mysql:dbname=testdb;host=localhost', 'username'   => 'foo', 'password'   => 'bar', 'encoding'   => 'utf8', 'persistent' => true, 'options'    => ['ATTR_AUTOCOMMIT' => false]];

$p = new sfPropelDatabase($parametersTests);
$t->is_deeply($p->getConfiguration(), $configuration, '->__construct() creates a valid propel configuration from parameters');
