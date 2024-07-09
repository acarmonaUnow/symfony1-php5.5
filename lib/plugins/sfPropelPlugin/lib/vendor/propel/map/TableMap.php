<?php
/*
 *  $Id: TableMap.php 1262 2009-10-26 20:54:39Z francois $
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information please see
 * <http://propel.phpdb.org>.
 */

/**
 * TableMap is used to model a table in a database.
 *
 * GENERAL NOTE
 * ------------
 * The propel.map classes are abstract building-block classes for modeling
 * the database at runtime.  These classes are similar (a lite version) to the
 * propel.engine.database.model classes, which are build-time modeling classes.
 * These classes in themselves do not do any database metadata lookups.
 *
 * @author     Hans Lellelid <hans@xmpl.org> (Propel)
 * @author     John D. McNally <jmcnally@collab.net> (Torque)
 * @author     Daniel Rall <dlr@finemaltcoding.com> (Torque)
 * @version    $Revision: 1262 $
 * @package    propel.map
 */
class TableMap {

  // The columns in the table
  protected $columns = [];

  // The database this table belongs to
  protected $dbMap;

  // The name of the table
  protected $tableName;

  // The PHP name of the table
  protected $phpName;

  // The Classname for this table
  protected $classname;

  // The Package for this table
  protected $package;
  
  // Whether to use an id generator for pkey
  protected $useIdGenerator;
  
  // The primary key columns in the table
  protected $primaryKeys = [];
  
  // The foreign key columns in the table
  protected $foreignKeys = [];

  // The relationships in the table
  protected $relations = [];

  // Relations are lazy loaded. This property tells if the relations are loaded or not
  protected $relationsBuilt = false;
  
  // Object to store information that is needed if the for generating primary keys
  protected $pkInfo;
  
  /**
   * Construct a new TableMap.
   *
   */
  public function __construct($name = null, $dbMap = null)
  {
    if(!is_null($name)) $this->setName($name);
    if(!is_null($dbMap)) $this->setDatabaseMap($dbMap);
    $this->initialize();
  }
  
  /**
   * Initialize the TableMap to build columns, relations, etc
   * This method should be overridden by descendents
   */
  public function initialize()
  {
  }

  /**
   * Set the DatabaseMap containing this TableMap.
   *
   * @param     DatabaseMap $dbMap A DatabaseMap.
   */
  public function setDatabaseMap(DatabaseMap $dbMap)
  {
    $this->dbMap = $dbMap;
  }
  
  /**
   * Get the DatabaseMap containing this TableMap.
   *
   * @return     DatabaseMap A DatabaseMap.
   */
  public function getDatabaseMap()
  {
    return $this->dbMap;
  }

  /**
   * Set the name of the Table.
   *
   * @param      string $name The name of the table.
   */
  public function setName($name)
  {
    $this->tableName = $name;
  }
  
  /**
   * Get the name of the Table.
   *
   * @return     string A String with the name of the table.
   */
  public function getName()
  {
    return $this->tableName;
  }

  /**
   * Set the PHP name of the Table.
   *
   * @param      string $phpName The PHP Name for this table
   */
  public function setPhpName($phpName)
  {
    $this->phpName = $phpName;
  }
  
  /**
   * Get the PHP name of the Table.
   *
   * @return     string A String with the name of the table.
   */
  public function getPhpName()
  {
    return $this->phpName;
  }

  /**
   * Set the Classname of the Table. Could be useful for calling
   * Peer and Object methods dynamically.
   * @param      string $classname The Classname
   */
  public function setClassname($classname)
  {
    $this->classname = $classname;
  }

  /**
   * Get the Classname of the Propel-Classes belonging to this table.
   * @return     string
   */
  public function getClassname()
  {
    return $this->classname;
  }
  
  /**
   * Set the Package of the Table
   *
   * @param      string $package The Package
   */
  public function setPackage($package)
  {
    $this->package = $package;
  }

  /**
   * Get the Package of the table.
   * @return     string
   */
  public function getPackage()
  {
    return $this->package;
  }
    
  /**
   * Set whether or not to use Id generator for primary key.
   * @param      boolean $bit
   */
  public function setUseIdGenerator($bit)
  {
    $this->useIdGenerator = $bit;
  }

  /**
   * Whether to use Id generator for primary key.
   * @return     boolean
   */
  public function isUseIdGenerator()
  {
    return $this->useIdGenerator;
  }

