<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfValidatorSchemaCompare compares several values from an array.
 *
 * @package    symfony
 * @subpackage validator
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfValidatorSchemaCompare.class.php 21908 2009-09-11 12:06:21Z fabien $
 */
class sfValidatorSchemaCompare extends sfValidatorSchema
{
  public const EQUAL              = '==';
  public const NOT_EQUAL          = '!=';
  public const IDENTICAL          = '===';
  public const NOT_IDENTICAL      = '!==';
  public const LESS_THAN          = '<';
  public const LESS_THAN_EQUAL    = '<=';
  public const GREATER_THAN       = '>';
  public const GREATER_THAN_EQUAL = '>=';

  /**
   * Constructor.
   *
   * Available options:
   *
   *  * left_field:         The left field name
   *  * operator:           The comparison operator
   *                          * self::EQUAL
   *                          * self::NOT_EQUAL
   *                          * self::IDENTICAL
   *                          * self::NOT_IDENTICAL
   *                          * self::LESS_THAN
   *                          * self::LESS_THAN_EQUAL
   *                          * self::GREATER_THAN
   *                          * self::GREATER_THAN_EQUAL
   *  * right_field:        The right field name
   *  * throw_global_error: Whether to throw a global error (false by default) or an error tied to the left field
   *
   * @param string $leftField   The left field name
   * @param string $operator    The operator to apply
   * @param string $rightField  The right field name
   * @param array  $options     An array of options
   * @param array  $messages    An array of error messages
   *
   * @see sfValidatorBase
   */
  public function __construct($leftField, $operator, $rightField, $options = [], $messages = [])
  {
    $this->addOption('left_field', $leftField);
    $this->addOption('operator', $operator);
    $this->addOption('right_field', $rightField);

    $this->addOption('throw_global_error', false);

    parent::__construct(null, $options, $messages);
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($values)
  {
    if (null === $values)
    {
      $values = [];
    }

    if (!is_array($values))
    {
      throw new InvalidArgumentException('You must pass an array parameter to the clean() method');
    }

    $leftValue  = $values[$this->getOption('left_field')] ?? null;
    $rightValue = $values[$this->getOption('right_field')] ?? null;

    $valid = match ($this->getOption('operator')) {
        self::GREATER_THAN => $leftValue > $rightValue,
        self::GREATER_THAN_EQUAL => $leftValue >= $rightValue,
        self::LESS_THAN => $leftValue < $rightValue,
        self::LESS_THAN_EQUAL => $leftValue <= $rightValue,
        self::NOT_EQUAL => $leftValue != $rightValue,
        self::EQUAL => $leftValue == $rightValue,
        self::NOT_IDENTICAL => $leftValue !== $rightValue,
        self::IDENTICAL => $leftValue === $rightValue,
        default => throw new InvalidArgumentException(sprintf('The operator "%s" does not exist.', $this->getOption('operator'))),
    };

    if (!$valid)
    {
      $error = new sfValidatorError($this, 'invalid', ['left_field'  => $leftValue, 'right_field' => $rightValue, 'operator'    => $this->getOption('operator')]);
      if ($this->getOption('throw_global_error'))
      {
        throw $error;
      }

      throw new sfValidatorErrorSchema($this, [$this->getOption('left_field') => $error]);
    }

    return $values;
  }

  /**
   * @see sfValidatorBase
   */
  public function asString($indent = 0)
  {
    $options = $this->getOptionsWithoutDefaults();
    $messages = $this->getMessagesWithoutDefaults();
    unset($options['left_field'], $options['operator'], $options['right_field']);

    $arguments = '';
    if ($options || $messages)
    {
      $arguments = sprintf('(%s%s)',
        $options ? sfYamlInline::dump($options) : ($messages ? '{}' : ''),
        $messages ? ', '.sfYamlInline::dump($messages) : ''
      );
    }

    return sprintf('%s%s %s%s %s',
      str_repeat(' ', $indent),
      $this->getOption('left_field'),
      $this->getOption('operator'),
      $arguments,
      $this->getOption('right_field')
    );
  }
}
