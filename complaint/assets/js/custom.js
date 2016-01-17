$(document).ready(function(){
	"use strict";
	
	// ajax adding of new complain from main page of the site
	$('body').on('click', '#submit_form', function(e){
		clearErrors();
		$('#loading').show();
		$.ajax({
			method: "POST",
			url: DIR_WEB_ROOT + "complain/add_new/",
			data: $("#form_complain").serialize(),
			dataType : "json",
			success: function(data){
				$('#loading').hide();
				if (data.status === "OK") {
					SuccessMes();
					clearForm();
					grecaptcha.reset(widgetId);
				} else if (data.status === "ERR") {
					showErrors(data.errors);
					grecaptcha.reset(widgetId);
				}
			}.bind(this)
		});
		e.preventDefault();
	});
	
	// ajax adding of a new complain from journal part of the site
	$('body').on('click', '#submit_form_new', function(e){
		clearErrors();
		$('#loading').show();
		$.ajax({
			method: "POST",
			url: DIR_WEB_ROOT + "complain/add_new/",
			data: $("#form_complain").serialize(),
			dataType : "json",
			success: function(data){
				$('#loading').hide();
				if (data.status === "OK") {
					SuccessMes2();
					grecaptcha.reset(widgetId);
				} else if (data.status === "ERR") {
					showErrors(data.errors);
					grecaptcha.reset(widgetId);
				}
			}.bind(this)
		});
		e.preventDefault();
	});
	
	// ajax editing of complain
	$('body').on('click', '#submit_form_edit', function(e){
		clearErrors();
		$('#loading2').show();
		$.ajax({
			method: "POST",
			url: DIR_WEB_ROOT + "complain/edit/",
			data: $("#form_complain_edit").serialize(),
			dataType : "json",
			success: function(data){
				$('#loading2').hide();
				if (data.status === "OK") {
					fillComplain();
					clearEditForm();
					$('#form_edit_complain').modal('hide');
				} else if (data.status === "ERR") {
					showEditErrors(data.errors);
				}
			}.bind(this)
		});
		e.preventDefault();
	});
	
	// show form of adding a new complain with captcha
	var widgetId;
	$('body').on('click', '#btn_show_form_new', function(e){
		clearForm();
		$("#form_complain").show();
		$(".sent").hide();
		$("#submit_form_new").show();
		$('#form_new_complain').modal('show');
		widgetId = grecaptcha.render('g-recaptcha', {
          'sitekey' : '6LdW-xMTAAAAAMnNdBWIq6xTzHlbTw6X6I7OEbCQ'
        });

		e.preventDefault();
	});
	
	// show form of editing complain
	$('body').on('click', '.edit_complain', function(e){
		clearEditForm();
		$('#MainLoading').show();
		// ajax getting data of edited complain
		var get_id = $(this).parent().parent().attr('id');
		$.ajax({
			method: "POST",
			url: DIR_WEB_ROOT + "complain/edit/",
			data: {id: get_id},
			dataType : "json",
			success: function(data){
				$('#MainLoading').hide();
				if (data.status === "OK") {
					fillEditForm(data.id, data.name, data.email, data.web, data.message);
				}
			}.bind(this)
		});
		$('#form_edit_complain').modal('show');
		e.preventDefault();
	});
	
	// delete complain
	$('body').on('click', '.delete_complain', function(e){
		var ParContainer = $(this).parent().parent();
		var username = $('#'+ParContainer.attr('id')+' .ComplName').html();
		if(confirm('Ви дійсно бажаєте видалити повідомлення від ' + username + '?')){
			$('#MainLoading').show();
			$.ajax({
				method: "POST",
				url: DIR_WEB_ROOT + "complain/delete/",
				data: {id: ParContainer.attr('id')},
				dataType : "json",
				success: function(data){
					$('#MainLoading').hide();
					if (data.status === "OK") {
						ParContainer.remove();
					}
				}.bind(this)
			});
		}
		e.preventDefault();
	});
	
	// set sort direction
	$('body').on('click', '.sort_btn', function(e){
		var sortType = $(this).attr('data-name');
		var cookieVal = Cookies.getJSON('sort');
		if (cookieVal === undefined) {
			Cookies.set('sort', { type: sortType, direction: 'down' });
		} else if (cookieVal.type === sortType) {
			if (cookieVal.direction === 'down') {
				Cookies.set('sort', { type: sortType, direction: 'up' });
			} else {
				Cookies.set('sort', { type: sortType, direction: 'down' });
			}
		} else {
			Cookies.set('sort', { type: sortType, direction: 'down' });
		}
		window.location.href=window.location.href;
		e.preventDefault();
	});
	
	// show errors in form
	function showErrors(errors) {
		for(var i=0; i<errors.length; i++){
			$("#"+errors[i].name).next().html(errors[i].text);
		}
	}
	
	// show errors in edit form
	function showEditErrors(errors) {
		for(var i=0; i<errors.length; i++){
			$("#"+errors[i].name + '_edit').next().html(errors[i].text);
		}
	}
	
	// clear errors in form
	function clearErrors() {
		$('.helpBlock').each(function(e) {
			$(this).html('');
		});
	}
	
	// clear form
	function clearForm() {
		$('#name').val('');
		$('#email').val('');
		$('#web').val('');
		$('#mesg').val('');
		$("#cancel_form_new").html('Скасувати');
	}
	
	// clear edit form
	function clearEditForm() {
		$('#hidden_id').val('');
		$('#name_edit').val('');
		$('#email_edit').val('');
		$('#web_edit').val('');
		$('#mesg_edit').val('');
	}
	// clear edit form
	function fillEditForm(hidden_id, name_edit, email_edit, web_edit, mesg_edit) {
		$('#hidden_id').val(hidden_id);
		$('#name_edit').val(name_edit);
		$('#email_edit').val(email_edit);
		$('#web_edit').val(web_edit);
		$('#mesg_edit').val(mesg_edit);
	}
	
	// clear edit form
	function fillComplain() {
		$('#' + $('#hidden_id').val() + ' .ComplName').html($('#name_edit').val());
		$('#' + $('#hidden_id').val() + ' .ComplEmail').html($('#email_edit').val());
		$('#' + $('#hidden_id').val() + ' .ComplWeb').html($('#web_edit').val());
		$('#' + $('#hidden_id').val() + ' .ComplText').html($('#mesg_edit').val());
		$('#' + $('#hidden_id').val() + ' .ComplText').attr('data-content', $('#mesg_edit').val());
	}
	
	function SuccessMes() {
		alert('Дякуємо, ваше повідомлення було надіслано!');
	}
	
	function SuccessMes2() {
		$("#form_complain").hide();
		$(".sent").show();
		$("#submit_form_new").hide();
		$("#cancel_form_new").html('Закрити');
	}
	
	$('[data-toggle="popover"]').popover({
		delay: 350,
		placement: 'top',
		trigger: 'hover',
		html: true
	});
	
});
