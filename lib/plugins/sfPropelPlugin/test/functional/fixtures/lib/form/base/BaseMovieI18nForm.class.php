<?php

/**
 * MovieI18n form base class.
 *
 * @method MovieI18n getObject() Returns the current form's model object
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     Your name here
 */
abstract class BaseMovieI18nForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(['id'      => new sfWidgetFormInputHidden(), 'culture' => new sfWidgetFormInputHidden(), 'title'   => new sfWidgetFormInputText()]);

    $this->setValidators(['id'      => new sfValidatorPropelChoice(['model' => 'Movie', 'column' => 'id', 'required' => false]), 'culture' => new sfValidatorChoice(['choices' => [$this->getObject()->getCulture()], 'empty_value' => $this->getObject()->getCulture(), 'required' => false]), 'title'   => new sfValidatorString(['max_length' => 255, 'required' => false])]);

    $this->validatorSchema->setPostValidator(
      new sfValidatorPropelUnique(['model' => 'MovieI18n', 'column' => ['title']])
    );

    $this->widgetSchema->setNameFormat('movie_i18n[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'MovieI18n';
  }


}
