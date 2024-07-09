<?php

/**
 * BaseModelFromLinkedSchema
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $name
 * 
 * @method string                getName() Returns the current record's "name" value
 * @method ModelFromLinkedSchema setName() Sets the current record's "name" value
 * 
 * @package    symfony12
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseModelFromLinkedSchema extends myDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('model_from_linked_schema');
        $this->hasColumn('name', 'string', 255, ['type' => 'string', 'length' => 255]);

        $this->option('symfony', ['form' => false, 'filter' => false]);
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}