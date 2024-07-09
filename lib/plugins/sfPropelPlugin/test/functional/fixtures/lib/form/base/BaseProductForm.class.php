<?php

/**
 * Product form base class.
 *
 * @method Product getObject() Returns the current form's model object
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     Your name here
 */
abstract class BaseProductForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(['id'               => new sfWidgetFormInputHidden(), 'price'            => new sfWidgetFormInputText(), 'a_primary_string' => new sfWidgetFormInputText()]);

    $this->setValidators(['id'               => new sfValidatorChoice(['choices' => [$this->getObject()->getId()], 'empty_value' => $this->getObject()->getId(), 'required' => false]), 'price'            => new sfValidatorNumber(['required' => false]), 'a_primary_string' => new sfValidatorString(['max_length' => 64, 'required' => false])]);

    $this->widgetSchema->setNameFormat('product[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Product';
  }

  public function getI18nModelName()
  {
    return 'ProductI18n';
  }

  public function getI18nFormClass()
  {
    return 'ProductI18nForm';
  }

}