  /**
   * Sets the pk information needed to generate a key
   *
   * @param      $pkInfo information needed to generate a key
   */
  public function setPrimaryKeyMethodInfo($pkInfo)
  {
    $this->pkInfo = $pkInfo;
  }
  
  /**
   * Get the information used to generate a primary key
   *
   * @return     An Object.
   */
  public function getPrimaryKeyMethodInfo()
  {
    return $this->pkInfo;
  }

  /**
   * Add a column to the table.
   *
   * @param      string name A String with the column name.
   * @param      string $type A string specifying the Propel type.
   * @param      boolean $isNotNull Whether column does not allow NULL values.
   * @param      int $size An int specifying the size.
   * @param      boolean $pk True if column is a primary key.
   * @param      string $fkTable A String with the foreign key table name.
   * @param      $fkColumn A String with the foreign key column name.
   * @param      string $defaultValue The default value for this column.
   * @return     ColumnMap The newly created column.
   */
  public function addColumn($name, $phpName, $type, $isNotNull = false, $size = null, $defaultValue = null, $pk = false, $fkTable = null, $fkColumn = null)
  {

    $col = new ColumnMap($name, $this);

    if ($fkTable && $fkColumn) {
      if (strpos((string) $fkColumn, '.') > 0 && str_contains((string) $fkColumn, $fkTable)) {
        $fkColumn = substr((string) $fkColumn, strlen($fkTable) + 1);
      }
      $col->setForeignKey($fkTable, $fkColumn);
      $this->foreignKeys[$name] = $col;
    }

    $col->setType($type);
    $col->setSize($size);
    $col->setPhpName($phpName);
    $col->setNotNull($isNotNull);
    $col->setDefaultValue($defaultValue);
    if ($pk) {
      $col->setPrimaryKey(true);
      $this->primaryKeys[$name] = $col;
    }
    $this->columns[$name] = $col;

    return $this->columns[$name];
  }
  
  /**
   * Add a pre-created column to this table. It will replace any
   * existing column.
   *
   * @param      ColumnMap $cmap A ColumnMap.
   * @return     ColumnMap The added column map.
   */
  public function addConfiguredColumn($cmap)
  {
    $this->columns[ $cmap->getColumnName() ] = $cmap;
    return $cmap;
  }
  
  /**
   * Does this table contain the specified column?
   *
   * @param      mixed   $name name of the column or ColumnMap instance
   * @param      boolean $normalize Normalize the column name (if column name not like FIRST_NAME)
   * @return     boolean True if the table contains the column.
   */
  public function hasColumn(mixed $name, $normalize = true)
  {
    if ($name instanceof ColumnMap) {
      $name = $name->getColumnName();
    } else if($normalize) {
      $name = ColumnMap::normalizeName($name);
    }
    return isset($this->columns[$name]);
  }
  
  /**
   * Get a ColumnMap for the named table.
   *
   * @param      string    $name A String with the name of the table.
   * @param      boolean   $normalize Normalize the column name (if column name not like FIRST_NAME)
   * @return     ColumnMap A ColumnMap.
   * @throws     PropelException if the column is undefined
   */
  public function getColumn($name, $normalize = true)
  {
    if ($normalize) {
      $name = ColumnMap::normalizeName($name);
    }
    if (!$this->containsColumn($name, false)) {
      throw new PropelException("Cannot fetch ColumnMap for undefined column: " . $name);
    }
    return $this->columns[$name];
  }
  
  /**
   * Get a ColumnMap[] of the columns in this table.
   *
   * @return     array A ColumnMap[].
   */
  public function getColumns()
  {
    return $this->columns;
  }

  /**
   * Add a primary key column to this Table.
   *
   * @param      string $columnName A String with the column name.
   * @param      string $type A string specifying the Propel type.
   * @param      boolean $isNotNull Whether column does not allow NULL values.
   * @param      $size An int specifying the size.
   * @return     ColumnMap Newly added PrimaryKey column.
   */
  public function addPrimaryKey($columnName, $phpName, $type, $isNotNull = false, $size = null, $defaultValue = null)
  {
    return $this->addColumn($columnName, $phpName, $type, $isNotNull, $size, $defaultValue, true, null, null);
  }

