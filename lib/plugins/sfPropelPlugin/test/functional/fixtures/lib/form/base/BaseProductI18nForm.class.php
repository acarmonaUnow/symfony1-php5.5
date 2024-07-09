<?php

/**
 * ProductI18n form base class.
 *
 * @method ProductI18n getObject() Returns the current form's model object
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     Your name here
 */
abstract class BaseProductI18nForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(['id'      => new sfWidgetFormInputHidden(), 'culture' => new sfWidgetFormInputHidden(), 'name'    => new sfWidgetFormInputText()]);

    $this->setValidators(['id'      => new sfValidatorPropelChoice(['model' => 'Product', 'column' => 'id', 'required' => false]), 'culture' => new sfValidatorChoice(['choices' => [$this->getObject()->getCulture()], 'empty_value' => $this->getObject()->getCulture(), 'required' => false]), 'name'    => new sfValidatorString(['max_length' => 50, 'required' => false])]);

    $this->widgetSchema->setNameFormat('product_i18n[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'ProductI18n';
  }


}
