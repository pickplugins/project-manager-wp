jQuery(document).ready(function($) {
		
	$('#project_deadline').datepicker({ dateFormat : 'yy-mm-dd' });
	$('#pm_task_meta_deadline').datepicker({ dateFormat : 'yy-mm-dd' });

	
	$(document).on('click', '.admin-list .user.add ', function() {
		$('.search-user').css('display','block');
		$('.admin-list .user.close-search-window').fadeIn();
	})
		
	$(document).on('click', '.admin-list .user.close-search-window', function() {
		$('.search-user').fadeOut();
		$(this).fadeOut();
	})

	$(document).on('keyup', '.search-user .user-name ', function(){

		user_name = $(this).val();
		$.ajax(
			{
		type: 'POST',
		context: this,
		url:pm_ajax.pm_ajaxurl,
		data: {"action": "pm_ajax_search_user","user_name": user_name, },
		success: function(data) {	
			$('.user-result').html(data);
		}
			});
	})
		
	$(document).on('click', '.admin-list .user-result .name', function() {
			
		user_id = $(this).attr('user-id');
		avatar 	= $( '.user-' + user_id + ' img' ).attr('src');
		
		if( $('.admin-list').find( '#user-' + user_id ).length ) {
			
			$('.toast').html( 'Already added !' );
			$('.toast').stop().fadeIn(400).delay(3000).fadeOut(400);
			return;
		}
		
		
		$('.admin-list').prepend( 
		"<div  user-id="+user_id+" id=user-"+user_id+" class=\"user user-hover\"><img src=\""+avatar+"\" /></div>" + 
		"<div class=\"user-hover-window user-hover-window-"+user_id+"\"></div>" + 
		"<input type='hidden' id=\"user-input-"+user_id+"\" name='project_admin[]' value='"+user_id+"' />");
	})
	
	$(document).on('click', '.admin-list .user-hover', function() {
			
		user_id = $(this).attr('user-id');
		$('.user-hover-window').hide();
		$('.user-hover-window-' + user_id ).fadeIn();
				
		$.ajax(
			{
		type: 'POST',
		context: this,
		url:pm_ajax.pm_ajaxurl,
		data: {"action": "pm_ajax_get_user_details","user_id": user_id, },
		success: function(data) {	
			$( '.user-hover-window-' + user_id ).html(data);
		}
			});
	})

	$(document).on('click', '.admin-list ._hide', function() {
		$('.user-hover-window').fadeOut();
	})
	
	$(document).on('click', '.admin-list ._userdata._userremove', function() {
			
		user_id = $(this).attr('user-id');
		$( '#user-' + user_id ).remove();
		$('.user-hover-window-' + user_id ).remove();			
		$('#user-input-' + user_id ).remove();			
	})
		
	// end of Admin list 
		
		
		
		
		
		
		
		
		
		
	//client list
	$(document).on('click', '.client-list .client.add ', function() {
		$('.search-client').fadeIn();
		$('.client-list .client.close-search-window').fadeIn();
	})
		
	$(document).on('click', '.client-list .client.close-search-window', function() {
		$('.search-client').fadeOut();
		$(this).fadeOut();
	})

	$(document).on('keyup', '.search-client .client-name', function(){
		user_name = $(this).val();
		$.ajax(
			{
		type: 'POST',
		context: this,
		url:pm_ajax.pm_ajaxurl,
		data: {"action": "pm_ajax_search_user","user_name": user_name, },
		success: function(data) {	
			$('.client-result').html(data);
		}
			});
	})
		
	$(document).on('click', '.client-list .client-result .name', function() {
		user_id = $(this).attr('user-id');
		avatar 	= $( '.client-list .user-' + user_id + ' img' ).attr('src');
		
		if( $('.client-list').find( '#client-' + user_id ).length ) {
			
			$('.toast').html( 'Already added !' );
			$('.toast').stop().fadeIn(400).delay(3000).fadeOut(400);
			return;
		}
		
		$('.client-list').prepend( 
		"<div client-id="+user_id+" id=client-"+user_id+" class=\"client client-hover\"><img src=\""+avatar+"\" /></div>" + 
		"<div class=\"client-hover-window client-hover-window-"+user_id+"\"></div>" + 
		"<input type='hidden' id=\"client-input-"+user_id+"\" name='project_clients[]' value='"+user_id+"' />");
	})

	$(document).on('click', '.client-list .client-hover', function() {
		user_id = $(this).attr('client-id');
		$('.client-hover-window').hide();
		$('.client-hover-window-' + user_id ).fadeIn();
		$(this).css( 'cursor', 'wait' );
			
		$.ajax(
			{
		type: 'POST',
		context: this,
		url:pm_ajax.pm_ajaxurl,
		data: {"action": "pm_ajax_get_user_details","user_id": user_id, },
		success: function(data) {	
			$(this).css( 'cursor', '-webkit-zoom-in' );
			$( '.client-hover-window-' + user_id ).html(data);
		}
			});
	})

	$(document).on('click', '.client-list ._hide', function() {
		$('.client-hover-window').fadeOut();
	})

	$(document).on('click', '.client-list ._userdata._userremove', function() {
		user_id = $(this).attr('user-id');
		$( '#client-' + user_id ).remove();
		$('.client-hover-window-' + user_id ).remove();			
	})
	
	// end clients list
	

	
	// post-title
	$(document).on('click', '.project-meta .edit-post-title,.task-meta .edit-post-title ', function() {
		$(this).parent().fadeOut();
		$('.post_title').fadeIn();
	})
		
	$(document).on('click', '.project-meta .edit-post-content, .task-meta .edit-post-content', function() {
		$(this).parent().fadeOut();
		$('.post_content').fadeIn();
	})
	
	
	$(document).on('change', '.project-meta .filter-task-status', function() {
		
		val = $(this).val();
		var url      = window.location.href;
		
		url = url+'&task_status='+val;
		
		window.location.href = url;
		
		
	})	
	
	
	
	
	
	
	
	
	
	
});	







