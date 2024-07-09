<?php

/**
 * Attachment form base class.
 *
 * @method Attachment getObject() Returns the current form's model object
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     Your name here
 */
abstract class BaseAttachmentForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(['id'         => new sfWidgetFormInputHidden(), 'article_id' => new sfWidgetFormPropelChoice(['model' => 'Article', 'add_empty' => true]), 'name'       => new sfWidgetFormInputText(), 'file'       => new sfWidgetFormInputText()]);

    $this->setValidators(['id'         => new sfValidatorChoice(['choices' => [$this->getObject()->getId()], 'empty_value' => $this->getObject()->getId(), 'required' => false]), 'article_id' => new sfValidatorPropelChoice(['model' => 'Article', 'column' => 'id', 'required' => false]), 'name'       => new sfValidatorString(['max_length' => 255, 'required' => false]), 'file'       => new sfValidatorString(['max_length' => 255, 'required' => false])]);

    $this->widgetSchema->setNameFormat('attachment[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Attachment';
  }


}
