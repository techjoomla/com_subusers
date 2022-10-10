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

require_once JPATH_ADMINISTRATOR . '/components/com_installer/models/database.php';

/**
 * Jlike Manage Model
 *
 * @since  1.6
 */
class SubusersModelDatabase extends InstallerModelDatabase
{
	/**
	 * Gets the changeset object.
	 *
	 * @return  JSchemaChangeset
	 */
	public function getItems()
	{
		$folder = JPATH_ADMINISTRATOR . '/components/com_subusers/sql/updates/';

		try
		{
			$changeSet = JSchemaChangeset::getInstance($this->getDbo(), $folder);
		}
		catch (RuntimeException $e)
		{
			JFactory::getApplication()->enqueueMessage($e->getMessage(), 'warning');

			return false;
		}

		return $changeSet;
	}
}