  /**
   * Add a foreign key column to the table.
   *
   * @param      string $columnName A String with the column name.
   * @param      string $type A string specifying the Propel type.
   * @param      string $fkTable A String with the foreign key table name.
   * @param      string $fkColumn A String with the foreign key column name.
   * @param      boolean $isNotNull Whether column does not allow NULL values.
   * @param      int $size An int specifying the size.
   * @param      string $defaultValue The default value for this column.
   * @return     ColumnMap Newly added ForeignKey column.
   */
  public function addForeignKey($columnName, $phpName, $type, $fkTable, $fkColumn, $isNotNull = false, $size = 0, $defaultValue = null)
  {
    return $this->addColumn($columnName, $phpName, $type, $isNotNull, $size, $defaultValue, false, $fkTable, $fkColumn);
  }

  /**
   * Add a foreign primary key column to the table.
   *
   * @param      string $columnName A String with the column name.
   * @param      string $type A string specifying the Propel type.
   * @param      string $fkTable A String with the foreign key table name.
   * @param      string $fkColumn A String with the foreign key column name.
   * @param      boolean $isNotNull Whether column does not allow NULL values.
   * @param      int $size An int specifying the size.
   * @param      string $defaultValue The default value for this column.
   * @return     ColumnMap Newly created foreign pkey column.
   */
  public function addForeignPrimaryKey($columnName, $phpName, $type, $fkTable, $fkColumn, $isNotNull = false, $size = 0, $defaultValue = null)
  {
    return $this->addColumn($columnName, $phpName, $type, $isNotNull, $size, $defaultValue, true, $fkTable, $fkColumn);
  }
  
  /**
   * Returns array of ColumnMap objects that make up the primary key for this table
   *
   * @return     array ColumnMap[]
   */
  public function getPrimaryKeys()
  {
    return $this->primaryKeys;
  }
  
  /**
   * Returns array of ColumnMap objects that are foreign keys for this table
   *
   * @return     array ColumnMap[]
   */
  public function getForeignKeys()
  {
    return $this->foreignKeys;
  }

  /**
  * Add a validator to a table's column
  *
  * @param      string $columnName The name of the validator's column
  * @param      string $name The rule name of this validator
  * @param      string $classname The dot-path name of class to use (e.g. myapp.propel.MyValidator)
  * @param      string $value
  * @param      string $message The error message which is returned on invalid values
  * @return     void
  */
  public function addValidator($columnName, $name, $classname, $value, $message)
  {
    if (false !== ($pos = strpos($columnName, '.'))) {
      $columnName = substr($columnName, $pos + 1);
    }

    $col = $this->getColumn($columnName);
    if ($col !== null) {
      $validator = new ValidatorMap($col);
      $validator->setName($name);
      $validator->setClass($classname);
      $validator->setValue($value);
      $validator->setMessage($message);
      $col->addValidator($validator);
    }
  }
  
  /**
   * Build relations
   * Relations are lazy loaded for performance reasons
   * This method should be overridden by descendents
   */
  public function buildRelations()
  {
  }  
  
  /**
   * Adds a RelationMap to the table
   * 
   * @param      string $name The relation name
   * @param      string $tablePhpName The related table name
   * @param      integer $type The relation type (either RelationMap::MANY_TO_ONE, RelationMap::ONE_TO_MANY, or RelationMAp::ONE_TO_ONE) 
   * @param      array $columnMapping An associative array mapping column names (local => foreign)
   * @return     RelationMap the built RelationMap object
   */
  public function addRelation($name, $tablePhpName, $type, $columnMapping = [], $onDelete = null, $onUpdate = null)
  {
    // note: using phpName for the second table allows the use of DatabaseMap::getTableByPhpName()
    // and this method autoloads the TableMap if the table isn't loaded yet
    $relation = new RelationMap($name);
    $relation->setType($type);
    $relation->setOnUpdate($onUpdate);
    $relation->setOnDelete($onDelete);
    // set tables
    if ($type == RelationMap::MANY_TO_ONE) {
      $relation->setLocalTable($this);
      $relation->setForeignTable($this->dbMap->getTableByPhpName($tablePhpName));
    } else {
      $relation->setLocalTable($this->dbMap->getTableByPhpName($tablePhpName));
      $relation->setForeignTable($this);
      $columnMapping  = array_flip($columnMapping);
    }
    // set columns
    foreach ($columnMapping as $key => $value)
    {
      $relation->addColumnMapping(
        $relation->getLocalTable()->getColumn($key),
        $relation->getForeignTable()->getColumn($value)
      );
    }
    $this->relations[$name] = $relation;
    return $relation;
  }

