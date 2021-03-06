<?php defined('CHECK') OR exit('No direct script access allowed');?>

<div class="modal fade" id="form_new_complain" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title" id="myModalLabel1">Нове повідомлення</h4>
	  </div>
	  
	  <div class="modal-body">
		<form id="form_complain" class="form-horizontal" method="post" accept-charset="utf-8">
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
			<div id="loading"></div>
		</form>
		
		<div class="sent"><br><br><h4 class="modal-title text-center">Дякуюємо! Ваше повідомлення надіслано!</h4><br><br></div>
		
	  </div>
	  
	  <div class="modal-footer">
		<button type="button" id="cancel_form_new" class="btn btn-default" data-dismiss="modal">Скасувати</button>
		<button type="button" id="submit_form_new" class="btn btn-primary">Надіслати</button>
	  </div>
	</div>
  </div>
</div>
