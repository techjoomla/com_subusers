<?php
/**
 * @package    Subusers
 *
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (C) 2009 - 2018 Techjoomla. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers/html');
HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('behavior.multiselect');
HTMLHelper::_('formbehavior.chosen', 'select');

$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');
?>

<div class="tj-page">
	<div class="row-fluid">
		<form action="<?php echo Route::_('index.php?option=com_subusers&view=actions'); ?>" method="post" name="adminForm" id="adminForm">
			<?php
			if (!empty($this->sidebar))
			{
			?>
				<div id="j-sidebar-container" class="span2">
					<?php echo $this->sidebar; ?>
				</div>
				<div id="j-main-container" class="span10">
			<?php
			}
			else
			{
				?>
				<div id="j-main-container">
			<?php
			}

			// Search tools bar
			echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this));

			if (empty($this->items))
			{
			?>
				<div class="alert alert-no-items">
					<?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
				</div>
			<?php
			}
			else
			{
				?>
				<table class="table table-striped" id="actionsList">
					<thead>
						<tr>
							<th width="1%" class="nowrap center hidden-phone"></th>
							<th width="1%" class="center">
								<?php echo HTMLHelper::_('grid.checkall'); ?>
							</th>
							<th>
								<?php echo HTMLHelper::_('searchtools.sort', 'COM_SUBUSERS_ACTIONS_NAME', 'a.name', $listDirn, $listOrder); ?>
							</th>
							<th>
								<?php echo HTMLHelper::_('searchtools.sort', 'COM_SUBUSERS_FORM_LBL_ACTION_CODE', 'a.code', $listDirn, $listOrder); ?>
							</th>
							<th>
								<?php echo HTMLHelper::_('searchtools.sort', 'COM_SUBUSERS_ACTIONS_CLIENT', 'a.client', $listDirn, $listOrder); ?>
							</th>
							<th width="1%" class="nowrap center hidden-phone">
								<?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
							</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<td colspan="10">
								<?php echo $this->pagination->getListFooter(); ?>
							</td>
						</tr>
					</tfoot>
					<tbody>
						<?php
						foreach ($this->items as $i => $item)
						{
							$canEdit    = $this->canDo->get('core.edit');
							$canEditOwn = $this->canDo->get('core.edit.own');
							?>
							<tr class="row <?php echo $i % 2; ?>"
							sortable-group-id="<?php echo $item->id; ?>">
								<td class="center">
									<?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
								</td>
								<td class="has-context">
									<div class="pull-left break-word">
										<?php
										if ($canEdit || $canEditOwn)
										{
											?>
											<a class="hasTooltip" href="
											<?php echo Route::_('index.php?option=com_subusers&task=action.edit&id=' . $item->id); ?>" title="
											<?php echo Text::_('JACTION_EDIT'); ?>">
											<?php echo $this->escape($item->name); ?></a>
											<?php
										}
										else
										{
											?>
											<span title="<?php echo Text::sprintf('JFIELD_ALIAS_LABEL', $this->escape($item->name)); ?>">
											<?php echo $this->escape($item->name); ?></span>
										<?php
										}?>

									</div>
								</td>
								<td><?php echo $this->escape($item->code); ?></td>
								<td><?php echo $this->escape($item->client); ?></td>
								<td><?php echo (int) $item->id; ?></td>
							</tr>
						<?php
						}
						?>
					<tbody>
				</table>
				<?php
			}
			?>
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
			<?php echo HTMLHelper::_('form.token'); ?>
			</div>
		</form>
	</div>
</div>
