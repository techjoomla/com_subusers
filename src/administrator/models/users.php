<?php
/**
 * @package     Subusers
 * @subpackage  com_subusers
 *
 * @author      Techjoomla <extensions@techjoomla.com>
 * @copyright   Copyright (C) 2009 - 2019 Techjoomla. All rights reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ListModel;

/**
 * Methods supporting a list of Subusers records.
 *
 * @since  1.0.0
 */
class SubusersModelUsers extends ListModel
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @since      1.0.0
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'user_id', 'a.user_id',
				'role_id', 'a.role_id',
				'client', 'a.client',
				'client_id', 'a.client_id',
				'created_by', 'a.created_by',
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   Elements order
	 * @param   string  $direction  Order direction
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication('administrator');

		$search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$this->setState('filter.user_id', $app->getUserStateFromRequest($this->context . ' . filter.user_id', 'filter_user_id', '', 'string'));

		$params = JComponentHelper::getParams('com_subusers');
		$this->setState('params', $params);

		parent::populateState('a.id', 'desc');
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return   string A store id.
	 *
	 * @since    1.0.0
	 */
	protected function getStoreId($id = '')
	{
		$id .= ':' . $this->getState('filter.search');

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return   JDatabaseQuery
	 *
	 * @since    1.0.0
	 */
	protected function getListQuery()
	{
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select(array('a.*', 'uc.name', 'rl.name as rolename'));
		$query->from('`#__tjsu_users` AS a');
		$query->join('INNER', $db->quoteName('#__users', 'uc') . ' ON (' . $db->quoteName('a.user_id') . ' = ' . $db->quoteName('uc.id') . ')');
		$query->join('INNER', $db->quoteName('#__tjsu_roles', 'rl') . ' ON (' . $db->quoteName('rl.id') . ' = ' . $db->quoteName('a.role_id') . ')');
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%' . $db->escape(trim($search), true) . '%');
				$query->where('( uc.`name` LIKE ' . $search . '  OR  a.`user_id` LIKE ' . $search . '  OR  a.`client_id` LIKE ' . $search . ' )');
			}
		}

		$roleId = $this->getState('filter.role_id');

		if (!empty($roleId))
		{
			$query->where($db->quoteName('a.role_id') . " = " . (int) $roleId);
		}

		$client = $this->getState('filter.client');

		if (!empty($client))
		{
			$query->where($db->quoteName('a.client') . " = " . $db->quote($client));
		}

		$clientId = $this->getState('filter.client_id');

		if (!empty($clientId))
		{
			$query->where($db->quoteName('a.client_id') . " = " . (int) $clientId);
		}

		$orderCol  = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');

		if ($orderCol && $orderDirn)
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}

		return $query;
	}
}
