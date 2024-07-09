<?php

/**
 * Subclass for representing a row from the 'category' table.
 *
 *
 *
 * @package lib.model
 */
class Category extends BaseCategory implements \Stringable
{
	/**
	 * Initializes internal state of Category object.
	 * @see        parent::__construct()
	 */
	public function __construct()
	{
		// Make sure that parent constructor is always invoked, since that
		// is where any default values for this object are set.
		parent::__construct();
	}

  public function __toString(): string
  {
    return $this->getName();
  }
}
