<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetFormI18nChoiceCurrency represents a currency choice widget.
 *
 * @package    symfony
 * @subpackage widget
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfWidgetFormI18nChoiceCurrency.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sfWidgetFormI18nChoiceCurrency extends sfWidgetFormChoice
{
  /**
   * Constructor.
   *
   * Available options:
   *
   *  * culture:    The culture to use for internationalized strings
   *  * currencies: An array of currency codes to use (ISO 4217)
   *  * add_empty:  Whether to add a first empty value or not (false by default)
   *                If the option is not a Boolean, the value will be used as the text value
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetFormChoice
   */
  protected function configure($options = [], $attributes = [])
  {
    parent::configure($options, $attributes);

    $this->addOption('culture');
    $this->addOption('currencies');
    $this->addOption('add_empty', false);

    // populate choices with all currencies
    $culture = $options['culture'] ?? 'en';

    $currencies = sfCultureInfo::getInstance($culture)->getCurrencies($options['currencies'] ?? null);

    $addEmpty = $options['add_empty'] ?? false;
    if (false !== $addEmpty)
    {
      $currencies = array_merge(['' => true === $addEmpty ? '' : $addEmpty], $currencies);
    }

    $this->setOption('choices', $currencies);
  }
}
