// global form error message text
var _errorMsgGlobal = 'There was an error while submiting form';
var _sucessMsgGlobal = 'Obtaining weather data is finished';

// messages
var _msg = {

    el: $('.alert'),
    error: function( text )
    {
        this.clear();
        this.el.addClass('alert-danger').removeClass('d-none').text( text );     
    },
    success: function( text )
    {
        this.clear();
        this.el.addClass('alert-success').removeClass('d-none').text( text );
    },
    clear: function()
    {
        this.el.removeClass('alert-danger').removeClass('alert-success').addClass('d-none').text('');
    }
}

// processing reslt table
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

    // show table title 
    setTitle: function( text )
    {
        $('h2',this.parent).text( text );   
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
    _msg.clear();

    // hide response table 
    _processTable.clear();

    // getting data from form
    var formData =  {
        city: $('#city', form).val(),
        date: $('#date', form).val()
    };

    // show spinner icon
    $('#spinner').css({display:'flex'});

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
                // hide spinner icon
                $('#spinner').hide();

                _msg.error( _errorMsgGlobal );
                return;
            };

            // process succesfull response
            if( 'undefined' != res.code && 200 == parseInt( res.code ) && 'undefined' != res.html )
            {
                // setting form data
                _processTable.setFormData( formData );

                // setting response data
                if( 'undefined' != res.title )
                {
                    _processTable.setTitle( res.title );
                }
                _processTable.setMainData( res.html );
                
                // show generated table
                _processTable.show();

                // show succesfull message
                _msg.success( _sucessMsgGlobal );
            }
            // show error to user 
            else
            {
                // show error message
                _msg.error( res.msg );
            }

            // hide spinner icon
            $('#spinner').hide();
        },
        error: function()
        {
            // show error message
            _msg.error( _errorMsgGlobal );

            // hide spinner icon
            $('#spinner').hide();
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

    // export & download btn
    $(document).on('click','#btn-export',function(e){

        e.preventDefault();
        var _t = $(e.currentTarget);

        // generating url address from obtained data
        var url = _t.attr('href');
        
        if( 'undefined' != typeof( _t.attr('data-city') ) && 'undefined' != typeof( _t.attr('data-date') ) )
        {
            url += '&city=' + _t.attr('data-city');
            url += '&date=' + _t.attr('data-date');

            window.location.href = url;
        }
        else
        {
            // fallback
            alert('Fill the form first');
        }
    })

});