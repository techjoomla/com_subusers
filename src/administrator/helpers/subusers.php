<?php
/**
 * @package    Subusers
 *
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (C) 2009 - 2018 Techjoomla. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die();

/**
 * Subusers helper.
 *
 * @since  1.0.0
 */
class SubusersHelper
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  string
	 *
	 * @return void
	 */
	public static function addSubmenu($vName = '')
	{
		JHtmlSidebar::addEntry(JText::_('COM_SUBUSERS_TITLE_ROLES'), 'index.php?option=com_subusers&view=roles', $vName == 'roles');
		JHtmlSidebar::addEntry(JText::_('COM_SUBUSERS_TITLE_ACTIONS'), 'index.php?option=com_subusers&view=actions', $vName == 'actions');
		JHtmlSidebar::addEntry(JText::_('COM_SUBUSERS_TITLE_MAPPINGS'), 'index.php?option=com_subusers&view=mappings', $vName == 'mappings');
		JHtmlSidebar::addEntry(JText::_('COM_SUBUSERS_TITLE_USERS'), 'index.php?option=com_subusers&view=users', $vName == 'users');
	}
}
