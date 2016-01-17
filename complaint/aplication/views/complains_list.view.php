<?php defined('CHECK') OR exit('No direct script access allowed');?>
<div class="panel panel-default">
	<div class="panel-body">

		<h2 class="text-center"><?php echo $title ?></h2>
		<hr>
		<div class="">
			<button type="button" class="btn btn-success" id="btn_show_form_new"><span class="glyphicon glyphicon-plus"></span> Нове повідомлення</button>
		</div>

		<div class="modal_cont">
			<?php require_once 'parts/complain_new.php'; ?>
			<?php require_once 'parts/complain_edit.php'; ?>
		</div>
		
		<div>
			<div id="MainLoading"></div>
			<nav class="text-center">
				<ul class="pagination">
					<?php $aFirstPage = array_shift($aLinks) ?>
					<?php $aLastPage = array_pop($aLinks) ?>
						<li><a href="<?php echo $aFirstPage['link'] ?>"><?php echo $aFirstPage['title'] ?></a></li>
					<?php foreach( $aLinks as $Link) { ?>
						<li <?php if ($Link['active']){ ?>class="active"<?php } ?>><a href="<?php echo $Link['link'] ?>"><?php echo $Link['title'] ?></a></li>
					<?php } ?>
						<li><a href="<?php echo $aLastPage['link'] ?>"><?php echo $aLastPage['title'] ?></a></li>
				</ul>
			</nav>
			<table class="table complains" style="">
				<thead>
					<tr>
						<th>#</th>
						<th><a href="#" class="sort_btn" data-name="name"><?php if(isset($aViewOrder['name'])) { ?><span class="glyphicon glyphicon-arrow-<?php if($aViewOrder['name'] == 'down') { ?>down<?php } else { ?>up<?php } ?>"></span> <?php } ?>Ім'я</a></th>
						<th><a href="#" class="sort_btn" data-name="email"><?php if(isset($aViewOrder['email'])) { ?><span class="glyphicon glyphicon-arrow-<?php if($aViewOrder['email'] == 'down') { ?>down<?php } else { ?>up<?php } ?>"></span> <?php } ?>E-Mail</a></th>
						<th>Website</th>
						<th>Повідомлення</th>
						<th><a href="#" class="sort_btn" data-name="date"><?php if(isset($aViewOrder['date'])) { ?><span class="glyphicon glyphicon-arrow-<?php if($aViewOrder['date'] == 'down') { ?>down<?php } else { ?>up<?php } ?>"></span> <?php } ?>Дата</a></th>
						<?php if(isset($bUserLogin) && $bUserLogin && $_SESSION['user']['user_role'] == 0){ ?>
						<th>IP</th>
						<th>Браузер</th>
						<th>Дія</th>
						<?php } ?>
					</tr>
				</thead>
				
				<?php foreach ($aComplains as $oComplain){ ?>
				<tr id="<?php echo $oComplain->id ?>">
					<td style="width:40px; text-align:center"><?php echo $oComplain->number.'<br><div style="font-size:10px">(id '.$oComplain->id.')</div>'; ?></td>
					<td width="80" class="ComplName"><?php echo $oComplain->user_name ?></td>
					<td style="max-width:200px" class="ComplEmail"><?php echo $oComplain->user_email ?></td>
					<td width="60" class="ComplWeb"><?php echo $oComplain->user_web ?></td>
					<td style="max-width:400px"><div class="hide_text_bottom ComplText" data-toggle="popover" data-content="<?php echo $oComplain->user_message ?>"><?php echo $oComplain->user_message ?></div></td>
					<td><?php echo $oComplain->date_add ?></td>
					<?php if(isset($bUserLogin) && $bUserLogin && $_SESSION['user']['user_role'] == 0){ ?>
					<td><?php echo $oComplain->user_ip ?></td>
					<td width="90" style="text-align:center"><div class="hide_text_bottom" data-toggle="popover" data-content="<?php echo $oComplain->user_browser ?>"><?php echo $oComplain->user_browser ?></div></td>
					<td>
						<a href="<?php echo $oComplain->id ?>" class="edit_complain" title="Редагувати">
							<span class="glyphicon glyphicon-pencil text-primary"></span>
						</a>
						<a href="<?php echo $oComplain->id ?>" class="delete_complain" title="Видалити">
							<span class="glyphicon glyphicon-remove text-danger"></span>
						</a>
					</td>
					<?php } ?>
				</tr>
				<?php } ?>
			</table>
			<nav class="text-center">
				<ul class="pagination">
						<li><a href="<?php echo $aFirstPage['link'] ?>"><?php echo $aFirstPage['title'] ?></a></li>
					<?php foreach( $aLinks as $Link) { ?>
						<li <?php if ($Link['active']){ ?>class="active"<?php } ?>><a href="<?php echo $Link['link'] ?>"><?php echo $Link['title'] ?></a></li>
					<?php } ?>
						<li><a href="<?php echo $aLastPage['link'] ?>"><?php echo $aLastPage['title'] ?></a></li>
				</ul>
			</nav>
		</div>
	</div>
</div>