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

$b = new sfTestBrowser(new sfBrowser());
$b->setTester('propel', 'sfTesterPropel');

// en
$b->
  get('/i18n/default')->
  with('request')->begin()->
    isParameter('module', 'i18n')->
    isParameter('action', 'default')->
  end()->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('#movies .toString:first', '')->
    checkElement('#movies .default:first', '')->
    checkElement('#movies .it:first', 'La Vita è bella')->
    checkElement('#movies .fr:first', 'La Vie est belle')->
  end()
;

// fr
$b->
  get('/i18n/index')->
  with('request')->begin()->
    isParameter('module', 'i18n')->
    isParameter('action', 'index')->
  end()->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('#movies .toString:first', 'La Vie est belle')->
    checkElement('#movies .default:first', 'La Vie est belle')->
    checkElement('#movies .it:first', 'La Vita è bella')->
    checkElement('#movies .fr:first', 'La Vie est belle')->
  end()
;

// still fr
$b->
  get('/i18n/default')->
  with('request')->begin()->
    isParameter('module', 'i18n')->
    isParameter('action', 'default')->
  end()->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('#movies .toString:first', 'La Vie est belle')->
    checkElement('#movies .default:first', 'La Vie est belle')->
    checkElement('#movies .it:first', 'La Vita è bella')->
    checkElement('#movies .fr:first', 'La Vie est belle')->
  end()
;

// i18n forms
$b->
  get('/i18n/movie')->
  with('request')->begin()->
    isParameter('module', 'i18n')->
    isParameter('action', 'movie')->
  end()->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('#movie_fr_id', false)->
    checkElement('#movie_fr_culture', false)->
  end()->
  
  click('submit', ['movie' => ['director' => 'Robert Aldrich', 'en' => ['title' => 'The Dirty Dozen'], 'fr' => ['title' => 'Les Douze Salopards']]])->
  with('response')->begin()->
    isRedirected()->
    followRedirect()->
  end()->
  with('response')->begin()->
    checkElement('input[value="Robert Aldrich"]')->
    checkElement('input[value="The Dirty Dozen"]')->
    checkElement('input[value="Les Douze Salopards"]')->
    checkElement('#movie_fr_id', true)->
    checkElement('#movie_fr_culture', true)->
  end()->

  with('propel')->begin()->
    check('Movie', [], 2)->
    check('Movie', ['director' => 'Robert Aldrich', 'id' => 2])->
    check('MovieI18N', [], 4)->
    check('MovieI18N', ['id' => 2], 2)->
    check('MovieI18N', ['culture' => 'fr', 'id' => 2, 'title' => 'Les Douze Salopards'])->
    check('MovieI18N', ['culture' => 'en', 'id' => 2, 'title' => 'The Dirty Dozen'])->
  end()->

  click('submit', ['movie' => ['director' => 'Robert Aldrich (1)', 'en' => ['title' => 'The Dirty Dozen (1)'], 'fr' => ['title' => 'Les Douze Salopards (1)']]])->
  with('response')->begin()->
    isRedirected()->
    followRedirect()->
  end()->
  with('response')->begin()->
    checkElement('input[value="Robert Aldrich (1)"]')->
    checkElement('input[value="The Dirty Dozen (1)"]')->
    checkElement('input[value="Les Douze Salopards (1)"]')->
  end()->

  with('propel')->begin()->
    check('Movie', [], 2)->
    check('Movie', ['director' => 'Robert Aldrich (1)', 'id' => 2])->
    check('MovieI18N', [], 4)->
    check('MovieI18N', ['id' => 2], 2)->
    check('MovieI18N', ['culture' => 'fr', 'id' => 2, 'title' => 'Les Douze Salopards (1)'])->
    check('MovieI18N', ['culture' => 'en', 'id' => 2, 'title' => 'The Dirty Dozen (1)'])->
  end()->

  // Bug #7486
  click('submit')->
  
  with('form')->begin()->
    hasErrors(false)->
  end()->

  get('/i18n/movie')->
  click('submit', ['movie' => ['director' => 'Robert Aldrich', 'en' => ['title' => 'The Dirty Dozen (1)'], 'fr' => ['title' => 'Les Douze Salopards (1)']]])->

  with('form')->begin()->
    hasErrors(2)->
  end()->

  click('submit', ['movie' => ['director' => 'Robert Aldrich', 'en' => ['title' => 'The Dirty Dozen'], 'fr' => ['title' => 'Les Douze Salopards']]])->

  with('form')->begin()->
    hasErrors(false)->
  end()->
  with('response')->begin()->
    isRedirected()->
    followRedirect()->
  end()->
  with('response')->begin()->
    checkElement('input[value="Robert Aldrich"]')->
    checkElement('input[value="The Dirty Dozen"]')->
    checkElement('input[value="Les Douze Salopards"]')->
  end()
  // END: Bug #7486
;

$b->getAndCheck('i18n', 'products')
  ->with('response')->begin()
    ->checkElement('ul#products li.toString', 'PRIMARY STRING')
  ->end()
;
