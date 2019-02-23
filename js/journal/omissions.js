$(document).ready(function(){

    $spinner1 = $('#spinner1');

    initFilterForm($spinner1);

    initPopovers();

    initTooltips();

    $(document).on('click', 'input.datepicker', function(event) {
        $(this).datepicker({
            format: 'dd.mm.yyyy',
            language: 'ru'
        }).focus();
    });

    $('i.show-info').click(function(){
        showDialog();
    });

    $('#save-change-omissions').click(function(){
        var $that = $(this);
        var st1 = $that.data('st1');
        var date1=$('#date1_omissions_change').val();
        var date2=$('#date2_omissions_change').val();
        var number=$('#number_reference').val();
        var type_omissions=$('#select-type').val();
        var elgp4=$('#elgp4').val();
        var elgp5=$('#elgp5').val();
        var title =$('#myModal .modal-header h4').html();

        var params = {
            st1:st1,
            date1:date1,
            date2:date2,
            number:number,
            elgp4:elgp4,
            elgp5:elgp5,
            type_omissions:type_omissions
        }

        var url = $that.data('url');
        $('#myModal').modal('hide');
        $spinner1.show();
        $.getJSON(url, params, function(data){

            if (data.error) {
                addGritter(title, tt.error, 'error')
            } else {
                addGritter(title, tt.success, 'success')
                setTimeout(function() {
                    $td.removeClass('success') }, 1000);
                $('#filter-form').submit();
            }

            $spinner1.hide();
        }).error(function(jqXHR, textStatus, errorThrown) {
            if (jqXHR.status == 500) {
                addGritter(title, 'Internal error: ' + jqXHR.responseText, 'error')
            } else {
                if (jqXHR.status == 403) {
                    addGritter(title, 'Access error: ' + jqXHR.responseText, 'error')
                } else {
                    addGritter(title, tt.error, 'error');
                }
            }
            $spinner1.hide();
        })
    });

    $('#select-type').change(function(){
        var val = $(this).val();
        if(val!=5)
            $('#elgp').hide();
        else
            $('#elgp').show();
    });

    $('#omissions .input-elgp3, #omissions .input-elgp4,#omissions .input-elgp5 ,#omissions .select-elgp2').change(function(){

        var $that = $(this);

        var elgp0    = $that.parents('[data-elgp0]').data('elgp0');

        var params = {
            field : $that.data('field'),
            elgp0:elgp0,
            value : $that.val()
        }
        var $td    = $that.parent();

        var date   = $that.parents('tr').find('.date').html();
        var title  = date;

        var url = $that.parents('[data-url]').data('url');

        $spinner1.show();

        $.getJSON(url, params, function(data){

            if (data.error) {
                addGritter(title, tt.error, 'error')
                $td.addClass('error');
            } else {
                addGritter(title, tt.success, 'success')
                $td.removeClass('error').addClass('success');
                setTimeout(function() {
                    $td.removeClass('success') }, 1000);
                if($that.is("select"))
                {
                    $val='';
                    if(params.value<5)
                        $val=$that.parents('[data-type2]').data('type2');
                    else
                        $val=$that.parents('[data-type1]').data('type1');
                    $that.parents('tr').find('.type-omissions').html($val);

                    $('#filter-form').submit();
                }
            }

            $spinner1.hide();
        }).error(function(jqXHR, textStatus, errorThrown) {
            if (jqXHR.status == 500) {
                addGritter(title, 'Internal error: ' + jqXHR.responseText, 'error')
            } else {
                if (jqXHR.status == 403) {
                    addGritter(title, 'Access error: ' + jqXHR.responseText, 'error')
                } else {
                    addGritter(title, tt.error, 'error');
                }
            }
            $td.addClass('error');
            $spinner1.hide();
        })

    });
});

function showDialog()
{
    $( "#dialog-message" ).dialog({
        modal: true,
        title: "<div class='widget-header'><h4 class='smaller'>"+tt.popupTitle+"</h4></div>",
        title_html: true,
        buttons: [
            {
                html: "<i class='icon-remove bigger-110'></i>&nbsp; Ok",
                class : "btn btn-info btn-mini",
                click: function() {
                    $( this ).dialog( "close" );
                }
            }
        ]
    });
}