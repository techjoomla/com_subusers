<?php
/**
 * @package    Subusers
 *
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (C) 2009 - 2018 Techjoomla. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Unauthorized Access');

use Joomla\CMS\Factory;
use Joomla\CMS\Object\CMSObject;

/**
 * Action class.  Handles all application interaction with a Action
 *
 * @since  __DEPLOY_VERSION__
 */
class SubusersAction extends CMSObject
{
	public $id = 0;

	public $name = "";

	public $code = "";

	public $client = "";

	public $created_date = 0;

	protected static $actionObj = array();

	/**
	 * Constructor activating the default information of the Action
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
	}

	/**
	 * Returns the global Action object
	 *
	 * @param   integer  $id  The primary key of the Action to load (optional).
	 *
	 * @return  SubusersAction  The Action object.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getInstance($id = 0)
	{
		if (!$id)
		{
			return new SubusersAction;
		}

		if (empty(self::$actionObj[$id]))
		{
			$action = new SubusersAction($id);
			self::$actionObj[$id] = $action;
		}

		return self::$actionObj[$id];
	}

	/**
	 * Method to load a action object by action id
	 *
	 * @param   int  $id  The Action id
	 *
	 * @return  boolean  True on success
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public function load($id)
	{
		$table = RBACL::table("action");

		if (!$table->load($id))
		{
			return false;
		}

		$this->setProperties($table->getProperties());

		return true;
	}

	/**
	 * Method to save the action object to the database
	 *
	 * @return  boolean  True on success
	 *
	 * @since   __DEPLOY_VERSION__
	 * @throws  \RuntimeException
	 */
	public function save()
	{
		// Create the widget table object
		$table = RBACL::table("action");
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
	 * Method to bind an associative array of data to a Action object
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
			$this->setError(JText::_('COM_SUBUSER_EMPTY_DATA'));

			return false;
		}

		// Bind the array
		if (!$this->setProperties($array))
		{
			$this->setError(\JText::_('COM_SUBUSER_BINDING_ERROR'));

			return false;
		}

		// Make sure its an integer
		$this->id = (int) $this->id;

		return true;
	}

	/**
	 * Method to create instance of action based on the code and client
	 *
	 * @param   string  $code    The unique code of the action
	 * @param   string  $client  action client
	 *
	 * @return  SubusersAction  The Action object.
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public static function loadActionByCode(String $code, String $client)
	{
		$table = RBACL::table("action");
		$table->load(array("code" => $code, "client" => $client));

		return self::getInstance($table->id);
	}

	/**
	 * This method will return the array of authorized roles that are allowed to perform current action
	 *
	 * @return  array  The roles array.
	 *
	 * @since __DEPLOY_VERSION__
	 */
	public function getAuthorizedRoles()
	{
		$model = RBACL::model("action");

		return $model->getAssignedRoles($this->id);
	}
}
