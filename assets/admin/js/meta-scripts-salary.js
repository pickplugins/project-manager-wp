jQuery(document).ready(function($) {



    $(document).on('click', '.salary-data .reset-data', function() {


        console.log('Hello');


        $(this).parent('td').children('input[type="text"]').val('');

    })




	
});	







