<?php defined('CHECK') OR exit('No direct script access allowed');?>
<div class="row">
	<div class="col-xs-12 col-sm-4 col-sm-offset-4" style="padding-top:100px">
		<form method="POST" action="<?php echo base_url('user/login') ?>">
			<input type="hidden" name="check" value="login_form">
			<div class="form-group">
				<label for="username">Логін:</label>
				<input type="text" class="form-control" id="username"  name="username">
			</div>
		
			<div class="form-group">
				<label for="password">Пароль:</label>
				<input type="password" class="form-control" id="password"  name="password">
			</div>
		
			<?php if(isset($err) && $err === true) { ?>
				<div class="form-group" style="color: #B90303">Такої комбінації логіну і паролю не знайдено!</div>
			<?php }	?>
		
			<button type="submit" class="btn btn-default">Увійти</button>
		</form>
	</div>
</div>