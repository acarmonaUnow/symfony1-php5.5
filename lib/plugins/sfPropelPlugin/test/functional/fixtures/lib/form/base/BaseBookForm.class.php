<?php

/**
 * Book form base class.
 *
 * @method Book getObject() Returns the current form's model object
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     Your name here
 */
abstract class BaseBookForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(['id'   => new sfWidgetFormInputHidden(), 'name' => new sfWidgetFormInputText()]);

    $this->setValidators(['id'   => new sfValidatorChoice(['choices' => [$this->getObject()->getId()], 'empty_value' => $this->getObject()->getId(), 'required' => false]), 'name' => new sfValidatorString(['max_length' => 255, 'required' => false])]);

    $this->widgetSchema->setNameFormat('book[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Book';
  }


}
