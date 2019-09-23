<?php
/**
 * @package    Subusers
 *
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (C) 2009 - 2018 Techjoomla. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;

HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers/html');
HTMLHelper::_('behavior.tooltip');
HTMLHelper::_('behavior.formvalidation');
HTMLHelper::_('formbehavior.chosen', 'select');
HTMLHelper::_('behavior.keepalive');

Factory::getDocument()->addScriptDeclaration(
		'
	Joomla.submitbutton = function(task)
	{
		if (task == "mapping.cancel" || document.formvalidator.isValid(document.getElementById("mapping-form")))
		{
			Joomla.submitform(task, document.getElementById("mapping-form"));
		}
	};
');
?>
<form
	action="<?php echo Route::_('index.php?option=com_subusers&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" enctype="multipart/form-data" name="adminForm"
	id="mapping-form" class="form-validate">
	<div class="form-horizontal">
		<div class="row-fluid">
			<div class="span9">
				<?php echo $this->form->renderField('id'); ?>
				<?php echo $this->form->renderField('role_id');?>
				<?php echo $this->form->renderField('action_id');?>
			</div>
		</div>
		<input type="hidden" name="task" value="" />
		<?php echo HTMLHelper::_('form.token'); ?>
	</div>
</form>
