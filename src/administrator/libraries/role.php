<?php
/**
 * @package     Subusers
 * @subpackage  com_subusers
 *
 * @author      Techjoomla <extensions@techjoomla.com>
 * @copyright   Copyright (C) 2009 - 2022 Techjoomla. All rights reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die('Unauthorized Access');

use Joomla\CMS\Factory;
use Joomla\CMS\Object\CMSObject;
use Joomla\CMS\Language\Text;

/**
 * Role class.  Handles all application interaction with a Role
 *
 * @since  __DEPLOY_VERSION__
 */
class SubusersRole extends CMSObject
{
	public $id = 0;

	public $name = "";

	public $client = "";

	public $created_date = "";

	public $modified_date = "";

	public $created_by = 0;

	public $modified_by = 0;

	public $ordering = "";

	protected static $roleObj = array();

	/**
	 * Constructor activating the default information of the Role
	 *
	 * @param   int  $id  The unique event key to load.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function __construct($id = 0)
	{
		if (!empty($id))
		{
			$this->load($id);
		}

		$db = Factory::getDbo();

		$this->modified_date = $this->created_date = $db->getNullDate();
	}

	/**
	 * Returns the global Role object
	 *
	 * @param   integer  $id  The primary key of the Role to load (optional).
	 *
	 * @return  SubusersRole  The Role object.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getInstance($id = 0)
	{
		if (!$id)
		{
			return new SubusersRole;
		}

		if (empty(self::$roleObj[$id]))
		{
			$role = new SubusersRole($id);
			self::$roleObj[$id] = $role;
		}

		return self::$roleObj[$id];
	}

	/**
	 * Method to load a Role object by Role id
	 *
	 * @param   int  $id  The Role id
	 *
	 * @return  boolean  True on success
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public function load($id)
	{
		$table = RBACL::table("role");

		if (!$table->load($id))
		{
			return false;
		}

		$this->setProperties($table->getProperties());

		return true;
	}

	/**
	 * Method to save the Role object to the database
	 *
	 * @return  boolean  True on success
	 *
	 * @since   __DEPLOY_VERSION__
	 * @throws  \RuntimeException
	 */
	public function save()
	{
		// Create the widget table object
		$table = RBACL::table("role");
		$table->bind($this->getProperties());

		$user = Factory::getUser();

		// Allow an exception to be thrown.
		try
		{
			// Check and store the object.
			if (!$table->check())
			{
				$this->setError($table->getError());

				return false;
			}

			// Check if new record
			$isNew = empty($this->id);

			// Store the user data in the database
			if (!($table->store()))
			{
				$this->setError($table->getError());

				return false;
			}
		}
		catch (\Exception $e)
		{
			$this->setError($e->getMessage());

			return false;
		}

		$this->id = $table->id;

		return true;
	}

	/**
	 * Method to bind an associative array of data to a Role object
	 *
	 * @param   array  &$array  The associative array to bind to the object
	 *
	 * @return  boolean  True on success
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public function bind(&$array)
	{
		if (empty($array))
		{
			$this->setError(Text::_('COM_CLUSTER_EMPTY_DATA'));

			return false;
		}

		// Bind the array
		if (!$this->setProperties($array))
		{
			$this->setError(Text::_('COM_CLUSTER_BINDING_ERROR'));

			return false;
		}

		// Make sure its an integer
		$this->id = (int) $this->id;

		return true;
	}
}
