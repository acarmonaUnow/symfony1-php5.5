<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$app = 'crud';
$fixtures = 'fixtures/fixtures.yml';
if (!include(__DIR__.'/../../bootstrap/functional.php'))
{
  return;
}

require_once(__DIR__.'/crudBrowser.class.php');

$b = new CrudBrowser();
$b->browse(['generate-in-cache', 'non-verbose-templates']);
