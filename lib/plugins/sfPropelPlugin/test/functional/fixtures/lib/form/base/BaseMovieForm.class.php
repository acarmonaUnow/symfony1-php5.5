<?php

/**
 * Movie form base class.
 *
 * @method Movie getObject() Returns the current form's model object
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     Your name here
 */
abstract class BaseMovieForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(['id'       => new sfWidgetFormInputHidden(), 'director' => new sfWidgetFormInputText()]);

    $this->setValidators(['id'       => new sfValidatorChoice(['choices' => [$this->getObject()->getId()], 'empty_value' => $this->getObject()->getId(), 'required' => false]), 'director' => new sfValidatorString(['max_length' => 255, 'required' => false])]);

    $this->widgetSchema->setNameFormat('movie[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Movie';
  }

  public function getI18nModelName()
  {
    return 'MovieI18n';
  }

  public function getI18nFormClass()
  {
    return 'MovieI18nForm';
  }

}
