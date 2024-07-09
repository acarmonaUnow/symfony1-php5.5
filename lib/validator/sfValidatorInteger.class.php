<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfValidatorInteger validates an integer. It also converts the input value to an integer.
 *
 * @package    symfony
 * @subpackage validator
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfValidatorInteger.class.php 22018 2009-09-14 16:56:28Z fabien $
 */
class sfValidatorInteger extends sfValidatorBase
{
  /**
   * Configures the current validator.
   *
   * Available options:
   *
   *  * max: The maximum value allowed
   *  * min: The minimum value allowed
   *
   * Available error codes:
   *
   *  * max
   *  * min
   *
   * @param array $options   An array of options
   * @param array $messages  An array of error messages
   *
   * @see sfValidatorBase
   */
  protected function configure($options = [], $messages = [])
  {
    $this->addMessage('max', '"%value%" must be at most %max%.');
    $this->addMessage('min', '"%value%" must be at least %min%.');

    $this->addOption('min');
    $this->addOption('max');

    $this->setMessage('invalid', '"%value%" is not an integer.');
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {
    $clean = intval($value);

    if (strval($clean) != $value)
    {
      throw new sfValidatorError($this, 'invalid', ['value' => $value]);
    }

    if ($this->hasOption('max') && $clean > $this->getOption('max'))
    {
      throw new sfValidatorError($this, 'max', ['value' => $value, 'max' => $this->getOption('max')]);
    }

    if ($this->hasOption('min') && $clean < $this->getOption('min'))
    {
      throw new sfValidatorError($this, 'min', ['value' => $value, 'min' => $this->getOption('min')]);
    }

    return $clean;
  }
}
