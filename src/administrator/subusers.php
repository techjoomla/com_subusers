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
use Joomla\CMS\MVC\Controller\BaseController;

// Access check.
if (!Factory::getUser()->authorise('core.manage', 'com_subusers'))
{
	throw new \Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

\JLoader::import("/components/com_subusers/includes/rbacl", JPATH_ADMINISTRATOR);

$controller = BaseController::getInstance('Subusers');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
