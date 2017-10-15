jQuery(document).ready(function($) {
		
	$('#task_deadline').datepicker({ dateFormat : 'yy-mm-dd' });
	
	$( ".pm-project-ajax-search" ).select2({        
		ajax: {
			url: pm_ajax.pm_ajaxurl,
			dataType: 'json',
			delay: 250,
			data: function (params) {
				return {
					q: params.term,
					action: 'pm_ajax_search_project'
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


	$(document).on('click', '.task-worker .worker-list .worker.add ', function() {
		$('.search-worker').css('display','block');
		$('.task-worker .worker-list .worker.close-search-window').fadeIn();
	})
		
	$(document).on('click', '.task-worker .worker-list .worker.close-search-window', function() {
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
		
	$(document).on('click', '.task-worker .worker-list .worker-result .name', function() {
			
		user_id = $(this).attr('user-id');
		avatar 	= $( '.user-' + user_id + ' img' ).attr('src');
		
		if( $('.task-worker .worker-list').find( '#worker-' + user_id ).length ) {
			
			$('.toast').html( 'Already added !' );
			$('.toast').stop().fadeIn(400).delay(3000).fadeOut(400);
			return;
		}
		
		$('.task-worker .worker-list').prepend( 
		"<div  worker-id="+user_id+" id=worker-"+user_id+" class=\"worker worker-hover\"><img src=\""+avatar+"\" /></div>" + 
		"<div class=\"worker-hover-window worker-hover-window-"+user_id+"\"></div>" + 
		"<input type='hidden' name='task_workers[]' value='"+user_id+"' /></div>");
	})
	
	$(document).on('click', '.task-worker .worker-list .worker-hover', function() {
			
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

	$(document).on('click', '.task-worker .worker-list ._hide', function() {
		$(this).parent().fadeOut();
		
	})
	
	$(document).on('click', '.task-worker .worker-list ._userdata._userremove', function() {
			
		user_id = $(this).attr('user-id');
		$( '#worker-' + user_id ).remove();
		$( '#worker-id-' + user_id ).remove();

		$(this).parent().remove();
		$(this).parent().prev().prev().remove();
	})
		
	$(document).on('click', '.task-comment .ajax-add-comment', function() {
		
		post_id = $(this).attr('post_id');
		//comment_content = $('.task-comment #comment-content').val();
		//comment_content = $('#comment-content').val();
		var editor = tinyMCE.get('comment-content');
		comment_content = editor.getContent();
		// $('.task-comment .comment-input').append( '<i class="fa fa-cog fa-spin comment-loading-icon"></i>' );
		//alert(comment_content);
		//console.log(comment_content);
		
		
		if( comment_content.length <= 0 ) {
			
			$( '.task-comment .comment-content' ).addClass( 'error-field' );
			$( '.task-comment .comment-input' ).prepend( '<div class="pm_error_message">This field is required !</div>' ).hide().fadeIn();

			setTimeout(function() {
				$( '.task-comment .comment-content' ).removeClass( 'error-field' );
				$( '.task-comment .pm_error_message' ).fadeOut();
			}, 2500);

			return;
		}
	
		$('.comment-loading-icon').css('display','inline-block');
	
		$.ajax(
			{
		type: 'POST',
		context: this,
		url:pm_ajax.pm_ajaxurl,
		data: {"action": "pm_ajax_admin_add_comment","post_id": post_id, "comment_content" : comment_content },
		success: function(data) {	
			
			$('.comment-loading-icon').fadeOut();
			$('.task-comment .comment-content').val('');
			$('.task-comment .comment-list').prepend(data).hide().fadeIn();
		}
			});
			
			
	})




	$(document).on('change', '.task-meta .filter-to-do-status', function() {
		
		val = $(this).val();
		var url      = window.location.href;
		
		url = url+'&to_do_status='+val;
		
		window.location.href = url;
		
		
	})	









	// $(document).on('click', '.client-list ._hide', function() {
		// $('.client-hover-window').fadeOut();
	// })

	// $(document).on('click', '.client-list ._userdata._userremove', function() {
		// user_id = $(this).attr('user-id');
		// $( '#client-' + user_id ).remove();
		// $('.client-hover-window-' + user_id ).remove();			
	// })
	
	// end clients list
	

	
	// post-title
	// $(document).on('click', '.project-meta .edit-post-title,.task-meta .edit-post-title ', function() {
		// $(this).parent().fadeOut();
		// $('.post_title').fadeIn();
	// })
		
	// $(document).on('click', '.project-meta .edit-post-content, .task-meta .edit-post-content', function() {
		// $(this).parent().fadeOut();
		// $('.post_content').fadeIn();
	// })
	
});	







