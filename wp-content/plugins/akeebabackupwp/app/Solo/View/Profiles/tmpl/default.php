<?php
/**
 * @package    solo
 * @copyright  Copyright (c)2014-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license    GNU GPL version 3 or later
 */

use Awf\Text\Text;

// Used for type hinting
/** @var \Solo\View\Profiles\Html $this */

$router = $this->container->router;
$configURL = base64_encode($router->route('index.php?view=configuration'));
$token = $this->container->session->getCsrfToken()->getValue();

/** @var \Solo\Model\Profiles $model */
$model = $this->getModel();
?>

<?php echo $this->loadAnyTemplate('Main/paypal'); ?>

<div class="akeeba-block--info">
	<strong><?php echo Text::_('COM_AKEEBA_CPANEL_PROFILE_TITLE'); ?></strong>:
	#<?php echo $this->profileid; ?> <?php echo $this->profilename; ?>
</div>

<form action="<?php echo $router->route('index.php?view=profiles')?>" method="post" name="adminForm" id="adminForm"
      class="akeeba-form--with-hidden" role="form">

	<table class="akeeba-table--striped" id="adminList">
		<thead>
			<tr>
				<th width="30">
					&nbsp;
				</th>
				<th width="50">
					<?php echo \Awf\Html\Grid::sort('#', 'id', $this->lists->order_Dir, $this->lists->order, 'browse'); ?>
				</th>
				<th width="20%">
				</th>
				<th>
					<?php echo \Awf\Html\Grid::sort('COM_AKEEBA_PROFILES_COLLABEL_DESCRIPTION', 'description', $this->lists->order_Dir, $this->lists->order, 'browse'); ?>
				</th>
				<th>
					<?php echo Text::_('COM_AKEEBA_CONFIG_QUICKICON_LABEL'); ?>
				</th>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td>
					<input type="text" name="description" value="<?php echo $model->getState('description', '');?>"
						   placeholder="<?php echo Text::_('COM_AKEEBA_PROFILES_COLLABEL_DESCRIPTION')?>"
						   onchange="forms.adminForm.submit();" style="width: 100%;" />
				</td>
                <td></td>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="20" class="center"><?php echo $this->pagination->getListFooter(); ?></td>
			</tr>
		</tfoot>
		<tbody>
		<?php if (!empty($this->items)): ?>
		<?php $i = 0;
			foreach ($this->items as $profile):?>
			<?php
			$check = \Awf\Html\Grid::id(++$i, $profile->id);
			$link = $router->route('index.php?&view=profiles&task=edit&id=' . $profile->id);
			$exportBaseName = \Awf\Utils\StringHandling::toSlug($profile->description);
			?>
		<tr>
			<td>
				<?php echo $check; ?>
			</td>
			<td>
				<?php echo $profile->id; ?>
			</td>
			<td>
				<button class="akeeba-btn--teal--small" onclick="window.location='<?php echo $router->route('index.php?view=main&task=switchProfile&profile=' . $profile->id . '&returnurl=' . $configURL . '&token=' . $token)?>'; return false;">
					<span class="akion-ios-cog"></span>
					<?php echo Text::_('COM_AKEEBA_CONFIG_UI_CONFIG'); ?>
				</button>
				&nbsp;
				<button class="akeeba-btn--dark--small" onclick="window.location='<?php echo $router->route('index.php?view=profiles&task=read&id=' . $profile->id . '&basename=' . urlencode($exportBaseName) . '&format=json&' . $token . '=1')?>'; return false;">
					<span class="akion-android-download"></span>
					<?php echo Text::_('COM_AKEEBA_PROFILES_BTN_EXPORT'); ?>
				</button>
			</td>
			<td>
				<a href="<?php echo $link; ?>">
					<?php echo $this->escape($profile->description); ?>
				</a>
			</td>
            <td>
                <?php $action = $profile->quickicon ? 'unpublish' : 'publish' ?>
                <a href="<?php echo $router->route("index.php?view=Profiles&task={$action}&id={$profile->id}&{$token}=1") ?>"
                   class="akeeba-btn--<?php echo $profile->quickicon ? 'green' : 'red' ?>"
                >
                    <span class="akion-<?php echo $profile->quickicon ? 'checkmark' : 'close-circled' ?>"
                </a>

            </td>
		</tr>
		<?php endforeach; ?>
		<?php else: ?>
		<tr>
			<td colspan="11">
				<?php echo Text::_('SOLO_LBL_NO_RECORDS') ?>
			</td>
		</tr>
		<?php endif; ?>
		</tbody>
	</table>

    <div class="akeeba-hidden-fields-container">
        <input type="hidden" name="boxchecked" id="boxchecked" value="0">
        <input type="hidden" name="task" id="task" value="browse">
        <input type="hidden" name="filter_order" id="filter_order" value="<?php echo $this->lists->order ?>">
        <input type="hidden" name="filter_order_Dir" id="filter_order_Dir" value="<?php echo $this->lists->order_Dir ?>">
        <input type="hidden" name="token" value="<?php echo $token ?>">
    </div>
</form>

<form action="<?php echo $router->route('index.php?view=profiles&task=import')?>" method="POST" name="importForm" enctype="multipart/form-data" id="importForm" class="akeeba-form--inline--with-hidden--no-margins akeeba-panel--info">
	<div class="akeeba-form-group">
        <input type="file" name="importfile" class="form-control" />
    </div>

    <div class="akeeba-form-group--actions">
        <button class="akeeba-btn--green">
            <span class="akion-upload"></span>
		    <?php echo Text::_('COM_AKEEBA_PROFILES_HEADER_IMPORT');?>
        </button>
        <span class="akeeba-help-text">
	    	<?php echo Text::_('COM_AKEEBA_PROFILES_LBL_IMPORT_HELP');?>
    	</span>
    </div>

    <div class="akeeba-hidden-fields-container">
        <input type="hidden" name="boxchecked" id="boxchecked" value="0" />
        <input type="hidden" name="task" id="task" value="import" />
        <input type="hidden" name="<?php echo $token ?>" value="1" />
    </div>

</form>

<p></p>

<script type="application/javascript">
	akeeba.System.orderTable = function ()
	{
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $this->escape($this->lists->order); ?>')
		{
			dirn = 'asc';
		}
		else
		{
			dirn = direction.options[direction.selectedIndex].value;
		}

		akeeba.System.tableOrdering(order, dirn, '');
	}
</script>
