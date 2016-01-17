<?php defined('CHECK') OR exit('No direct script access allowed');
set_include_path(get_include_path().PATH_SEPARATOR.dirname(__FILE__));
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<title><?php echo Config::Get('view.name').' - '.$title;?></title>
	<link rel="icon" href="<?php echo base_url('assets/images/favicon.ico'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/jquery-ui.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/custom.css'); ?>">
	<script src="<?php echo base_url('assets/js/jquery_2_1_4.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/js/jquery-ui.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/js/js.cookie.js'); ?>"></script>
	<script src="<?php echo base_url('assets/js/bootstrap.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/js/custom.js'); ?>"></script>
	<script src="<?php echo base_url('assets/js/ie10-viewport-bug-workaround.js'); ?>"></script>
	<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
	<script type="text/javascript">
		var DIR_WEB_ROOT = "<?php echo base_url(); ?>";
	</script>
</head>
<body>
	<?php require('parts/menu.view.php') ?>
	<div class="container-fluid" style="margin-top:20px">
  		<div class="container">
		<?php if(isset($sMes)){ ?>
			<div class="alert <?= (isset($bMesError)) ? "alert-danger" : "alert-success" ?> alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<strong><?php echo lang('common_attention') ?></strong> <?php echo $sMes ?>
			</div>		
		<?php } ?>