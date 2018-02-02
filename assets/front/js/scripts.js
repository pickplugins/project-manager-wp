jQuery(document).ready(function($) {






 setInterval(function(){ $.ajax(
			{
		type: 'POST',
		context: this,
		url:pm_ajax.pm_ajaxurl,
		data: {"action": "pm_ajax_notify_logg" },
		success: function(data) {	
		//$('.pm-notify .notify-count').html(data);

            var response 		= JSON.parse(data)

            var pending_count 	= response['pending_count'];
            var notification_message 	= response['notification_message'];
            var total_notification_count 	= response['total_notification_count'];

            pending_count = parseInt(pending_count);

            //console.log(data);

            console.log(pending_count);
            //console.log(notification_message);
            //console.log(total_notification_count);




            var audioElement = document.createElement('audio');
            audioElement.setAttribute('src', 'http://192.168.0.90/server/wp-content/plugins/project-manager/assets/alert.mp3');

            audioElement.addEventListener('ended', function() {
                this.play();
            }, false);


		if(pending_count > 0){



            console.log('Sound created');
            audioElement.play();


            var notification = new Notification('Project Manager', {
                body: notification_message,
                icon: '',
            });

		}
		else{
            audioElement.pause();

            console.log('Sound paused');


            $('audio').remove();



		}

			
		}
			}); }, 10000);



	$(document).on('mouseover', '.pm-notify .notify-button', function(){

		
	
		//alert(notify_id);
		$('.pm-notify .notify-reload').fadeIn();
		$('.pm-notify .notify-count').fadeOut();
		
		$.ajax(
			{
		type: 'POST',
		context: this,
		url:pm_ajax.pm_ajaxurl,
		data: {"action": "pm_ajax_notify_reload", },
		success: function(data) {	
		
			$('.notify-list').html(data);
			$('.pm-notify .notify-reload').fadeOut();
			//$(this).fadeOut();
			//$('.worker-result').html(data);
			
		}
			});
	})



	$(document).on('click', '.pm-notify .item .notify-mark', function(){

		notify_id = $(this).attr('notify-id');
	
		//alert(notify_id);
		$('.pm-notify .notify-count').fadeOut();
	
		$.ajax(
			{
		type: 'POST',
		context: this,
		url:pm_ajax.pm_ajaxurl,
		data: {"action": "pm_ajax_notify_mark","notify_id": notify_id, },
		success: function(data) {	
		
			$(this).addClass('read');
			//$(this).fadeOut();
			//$('.worker-result').html(data);
			
		}
			});
	})



});	