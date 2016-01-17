<?php defined('CHECK') OR exit('No direct script access allowed');?>

<div class="modal fade" id="form_edit_complain" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title" id="myModalLabel2">Редагування повідомлення</h4>
	  </div>
	  
	  <div class="modal-body">
		<form id="form_complain_edit" class="form-horizontal" method="post" accept-charset="utf-8" action="/complain/edit/">
			<input type="hidden" name="type" value="edit" style="display:none;" />
			<input type="hidden" id="hidden_id" name="id" value="" style="display:none;" />
			<div class="form-group">
				<label for="name" class="col-sm-3 control-label">Ваше ім'я<span class="req">*</span>:</label>
				<div class="col-sm-9">
					<input type="text" class="form-control" id="name_edit" name="name" placeholder="Введіть своє ім'я">
					<span class="helpBlock text-danger"></span>
				</div>
			</div>
				
			<div class="form-group">
				<label for="email" class="col-sm-3 control-label">Ваше E-mail<span class="req">*</span>:</label>
				<div class="col-sm-9">
					<input type="email" class="form-control" id="email_edit" name="email" placeholder="Введіть свій E-mail">
					<span class="helpBlock text-danger"></span>
				</div>
			</div>
				
			<div class="form-group">
				<label for="web" class="col-sm-3 control-label">Ваш Website:</label>
				<div class="col-sm-9">
					<input type="url" class="form-control" id="web_edit" name="web" placeholder="Введіть свій Website">
					<span class="helpBlock text-danger"></span>
				</div>
			</div>
				
			<div class="form-group">
				<label for="mesg" class="col-sm-3 control-label">Ваше повідомлення<span class="req">*</span>:</label>
				<div class="col-sm-9">
					<textarea rows="6" class="form-control" id="mesg_edit" name="mesg" placeholder="Введіть Ваше повідомлення"></textarea>
					<span class="helpBlock text-danger"></span>
				</div>
			</div>
			<div id="loading2"></div>
		</form>
	  </div>
	  
	  <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Скасувати</button>
		<button type="button" id="submit_form_edit" class="btn btn-primary">Зберегти</button>
	  </div>
	</div>
  </div>
</div>
