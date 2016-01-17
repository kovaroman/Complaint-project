<?php defined('CHECK') OR exit('No direct script access allowed');?>
<div class="panel panel-default">
	<div class="panel-body">
		<h2 class="text-center">Вітаємо! Подайте, будь ласка, свою пропозицію чи скаргу.</h2>
		<hr>
		<form id="form_complain" action="/complain/add_new/" class="form-horizontal" method="post" accept-charset="utf-8">
			<input type="hidden" name="type" value="add" style="display:none;" />
			<div class="form-group">
				<label for="name" class="col-sm-3 control-label">Ваше ім'я<span class="req">*</span>:</label>
				<div class="col-sm-9">
					<input type="text" class="form-control" id="name" name="name" placeholder="Введіть своє ім'я">
					<span class="helpBlock text-danger"></span>
				</div>
			</div>
				
			<div class="form-group">
				<label for="email" class="col-sm-3 control-label">Ваше E-mail<span class="req">*</span>:</label>
				<div class="col-sm-9">
					<input type="email" class="form-control" id="email" name="email" placeholder="Введіть свій E-mail">
					<span class="helpBlock text-danger"></span>
				</div>
			</div>
				
			<div class="form-group">
				<label for="web" class="col-sm-3 control-label">Ваш Website:</label>
				<div class="col-sm-9">
					<input type="url" class="form-control" id="web" name="web" placeholder="Введіть свій Website">
					<span class="helpBlock text-danger"></span>
				</div>
			</div>
				
			<div class="form-group">
				<label for="mesg" class="col-sm-3 control-label">Ваше повідомлення<span class="req">*</span>:</label>
				<div class="col-sm-9">
					<textarea rows="6" class="form-control" id="mesg" name="mesg" placeholder="Введіть Ваше повідомлення"></textarea>
					<span class="helpBlock text-danger"></span>
				</div>
			</div>
			
			<div class="form-group">
				<label for="mesg" class="col-sm-3 control-label">Пройдіть перевірку (CAPTCHA)<span class="req">*</span>:</label>
				<div class="col-sm-9">
					<div id="g-recaptcha"></div>
					<span class="helpBlock text-danger"></span>
				</div>
			</div>
			
			<hr>
			
												
			<div class="form-group">
				<div class="col-sm-offset-3 col-sm-9">
					<button type="submit" class="btn btn-success" id="submit_form">Надіслати</button>
				</div>
			</div>
			<div id="loading"></div>
		</form>
	</div>
</div>
<script type="text/javascript">
	  var widgetId;
      var onloadCallback = function() {
        widgetId = grecaptcha.render('g-recaptcha', {
          'sitekey' : '6LdW-xMTAAAAAMnNdBWIq6xTzHlbTw6X6I7OEbCQ'
        });
      };
</script>