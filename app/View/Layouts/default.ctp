<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
	?>
<!DOCTYPE html>
<html data-menu-color="brand" data-topbar-color="brand" data-sidenav-size="compact">

<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $cakeDescription ?>:
		<?php echo $this->fetch('title'); ?>
	</title>
	<?php
	echo $this->Html->meta('icon');
	echo $this->Html->css('../assets/libs/flatpickr/flatpickr.min');
	echo $this->Html->css('../assets/libs/selectize/css/selectize.bootstrap3');
	echo $this->Html->script('../assets/js/head');
	echo $this->Html->css('../assets/css/bootstrap.min', null, array('id' => 'app-style'));
	echo $this->Html->css('../assets/css/icons.min');
	echo $this->Html->css('../assets/libs/jsgrid/jsgrid.min');
	echo $this->Html->css('../assets/libs/jsgrid/jsgrid-theme.min');
	echo $this->Html->css('../assets/libs/select2/css/select2.min');
	echo $this->Html->css('../assets/libs/dropzone/min/dropzone.min');
	echo $this->Html->css('../assets/libs/dropify/css/dropify.min');
	echo $this->Html->css('../assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min');
	echo $this->Html->css('../assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min');
	echo $this->Html->css('../assets/libs/spectrum-colorpicker2/spectrum.min');
	echo $this->Html->css('../assets/libs/flatpickr/flatpickr.min');
	echo $this->Html->css('../assets/libs/clockpicker/bootstrap-clockpicker.min');
	echo $this->Html->css('../assets/css/app');
	echo $this->Html->css('app');
	?>
</head>

<body>

	<!-- Begin page -->
	<div id="wrapper">
		<!-- ========== Menu ========== -->
		<?php // $this->element('Commons/menu') ?>
		<!-- ========== Left menu End ========== -->


		<!-- ============================================================== -->
		<!-- Start Page Content here -->
		<!-- ============================================================== -->

		<div class="content-page">
			<?= $this->element('Common/header') ?>
			<div class="content mt-2">
				<?php echo $this->Flash->render(); ?>
				<!-- Start Content-->
				<?php echo $this->fetch('content'); ?>
				<!-- container -->

			</div> <!-- content -->


			<?= $this->element('Common/footer') ?>
		</div>

		<!-- ============================================================== -->
		<!-- End Page content -->
		<!-- ============================================================== -->

	</div>
	<?php
	// Incluir los scripts
	echo $this->Html->script('../assets/libs/jquery/jquery.min');
	echo $this->Html->script('../assets/js/vendor.min');
	echo $this->Html->script('../assets/libs/flatpickr/flatpickr.min');
	echo $this->Html->script('../assets/libs/apexcharts/apexcharts.min');
	echo $this->Html->script('../assets/libs/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min');
	echo $this->Html->script('../assets/libs/selectize/js/standalone/selectize.min');
	echo $this->Html->script('../assets/js/pages/dashboard-1.init');
	echo $this->Html->script('../assets/libs/jsgrid/jsgrid.min');
	echo $this->Html->script('../assets/libs/select2/js/select2.min');
	echo $this->Html->script('../assets/libs/dropzone/min/dropzone.min');
	echo $this->Html->script('../assets/libs/dropify/js/dropify.min');
	echo $this->Html->script('../assets/js/pages/form-fileuploads.init');
	echo $this->Html->script('../assets/libs/clockpicker/bootstrap-clockpicker.min');
	echo $this->Html->script('../assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min');
	echo $this->Html->script('../assets/libs/flatpickr/flatpickr.min');
	echo $this->Html->script('../assets/libs/spectrum-colorpicker2/spectrum.min');
	echo $this->Html->script('../assets/libs/clockpicker/bootstrap-clockpicker.min');
	echo $this->Html->script('../assets/js/pages/form-pickers.init');
	echo $this->Html->script('../assets/js/pages/form-wizard.init');
	echo $this->Html->script('../assets/js/app');
	?>
</body>

</html>