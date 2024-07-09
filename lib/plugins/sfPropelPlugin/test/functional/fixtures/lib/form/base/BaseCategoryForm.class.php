<?php

/**
 * Category form base class.
 *
 * @method Category getObject() Returns the current form's model object
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     Your name here
 */
abstract class BaseCategoryForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(['id'   => new sfWidgetFormInputHidden(), 'name' => new sfWidgetFormInputText()]);

    $this->setValidators(['id'   => new sfValidatorChoice(['choices' => [$this->getObject()->getId()], 'empty_value' => $this->getObject()->getId(), 'required' => false]), 'name' => new sfValidatorString(['max_length' => 255, 'required' => false])]);

    $this->validatorSchema->setPostValidator(
      new sfValidatorPropelUnique(['model' => 'Category', 'column' => ['name']])
    );

    $this->widgetSchema->setNameFormat('category[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Category';
  }


}
