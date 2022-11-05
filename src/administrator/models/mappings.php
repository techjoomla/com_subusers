<?php
/**
 * @package     Subusers
 * @subpackage  com_subusers
 *
 * @author      Techjoomla <extensions@techjoomla.com>
 * @copyright   Copyright (C) 2009 - 2022 Techjoomla. All rights reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\MVC\Model\ListModel;

/**
 * Methods supporting a list of Subusers records.
 *
 * @since  1.0.0
 */
class SubusersModelMappings extends ListModel
{
/**
	* Constructor.
	*
	* @param   array  $config  An optional associative array of configuration settings.
	*
	* @see        JController
	* @since      1.0.0
	*/
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.`id`',
				'b.name',
				'c.code',
				'b.client',
				'c.name',
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
	protected function populateState($ordering = 'a.id', $direction = 'desc')
	{
		$app = Factory::getApplication('administrator');

		// Load the filter state.
		$search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$params = ComponentHelper::getParams('com_subusers');
		$this->setState('params', $params);

		parent::populateState($ordering, $direction);
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
		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		$query->select(array('a.*', 'b.name as role_name', 'b.client as role_client', 'c.name as action_name', 'c.code as action_code'));
		$query->from('`#__tjsu_role_action_map` AS a');
		$query->join('INNER', $db->quoteName('#__tjsu_roles', 'b') . ' ON (' . $db->quoteName('a.role_id') . ' = ' . $db->quoteName('b.id') . ')');
		$query->join('INNER', $db->quoteName('#__tjsu_actions', 'c') . ' ON (' . $db->quoteName('a.action_id') . ' = ' . $db->quoteName('c.id') . ')');

		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%' . $db->escape($search, true) . '%');
				$searches   = array();
				$searches[] = 'b.name LIKE ' . $search;
				$searches[] = 'c.name LIKE ' . $search;
				$searches[] = 'c.code LIKE ' . $search;

				$query->where('(' . implode(' OR ', $searches) . ')');
			}
		}

		$orderCol  = $this->state->get('list.ordering', 'a.id');
		$orderDirn = $this->state->get('list.direction', 'DESC');
		$query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));

		return $query;
	}
}
