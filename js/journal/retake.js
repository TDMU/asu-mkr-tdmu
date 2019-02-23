$(document).ready(function(){

    $spinner1 = $('#spinner1');

    $(document).on('click', '.datepicker', function(event) {
        $(this).datepicker({
            'format': 'dd.mm.yy',
            'language':'ru'
        })
        .on('changeDate', function(ev){
            $(this).datepicker('hide');
        })
        .focus();
    });
	
    $('#FilterForm_group,#FilterForm_discipline').change(function(){
        var $form   = $('#filter-form');
        $form.submit();
    });

    $(document).on('click','.btn-add-retake,.btn-view-retake', function(e) {
        e.preventDefault();
        $('#myModal #modal-content').html('');
        $('#save-stego').hide();
        var url = $(this).attr('href');
        $.ajax({
            url: url,
            dataType: 'json',
            success: function( data ) {
                if (!data.error) {
                    $('#myModal .modal-header h4').html(data.title);
                    $('#myModal #modal-content').html(data.html);
                    if(data.show){
                        $('#save-stego').show();
                    }
                    $('.datepicker').datepicker({
                        format: 'dd.mm.yy',
                        language:'ru'
                    });
                    $('#myModal').modal('show');
                } else {
                    addGritter(data.html, tt.error_load, 'error');
                }
            },
            error: function( data ) {
                addGritter('', tt.error_load, 'error');
            }
        });
    });

    $(document).on('click','.delete-retake', function(e) {
        e.preventDefault();
        var params = {
            elgotr0:$(this).data("elgotr0")
        }
        var url = $(this).data('url');
        $.get(url, params, function(data){
            $('#myModal').modal('hide');
            if (data.error) {
                addGritter('', tt.error, 'error')
            } else {
                addGritter('', tt.success, 'success');
                $.fn.yiiGridView.update('retake');
            }
        }, 'json')
    });

    $(document).on('click','#save-stego', function(e) {
        var params = {
            elgotr1:$('#Elgotr_elgotr1').val(),
            p1:$('#Elgotr_elgotr4').val(),
            date:   $('#Elgotr_elgotr3').val(),
            value : parseFloat( $('#Elgotr_elgotr2').val().replace(',','.') )
        }
        if (isNaN(params.value)) {
            addGritter('', tt.error, 'error');
            $('#Elgotr_elgotr2').addClass('error');
            return false;
        }
        if ( params.value > 100 ) {
            addGritter('', tt.minMaxError, 'error');
            $('#Elgotr_elgotr2').addClass('error');
            return false;
        }
        if (params.date=="") {
            addGritter('', tt.error, 'error');
            $('#Elgotr_elgotr3').addClass('error');
            return false;
        }


        var url = $('#myModal').data('url');
        $.get(url, params, function(data){
            $('#myModal').modal('hide');
            if (data.error) {
                addGritter('', getError(data), 'error')
            } else {
                addGritter('', tt.success, 'success');
                $.fn.yiiGridView.update('retake');
            }
        }, 'json').error(function(jqXHR, textStatus, errorThrown) {
            $('#myModal').modal('hide');
            if (jqXHR.status == 500) {
                addGritter('', 'Internal error: ' + jqXHR.responseText, 'error')
            } else {
                if (jqXHR.status == 403) {
                    addGritter('', 'Access error: ' + jqXHR.responseText, 'error')
                } else {
                    addGritter('', tt.error, 'error');
                }
            }
        });

        return false;
    });
	
});


function getError(data)
{
    if (data.error) {
        var error='';
        switch (data.errorType) {
            case 0:
                error = tt.error;
                break
            case 3:
                error = tt.access;
                break
            default:
                error = tt.error;
        }
        return error;
    }else {
        return tt.success;
    }
}