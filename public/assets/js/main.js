// global form error message text
var _errorMsgGlobal = 'There was an error while submiting form';

var _processTable = {

    // main parent of the response table
    parent: $('#forecastTable'),

    // setting form data for export purposes
    setFormData: function( data )
    {
        $('#btn-export', this.parent).attr('data-city',data.city).attr('data-date',data.date);
    },

    // generating result table
    setMainData: function( data )
    {
        $('tbody', this.parent).html( data );
    },

    // show response table
    show: function()
    {
        this.parent.removeClass('d-none');
    },

    // clear and hide response table
    clear: function(){

        this.parent.addClass('d-none');
        $('tbody', this.parent).html('');
    },
}

// processing form function
var _processForm = function( form ){

    // hide previous error if exists
    $('.alert',form).addClass('d-none');

    // hide response table 
    _processTable.clear();

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
            // parsing obtained JSON data
            try {
                res = JSON.parse( res );
            }
            catch (e) {
                $('.alert',form).removeClass('d-none').text( _errorMsgGlobal );
                return;
            };

            // process succesfull response
            if( 'undefined' != res.code && 200 == parseInt( res.code ) && 'undefined' != res.html )
            {
                // setting form data
                _processTable.setFormData( formData );

                // setting response data
                _processTable.setMainData( res.html );

                // show generated table
                _processTable.show();
            }
            // show error to user 
            else
            {
                $('.alert',form).removeClass('d-none').text( res.msg );
            }
        },
        error: function()
        {
            $('.alert',form).removeClass('d-none').text( _errorMsgGlobal );
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