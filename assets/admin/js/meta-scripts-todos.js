jQuery(document).ready(function($) {
		
	$('#to_do_deadline').datepicker({ dateFormat : 'yy-mm-dd' });
	
	$( ".pm-task-ajax-search" ).select2({        
		ajax: {
			url: pm_ajax.pm_ajaxurl,
			dataType: 'json',
			delay: 250,
			data: function (params) {
				return {
					q: params.term,
					action: 'pm_ajax_search_task'
				};
			},
			processResults: function (data) {
				return {
					results: data
				};
			},
		},
		minimumInputLength: 2,
		placeholder: "Type something",
	});


	$(document).on('click', '.to_do-worker .worker-list .worker.add ', function() {
		$('.search-worker').css('display','block');
		$('.to_do-worker .worker-list .worker.close-search-window').fadeIn();
	})
		
	$(document).on('click', '.to_do-worker .worker-list .worker.close-search-window', function() {
		$('.search-worker').fadeOut();
		$(this).fadeOut();
	})

	$(document).on('keyup', '.search-worker .worker-name ', function(){

		user_name = $(this).val();
		$.ajax(
			{
		type: 'POST',
		context: this,
		url:pm_ajax.pm_ajaxurl,
		data: {"action": "pm_ajax_search_user","user_name": user_name, },
		success: function(data) {	
			$('.worker-result').html(data);
		}
			});
	})
		
	$(document).on('click', '.to_do-worker .worker-list .worker-result .name', function() {
			
		user_id = $(this).attr('user-id');
		avatar 	= $( '.user-' + user_id + ' img' ).attr('src');
		
	
		if( $('.to_do-worker .worker-list').find( '#worker-' + user_id ).length ) {
			
			$('.toast').html( 'Already added !' );
			$('.toast').stop().fadeIn(400).delay(3000).fadeOut(400);
			return;
		}
		
		$('.to_do-worker .worker-list').prepend( 
		"<div  worker-id="+user_id+" id=worker-"+user_id+" class=\"worker worker-hover\"><img src=\""+avatar+"\" /></div>" + 
		"<div class=\"worker-hover-window worker-hover-window-"+user_id+"\"></div>" + 
		"<input type='hidden' name='to_do_workers[]' value='"+user_id+"' /></div>");
	})
	
	$(document).on('click', '.to_do-worker .worker-list .worker-hover', function() {
			
		user_id = $(this).attr('worker-id');
		$('.worker-hover-window').hide();
		$('.worker-hover-window-' + user_id ).fadeIn();
		$(this).css( 'cursor', 'wait' );
				
		$.ajax(
			{
		type: 'POST',
		context: this,
		url:pm_ajax.pm_ajaxurl,
		data: {"action": "pm_ajax_get_user_details","user_id": user_id, },
		success: function(data) {	
			$(this).css( 'cursor', '-webkit-zoom-in' );
			$( '.worker-hover-window-' + user_id ).html(data);
		}
			});
	})

	$(document).on('click', '.to_do-worker .worker-list ._hide', function() {
		$(this).parent().fadeOut();
		
	})
	
	$(document).on('click', '.to_do-worker .worker-list ._userdata._userremove', function() {
			
		user_id = $(this).attr('user-id');
		$( '#worker-' + user_id ).remove();
		$( '#worker-id-' + user_id ).remove();

		$(this).parent().remove();
		$(this).parent().prev().prev().remove();
	})
		
	

	// TPDO PROGRESS START
	
	$(document).on('click', '.todo-progress .add-progress', function() {

		$(this).fadeOut();
		$('.todo-progress .progress-input').fadeIn();
		$('.todo-progress .close-progress').css('display','inline-block');
	})
		
	$(document).on('click', '.todo-progress .close-progress', function() {

		$(this).fadeOut();
		$('.todo-progress .progress-input').fadeOut();
		$('.todo-progress .add-progress').css('display','inline-block');
	})
		
	$(document).on('click', '.todo-progress .submit-progress', function() {
	
		todo_id = $(this).attr('todo_id');
		content = (jQuery('#wp-todo-progress-editor-wrap').hasClass("tmce-active")) ? tinyMCE.get('todo-progress-editor').getContent() : jQuery('#todo-progress-editor').val();
		
		if( content.length == 0 ) {
			
			$('.toast').html( 'Empty data !' );
			$('.toast').stop().fadeIn(400).delay(3000).fadeOut(400);
			return;
		}
		
		$('.todo-progress .submit-progress-status').css('display', 'inline-block');

		$.ajax(
			{
		type: 'POST',
		context: this,
		url:pm_ajax.pm_ajaxurl,
		data: {
			"action": "pm_ajax_submit_todo_work_progress",
			"todo_id": todo_id, 
			"content": content,
		},
		success: function(data) {	
			
			$('.todo-progress .submit-progress-status').css('display', 'none');
			//tinyMCE.get('todo-progress-editor').setContent('')
			
			$('.to_do-meta .todo-progress .progress-list').prepend( data ).hide().fadeIn();
			
			
		}
			});
		
	})
	
	
	
	
	
	
		
		
	// TPDO PROGRESS END	
		
		
	
	
	$(document).on('click', '.to_do-meta .edit-post-title,.to_do-meta .edit-post-title ', function() {
		$(this).parent().fadeOut();
		$('.post_title').fadeIn();
	})
	
	
		
	$(document).on('click', '.to_do-meta .edit-post-content, .to_do-meta .edit-post-content', function() {
		$(this).parent().fadeOut();
		$('.post_content').fadeIn();
	})
	
});	







