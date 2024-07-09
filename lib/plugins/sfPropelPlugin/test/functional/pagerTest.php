<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$app = 'frontend';
$fixtures = 'fixtures/fixtures.yml';
if (!include(__DIR__.'/../bootstrap/functional.php'))
{
  return;
}

$browser = new sfTestFunctional(new sfBrowser());

ArticlePeer::doDeleteAll();
$category = CategoryPeer::doSelectOne(new Criteria());

foreach (range(1, 20) as $n)
{
  $article = new Article();
  $article->setTitle();
  $article->setCategory($category);
  $article->save();
}

$browser
  ->getAndCheck('pager', 'interfaces')

  ->with('response')->begin()
    ->checkElement('#pagerResults li', 10)
    
    ->checkElement('#pagerCount:contains(20)')
  ->end()
;
