// global form error message text
var _errorMsgGlobal = 'There was an error while submiting form';

// processing form function
var _processForm = function( form ){

    // hide previous error if exists
    $('.alert',form).addClass('d-none');

    // getting data from form
    var formData =  {
        city: $('#city', form).val(),
        date: $('#date', form).val()
    };

    // ajax call
    $.ajax({
        url: form.attr('action'),
        data: formData,
        method: 'POST',
        success: function( res )
        {
            res = JSON.parse( res );

            // process succesfull response
            if( 200 == res.code )
            {

            }
            // show error to user 
            else
            {
                $('.alert',form).removeClass('d-none').text( res.msg );
            }
        },
        fail: function()
        {
            $('.alert',form).removeClass('d-none').val( _errorMsgGlobal );
        }
    });

}

$(function(){
    
    // init of datepicker for forecast form
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        startDate: '0d',
        endDate: '+5d'
    });

    // processing form event
    $(document).on('submit','#forecast-form',function(e){
        e.preventDefault();
        _processForm( $(e.currentTarget) );
    });

});