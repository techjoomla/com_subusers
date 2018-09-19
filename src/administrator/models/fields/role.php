<?php
/**
 * @package    Subusers
 *
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (C) 2009 - 2018 Techjoomla. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die();
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

JFormHelper::loadFieldClass('list');

/**
 * Supports an HTML select list of Roles
 *
 * @since  __DEPLOY_VERSION__
 */
class JFormFieldRole extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since   __DEPLOY_VERSION__
	 */
	protected $type = 'role';

	/**
	 * Method to get list of role options.
	 *
	 * @return   array role An array of HTMLHelper options
	 *
	 * @since    __DEPLOY_VERSION__
	 */
	protected function getOptions()
	{
		$jinput = Factory::getApplication()->input;
		$client = $jinput->get('client', '', 'STRING');

		$roleModel = RBACL::model("roles", array("ignore_request" => true));
		$roleModel->setState('filter.client', $client);
		$results = $roleModel->getItems();

		$options = array();
		$options[] = HTMLHelper::_('select.option', '', Text::_('COM_SUBUSERS_FORM_LBL_SELECT_ROLE'));

		if ($results)
		{
			foreach ($results as $result)
			{
				$options[] = HTMLHelper::_('select.option', $result->id, $result->name);
			}
		}

		return $options;
	}
}
