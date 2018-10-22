<?php
/**
 * @package    Subusers
 *
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (C) 2009 - 2018 Techjoomla. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die();

use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Factory;

JLoader::discover("Subusers", JPATH_ADMINISTRATOR . '/components/com_subusers/libraries');

/**
 * Subusers factory class.
 *
 * This class perform the helpful operation for subuser
 *
 * @since  __DEPLOY_VERSION__
 */
class RBACL
{
	/**
	 * Array of loaded user roles
	 *
	 * @var    array
	 * @since  __DEPLOY_VERSION__
	 */
	protected static $roles = array();

	/**
	 * Retrieves a table from the table folder
	 *
	 * @param   string  $name  The table file name
	 *
	 * @return  \Joomla\CMS\Table\Table object
	 *
	 * @since   __DEPLOY_VERSION__
	 **/
	public static function table($name)
	{
		// @TODO Improve file loading with specific table file.

		Table::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_subusers/tables');

		// @TODO Add support for cache
		return Table::getInstance($name, 'SubusersTable');
	}

	/**
	 * Retrieves a model from the model folder
	 *
	 * @param   string  $name    The model name to instantiate
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  \Joomla\CMS\MVC\Model\BaseDatabaseModel object
	 *
	 * @since   __DEPLOY_VERSION__
	 **/
	public static function model($name, $config = array())
	{
		BaseDatabaseModel::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_subusers/models');

		// @TODO Add support for cache
		return BaseDatabaseModel::getInstance($name, 'SubusersModel', $config);
	}

	/**
	 * Method to check if a user is authorised to perform an action, optionally on an content.
	 *
	 * @param   integer  $userId     Id of the user for which to check authorisation.
	 * @param   string   $client     The name of the client to authorise. com_content
	 * @param   string   $action     The name of the action to authorise. Eg. core.edit
	 * @param   integer  $contentId  The content key. null check with role and allowed actions.
	 *
	 * @return  boolean         True if allowed, false for an explicit deny, null for an implicit deny.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function check($userId, $client, $action, $contentId = null)
	{
		$action = strtolower(preg_replace('#[\s\-]+#', '.', trim($action)));

		$user = Factory::getUser($userId);

		if ($user->id)
		{
			/*
			 * Step 1. Check the action is exist
			 */
			$actionObj = SubusersAction::loadActionByCode($action, $client);

			if ($actionObj->id)
			{
				/*
				 * Step 2. check allowed roles for the action
				 * It is may be more than one
				 */
				$authorizedRoles = $actionObj->getAuthorizedRoles();

				/*
				 * Step 3. Load user assigned roles
				 * It is may be more than one
				 */
				if (!isset(self::$roles[$client][$user->id]))
				{
					self::$roles[$client][$userId] = self::getRoleByUser($user->id, $client);
				}

				/*
				 * Step 4. Is user have right authority to perform this action
				 */
				$allowedRoles = array_intersect($authorizedRoles, self::$roles[$client][$userId]);

				if (!empty($allowedRoles))
				{
					/*
					 * Step 5. If the content id is provided and check for the associated role to it
					 */
					if (empty($contentId))
					{
						return true;
					}

					$userModel = self::model("user");
					$contentRoleId = $userModel->getAssociatedContentRole($userId, $client, $contentId);

					$rolesAllowed = array_intersect($contentRoleId, $allowedRoles);

					if (!empty($rolesAllowed))
					{
						return true;
					}
				}
			}
		}

		return false;
	}

	/**
	 * This method will check the core Joomla authorisatoion and RBACL authorisation
	 *
	 * @param   integer  $userId     Id of the user for which to check authorisation.
	 * @param   string   $client     The name of the client to authorise. com_content
	 * @param   string   $action     The name of the action to authorise. Eg. core.edit
	 * @param   integer  $contentId  The content key. null check with role and allowed actions.
	 *
	 * @return  boolean  True if authorised
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function authorise($userId, $client, $action, $contentId = null)
	{
		$user = Factory::getUser($userId);
		$result = $user->authorise($action, $client);

		return $result && self::check($userId, $client, $action);
	}

	/**
	 * Get user roles by user id and client id
	 *
	 * @param   integer  $userId            userId
	 * @param   string   $client            client for role
	 * @param   integer  $clientContentIid  content id
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getRoleByUser($userId, $client = '', $clientContentIid = 0)
	{
		$roles = array();

		if ($userId)
		{
			$db = Factory::getDbo();
			$query = $db->getQuery(true);
			$query->select('DISTINCT role_id');
			$query->from($db->quoteName('#__tjsu_users'));
			$query->where($db->quoteName('user_id') . " = " . $db->quote($userId));

			if (!empty($client))
			{
				$query->where($db->quoteName('client') . " = " . $db->quote($client));
			}

			if (!empty($clientContentIid))
			{
				$query->where($db->quoteName('client_id') . " = " . $db->quote($clientContentIid));
			}

			$db->setQuery($query);
			$roles = $db->loadColumn();
		}

		return $roles;
	}

	/**
	 * Method to Get roles of users against to selected client.
	 *
	 * @param   integer  $contentId  content id
	 * @param   integer  $userId     user id
	 *
	 * @return 	array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getAuthorizedActions($contentId = null, $userId = null)
	{
		if (is_null($contentId))
		{
			$input = JFactory::getApplication()->input;
			$contentId = $input->get('aid', '0', 'INT');
		}

		if (is_null($userId))
		{
			$userId = JFactory::getUser()->id;
		}

		// Get subusers actions mapp
		$userRoleId = self::getRoleByUser($userId, 'com_multiagency', 0);

		if (empty($userRoleId))
		{
			$userRoleId = self::getRoleByUser($userId, 'com_multiagency', $contentId);
		}

		if (!empty($userRoleId))
		{
			$db = JFactory::getDBO();

			// Get actions mapped to roles.
			$subInQuery = $db->getQuery(true);
			$subInQuery->select('action_id')
			->from($db->quoteName('#__tjsu_role_action_map'))
			->where($db->quoteName('role_id') . 'IN(' . implode(',', $userRoleId) . ')');
			$db->setQuery($subInQuery);

			$roleActions = $db->loadColumn();

			if ($roleActions && !empty($contentId))
			{
				/* Get the roles again to cotent id.
				 * e.g. One content is Agency and agency having multiple roles manager, staff, employee
				 * One user having two different roles for two different agency. then If I pass then agency id then query give us mapped actions agains to agency.
				 */
				$query = $db->getQuery(true);
				$query->select('m.role_id,r.name, count( m.action_id) as actionCount, (select count(aa.action_id)
				FROM #__tjsu_role_action_map aa WHERE aa.role_id = m.role_id) as roleCount');
				$query->from($db->quoteName('#__tjsu_role_action_map', 'm'));
				$query->join('INNER', $db->quoteName('#__tjsu_actions', 'a') . ' ON (' . $db->quoteName('a.id') . ' = ' . $db->quoteName('m.action_id') . ')');
				$query->join('INNER', $db->quoteName('#__tjsu_roles', 'r') . ' ON (' . $db->quoteName('r.id') . ' = ' . $db->quoteName('m.role_id') . ')');
				$query->where($db->quoteName('m.action_id') . ' IN (' . implode(',', $roleActions) . ')');
				$query->group($db->quoteName('m.role_id'));
				$query->having('roleCount <= actionCount');
				$db->setQuery($query);

				return $db->loadAssocList();
			}
		}
	}
}
