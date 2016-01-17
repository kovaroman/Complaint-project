<?php defined('CHECK') OR exit('No direct script access allowed');
?>
<!-- Static navbar -->
<nav class="navbar navbar-default navbar-static-top">
  <div class="container">
	<div class="navbar-header">
	  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
		<span class="sr-only">Toggle navigation</span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
	  </button>
	  <a class="navbar-brand" href="<?php echo base_url() ?>">iRooM</a>
	</div>
	<div id="navbar" class="navbar-collapse collapse">
	  <ul class="nav navbar-nav">
	  	<li <?php if(isset($menu_item) && $menu_item=='complain'){?>class="active"<?php }?>><a href="<?php echo base_url('complain') ?>">Журнал</a></li>
	  </ul>
		<ul class="nav navbar-nav navbar-right">
			<?php  if(isset($bUserLogin) && $bUserLogin && $_SESSION['user']['user_role'] == '0'){ ?>
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>  <?php echo $_SESSION['user']['user_name'] ?> <span class="caret"></span></a>
				<ul class="dropdown-menu">
					<li><a href="<?php echo base_url('user/logout'); ?>"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> Вийти</a></li>
				</ul>
			</li>
			<?php } else { ?>
			<li><a href="<?php echo base_url('user/login'); ?>" ><span class="glyphicon glyphicon-log-in"></span>  Увійти</a>
			<?php } ?>
		</ul>
	</div><!--/.nav-collapse -->
  </div>
</nav>