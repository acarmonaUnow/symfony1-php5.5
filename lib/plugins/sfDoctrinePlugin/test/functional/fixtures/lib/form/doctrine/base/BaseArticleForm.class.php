<?php

/**
 * Article form base class.
 *
 * @method Article getObject() Returns the current form's model object
 *
 * @package    symfony12
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseArticleForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(['id'             => new sfWidgetFormInputHidden(), 'author_id'      => new sfWidgetFormDoctrineChoice(['model' => $this->getRelatedModelName('Author'), 'add_empty' => true]), 'is_on_homepage' => new sfWidgetFormInputCheckbox(), 'views'          => new sfWidgetFormInputText(), 'type'           => new sfWidgetFormInputText(), 'created_at'     => new sfWidgetFormDateTime(), 'updated_at'     => new sfWidgetFormDateTime()]);

    $this->setValidators(['id'             => new sfValidatorChoice(['choices' => [$this->getObject()->get('id')], 'empty_value' => $this->getObject()->get('id'), 'required' => false]), 'author_id'      => new sfValidatorDoctrineChoice(['model' => $this->getRelatedModelName('Author'), 'required' => false]), 'is_on_homepage' => new sfValidatorBoolean(['required' => false]), 'views'          => new sfValidatorInteger(['required' => false]), 'type'           => new sfValidatorString(['max_length' => 255, 'required' => false]), 'created_at'     => new sfValidatorDateTime(), 'updated_at'     => new sfValidatorDateTime()]);

    $this->widgetSchema->setNameFormat('article[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Article';
  }

}
