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
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;
/**
 * View to edit
 *
 * @since  1.0.0
 */
class SubusersViewMapping extends HtmlView
{
	/**
	 * The JForm object
	 *
	 * @var  \Joomla\CMS\Form\Form
	 */
	protected $form;

	/**
	 * The active item
	 *
	 * @var  object
	 */
	protected $item;

	/**
	 * The model state
	 *
	 * @var  object
	 */
	protected $state;

	/**
	 * The actions the user is authorised to perform
	 *
	 * @var  JObject
	 */
	protected $canDo;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function display($tpl = null)
	{
		$this->state = $this->get('State');
		$this->item  = $this->get('Item');
		$this->form  = $this->get('Form');

		$this->canDo = ContentHelper::getActions('com_subusers', 'mapping', $this->item->id);

		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}

		$this->addToolbar();

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	protected function addToolbar()
	{
		$user       = Factory::getUser();
		$isNew      = ($this->item->id == 0);

		$canDo = $this->canDo;
		$layout = Factory::getApplication()->input->get("layout");

		$this->sidebar = JHtmlSidebar::render();
		JToolBarHelper::title(Text::_('COM_SUBUSERS_TITLE_MAPPING'), 'mapping.png');

		// For new records, check the create permission.
		if ($layout != "default")
		{
			Factory::getApplication()->input->set('hidemainmenu', true);

			if ($isNew)
			{
				JToolbarHelper::save('mapping.save');
				JToolbarHelper::save2new('mapping.save2new');
				JToolbarHelper::cancel('mapping.cancel');
			}
			else
			{
				if ($this->isEditable($canDo, $user->id))
				{
					JToolbarHelper::save('mapping.save');
				}

				JToolbarHelper::cancel('mapping.cancel', 'JTOOLBAR_CLOSE');
			}
		}

		JToolbarHelper::divider();
	}

	/**
	 * Is editable
	 *
	 * @param   Object   $canDo   Checked Out
	 *
	 * @param   integer  $userId  User ID
	 *
	 * @return boolean
	 */
	protected function isEditable($canDo, $userId)
	{
		return $canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by == $userId);
	}
}
