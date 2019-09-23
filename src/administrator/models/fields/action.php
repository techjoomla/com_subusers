<?php
/**
 * @package     Subusers
 * @subpackage  com_subusers
 *
 * @author      Techjoomla <extensions@techjoomla.com>
 * @copyright   Copyright (C) 2009 - 2019 Techjoomla. All rights reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
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
class JFormFieldAction extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since   __DEPLOY_VERSION__
	 */
	protected $type = 'action';

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

		$rolesModel = RBACL::model("actions", array("ignore_request" => true));
		$rolesModel->setState('filter.client', $client);
		$results = $rolesModel->getItems();

		$options = array();
		$options[] = HTMLHelper::_('select.option', '', Text::_('COM_SUBUSERS_FORM_LBL_SELECT_ACTION'));

		if (!empty($results))
		{
			foreach ($results as $result)
			{
				$options[] = HTMLHelper::_('select.option', $result->id, $result->name);
			}
		}

		return $options;
	}
}
