<?php
/**
 * @package     Subusers
 * @subpackage  com_subusers
 *
 * @author      Techjoomla <extensions@techjoomla.com>
 * @copyright   Copyright (C) 2009 - 2022 Techjoomla. All rights reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die();

use Joomla\CMS\MVC\Controller\AdminController;

/**
 * Actions list controller class.
 *
 * @since  1.0.0
 */
class SubusersControllerActions extends AdminController
{
	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    Optional. Model name
	 * @param   string  $prefix  Optional. Class prefix
	 * @param   array   $config  Optional. Configuration array for model
	 *
	 * @return  \Joomla\CMS\MVC\Model\BaseDatabaseModel|boolean  Model object on success; otherwise false on failure.
	 *
	 * @since    1.0.0
	 */
	public function getModel($name = 'action', $prefix = 'SubusersModel', $config = array())
	{
		return parent::getModel($name, $prefix, array('ignore_request' => true));
	}
}