  /**
   * Gets a RelationMap of the table by relation name
   *
   * @param       String $name The relation name 
   * @return      boolean true if the relation exists
   */
  public function hasRelation($name)
  {
    return array_key_exists($name, $this->getRelations());
  }
  
  /**
   * Gets a RelationMap of the table by relation name
   *
   * @param       String $name The relation name 
   * @return      RelationMap The relation object
   * @throws      PropelException When called on an inexistent relation
   */
  public function getRelation($name)
  {
    if (!array_key_exists($name, $this->getRelations()))
    {
      throw new PropelException('Calling getRelation() on an unknown relation, ' . $name);
    }
    return $this->relations[$name];
  }

  /**
   * Gets the RelationMap objects of the table
   * This method will build the relations if they are not built yet
   * 
   * @return      Array list of RelationMap objects
   */
  public function getRelations()
  {
    if(!$this->relationsBuilt)
    {
      $this->buildRelations();
      $this->relationsBuilt = true;
    }
    return $this->relations;
  }

  /**
   * 
   * Gets the list of behaviors registered for this table
   *
   * @return array
   */
  public function getBehaviors()
  {
    return [];
  }

  // Deprecated methods and attributres, to be removed
  
  /**
   * Does this table contain the specified column?
   *
   * @deprecated Use hasColumn instead
   * @param      mixed   $name name of the column or ColumnMap instance
   * @param      boolean $normalize Normalize the column name (if column name not like FIRST_NAME)
   * @return     boolean True if the table contains the column.
   */
  public function containsColumn(mixed $name, $normalize = true)
  {
    return $this->hasColumn($name, $normalize);
  }
  
  /**
   * Normalizes the column name, removing table prefix and uppercasing.
   * article.first_name becomes FIRST_NAME
   *
   * @deprecated Use ColumnMap::normalizeColumName() instead
   * @param      string $name
   * @return     string Normalized column name.
   */
  protected function normalizeColName($name)
  {
    return ColumnMap::normalizeName($name);
  }
  
  /**
   * Returns array of ColumnMap objects that make up the primary key for this table.
   *
   * @deprecated Use getPrimaryKeys instead
   * @return     array ColumnMap[]
   */
  public function getPrimaryKeyColumns()
  {
    return array_values($this->primaryKeys);
  }
    
  //---Utility methods for doing intelligent lookup of table names

  /** 
   * The prefix on the table name. 
   * @deprecated Not used anywhere in Propel
   */
  private $prefix;

  /**
   * Get table prefix name.
   *
   * @deprecated Not used anywhere in Propel
   * @return     string A String with the prefix.
   */
  public function getPrefix()
  {
    return $this->prefix;
  }

  /**
   * Set table prefix name.
   *
   * @deprecated Not used anywhere in Propel
   * @param      string $prefix The prefix for the table name (ie: SCARAB for
   * SCARAB_PROJECT).
   * @return     void
   */
  public function setPrefix($prefix)
  {
    $this->prefix = $prefix;
  }
  
  /**
   * Tell me if i have PREFIX in my string.
   *
   * @deprecated Not used anywhere in Propel
   * @param      data A String.
   * @return     boolean True if prefix is contained in data.
   */
  protected function hasPrefix($data)
  {
    return (str_starts_with((string) $data, (string) $this->prefix));
  }

  /**
   * Removes the PREFIX if found
   *
   * @deprecated Not used anywhere in Propel
   * @param      string $data A String.
   * @return     string A String with data, but with prefix removed.
   */
  protected function removePrefix($data)
  {
    return $this->hasPrefix($data) ? substr($data, strlen((string) $this->prefix)) : $data;
  }

  /**
   * Removes the PREFIX, removes the underscores and makes
   * first letter caps.
   *
   * SCARAB_FOO_BAR becomes FooBar.
   *
   * @deprecated Not used anywhere in Propel. At buildtime, use Column::generatePhpName() for that purpose
   * @param      data A String.
   * @return     string A String with data processed.
   */
  public final function removeUnderScores($data)
  {
    $out = '';
    $tmp = $this->removePrefix($data);
    $tok = strtok($tmp, '_');
    while ($tok) {
      $out .= ucfirst($tok);
      $tok = strtok('_');
    }
    return $out;
  }

  /**
   * Makes the first letter caps and the rest lowercase.
   *
   * @deprecated Not used anywhere in Propel.
   * @param      string $data A String.
   * @return     string A String with data processed.
   */
  private function firstLetterCaps($data)
  {
    return(ucfirst(strtolower($data)));
  }
}
