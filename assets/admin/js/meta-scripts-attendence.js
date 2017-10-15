jQuery(document).ready(function($) {



    $(document).on('click', '.left-atten', function() {

        post_id = $(this).attr('post_id');
        $(this).children('.loading').fadeIn();


        $.ajax(
            {
                type: 'POST',
                context: this,
                url:pm_ajax.pm_ajaxurl,
                data: {"action": "pm_ajax_action_left_atten",
                    "post_id": post_id,

                },
                success: function(data) {

                    var response 		= JSON.parse(data)
                    var message 	= response['message'];

                    $(this).html(message);
                    //console.log(message);
                    location.reload();
                }
            });
    })




    $(document).on('click', '.give-atten', function() {

        post_id = $(this).attr('post_id');
        $(this).children('.loading').fadeIn();


        $.ajax(
            {
                type: 'POST',
                context: this,
                url:pm_ajax.pm_ajaxurl,
                data: {"action": "pm_ajax_action_give_atten",
                    "post_id": post_id,

                },
                success: function(data) {

                    var response 		= JSON.parse(data)
                    var message 	= response['message'];

                    $(this).html(message);
                    //console.log(message);
                    location.reload();
                }
            });
    })




    $(document).on('click', '.action-atten', function() {

        post_id = $(this).attr('post_id');
        user_id = $(this).attr('user_id');
        date = $(this).attr('date');
        month = $(this).attr('month');
        year = $(this).attr('year');
        $(this).children('.loading').fadeIn();

        $.ajax(
            {
                type: 'POST',
                context: this,
                url:pm_ajax.pm_ajaxurl,
                data: {"action": "pm_ajax_action_attendance",
					"post_id": post_id,
					"user_id": user_id,
                    "date": date,
                    "month": month,
                    "year": year,
				},
                success: function(data) {

                    var response 		= JSON.parse(data)
                    var message 	= response['message'];

                    $(this).html(message);
                    //console.log(message);
                    location.reload();
                }
            });
    })


    $(document).on('click', '.action-lunch', function() {

        post_id = $(this).attr('post_id');
        user_id = $(this).attr('user_id');
        date = $(this).attr('date');
        month = $(this).attr('month');
        year = $(this).attr('year');
        $(this).children('.loading').fadeIn();

        $.ajax(
            {
                type: 'POST',
                context: this,
                url:pm_ajax.pm_ajaxurl,
                data: {"action": "pm_ajax_action_lunch",
                    "post_id": post_id,
                    "user_id": user_id,
                    "date": date,
                    "month": month,
                    "year": year,
                },
                success: function(data) {

                    var response 		= JSON.parse(data)
                    var message 	= response['message'];

                    $(this).html(message);
                    //console.log(message);
                    location.reload();
                }
            });
    })



    $(document).on('click', '.action-left-submit', function() {

        custom_time = $(this).parent().children('input').val();

        //alert(custom_time);

        post_id = $(this).parent().attr('post_id');
        user_id = $(this).parent().attr('user_id');
        date = $(this).parent().attr('date');
        month = $(this).parent().attr('month');
        year = $(this).parent().attr('year');
        $(this).children('.loading').fadeIn();

        $.ajax(
            {
                type: 'POST',
                context: this,
                url:pm_ajax.pm_ajaxurl,
                data: {"action": "pm_ajax_action_left",
                    "post_id": post_id,
                    "user_id": user_id,
                    "date": date,
                    "month": month,
                    "year": year,
                    "custom_time": custom_time,
                },
                success: function(data) {

                    var response 		= JSON.parse(data)
                    var message 	= response['message'];

                    $(this).html(message);
                    //console.log(message);
                    location.reload();
                }
            });
    })















	
});	







