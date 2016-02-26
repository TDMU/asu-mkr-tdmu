/**
 * Created by Neff on 10.02.2016.
 */
$(document).ready(function(){

    $('[data-toggle="popover"]').popover();


    $('#btn-refresh').click(function( event ){
        event.preventDefault();
        $("#filter-form").submit();
    });

    $spinner1 = $('#spinner1');

    initFilterForm($spinner1);

    var val_=$(this).val();
    var count_=$( "#lesson-list").data('count');
    showColumn($('#lesson-list').val());
    checkDisabledButton(val_,count_);

    $( "#lesson-list" ).change(function() {
        var val=$(this).val();
        var count=$( "#lesson-list").data('count');
        showColumn(val);
        checkDisabledButton(val,count)
    });

    $('#lesson-left').click(function(){
        changeLesson(false);
    });

    $('#lesson-right').click(function(){
        changeLesson(true);
    });

    $('.input-td input').keyup(function(event ){
        if(event.keyCode == 46)
        {
            var $that = $(this);

            var st1  = $that.parents('[data-st1]').data('st1');

            var gr1  = $that.parents('[data-gr1]').data('gr1');
            var field = $that.data('name');

            var params = {
                field : field,
                nom  : $that.parent().data('number'),
                elgz1   : $that.parent().data('elgz1'),
                type_lesson   : $that.parent().data('type_lesson'),
                r1   : $that.parent().data('r1'),
                st1   : st1,
                gr1   : gr1,
                date:$that.parent().data('date'),
                value : '0'
            }

            var stName = $('.journal-table tr[data-st1='+st1+'] td:eq(0)').text();
            var index  = $that.parent().index();
            var nom   = $that.parents('table').find('th:eq('+index+')').html();
            var title  = stName+'<br>'+nom+'<br>';

            var $trElem    = $that.parents('tr');
            var $td    = $that.parent();

            var url = $that.parents('[data-url]').data('url');

            $spinner1.show();
            send(url,params,title,$trElem,$td,$that,$spinner1,st1);
            $that.val('');
            $that.addClass('not-value');
            $trElem.find(':checkbox').attr('disabled','disabled');
        }

    });

    $('.checkbox-td input,.input-td input').change(function(event){
        var $that = $(this);
        var st1  = $that.parents('[data-st1]').data('st1');
        var typeLesson =$that.parents('[data-type-lesson]').data('type-lesson');
        var gr1  = $that.parents('[data-gr1]').data('gr1');
        var field = $that.data('name');
        var isCheckbox = $that.is(':checkbox');
        var params = {
            field : field,
            nom  : $that.parent().data('number'),
            elgz1   : $that.parent().data('elgz1'),
            type_lesson   : $that.parent().data('type_lesson'),
            r1   : $that.parent().data('r1'),
            st1   : st1,
            gr1   : gr1,
            date:$that.parent().data('date'),
            value : isCheckbox
                ? ($that.is(':checked') ? 0 : 1)
                : parseFloat( $that.val() )
        }

        var stName = $('.journal-table tr[data-st1='+st1+'] td:eq(0)').text();
        var index  = $that.parent().index();
        var nom   = $that.parents('table').find('th:eq('+index+')').html();
        var title  = stName+'<br>'+nom+'<br>';

        var $trElem    = $that.parents('tr');
        var $td    = $that.parent();


        if (params.field!='elgzst3'&&params.value==0&&!$that.is(':checkbox')&&ps55==0) {
            addGritter(title, _error, 'error')
            $td.addClass('error')
            return false;
        }

        if (isNaN(params.value)||(params.value<0)) {
            addGritter(title, _error, 'error')
            $td.addClass('error')
            return false;
        }

        if (params.field=='elgzst3'&&params.value==0)
        {
            var val=$trElem.find('td [data-name="elgzst4"]').val();
            if(val!=''&&typeLesson==1) {
                addGritter(title, _error, 'error');
                $td.addClass('error');
                $that.removeAttr('checked');
                return false;
            }
        }
        // min max check
        var url = $that.parents('[data-url]').data('url');
        var url_check = $that.parents('[data-url-check]').data('url-check');

        $spinner1.show();
        if ($that.is(':checkbox')&&params.value==1)
        {
            $.ajax({
                url: url_check,
                dataType: 'json',
                data:params,
                success: function( data ) {
                    if (data.error) {
                        addGritter(title, _error, 'error')
                        $td.addClass('error');
                        $enable=false;
                    } else {
                        if(data.count)
                        {
                            $( "#dialog-confirm" ).dialog({
                                resizable: false,
                                modal: true,
                                title: "<div class='widget-header'><h4 class='smaller'><i class='icon-warning-sign red'></i>Удаление</h4></div>",
                                title_html: true,
                                buttons: [
                                    {
                                        html: "<i class='icon-trash bigger-110'></i>&nbsp; Удалить",
                                        "class" : "btn btn-danger btn-mini",
                                        click: function() {
                                            $( this ).dialog( "close" );
                                            send(url,params,title,$trElem,$td,$that,$spinner1,st1);
                                        }
                                    }
                                    ,
                                    {
                                        html: "<i class='icon-remove bigger-110'></i>&nbsp; Отмена",
                                        "class" : "btn btn-mini",
                                        click: function() {
                                            $( this ).dialog( "close" );
                                            $spinner1.hide();
                                            $that.prop( 'checked', true );
                                        }
                                    }
                                ]
                            });
                        }else
                        {
                            send(url,params,title,$trElem,$td,$that,$spinner1,st1);
                        }

                    }
                },
                error: function( data ) {
                    addGritter(title, _error, 'error');
                    $trElem.addClass('error');
                }
            });
        }
        else
        {
            send(url,params,title,$trElem,$td,$that,$spinner1,st1);
        }

    });
});


function showColumn(date){
    $('.journal-bottom th:not(:first-child),.journal-bottom td:not(:first-child)').hide();
    var lessons = $('.journal-bottom [data-number="'+date+'"]');
    if(lessons.length>0)
        lessons.show();
    else
        alert('Not found lesson number='+date);
}

function changeLesson(type){
    var val=$( "#lesson-list").val();
    var count=$( "#lesson-list").data('count');
    var newVal = val;
    if(type)
        newVal++;
    else
        newVal--;
    if(newVal>count||newVal<1)
        alert('Not found lesson');
    else
    {
        checkDisabledButton(newVal,count);
        $( "#lesson-list").val(newVal).change();
    }
}

function checkDisabledButton(newVal,count)
{
    $('#lesson-left').removeClass('disabled').removeAttr('disabled');
    $('#lesson-right').removeClass('disabled').removeAttr('disabled');
    if(newVal==1)
        $('#lesson-left').addClass('disabled').attr('disabled','disabled');
    if(newVal==count)
        $('#lesson-right').addClass('disabled').attr('disabled','disabled');
}


function getError(data)
{
    if (data.error) {
        var error='';
        switch (data.errorType) {
            case 0:
                error = _error;
                break
            case 2:
                error = _error2;
                break
            case 3:
                error = _access;
                break
            case 4:
                error = _minMaxError;
                break
            case 5:
                error = _st;
                break
            default:
                error = _error;
        }
        return error;
    }else {
        return _success;
    }
}

function send(url,params,title,$trElem,$td,$that,$spinner1,st1)
{
    $.ajax({
        url: url,
        dataType: 'json',
        data: params,
        success: function(data){
            $spinner1.hide();
            if (data.error) {
                addGritter(title, getError(data), 'error')
                $td.addClass('error');
            } else {
                addGritter(title, _success, 'success')
                $td.removeClass('error').addClass('success');
                setTimeout(function() { $trElem.removeClass('success') }, 1000);
                if ($that.is(':checkbox'))
                {
                    var elem = $trElem.find('td [data-name="elgzst5"]');
                    if ($that.prop('checked')) {
                        $trElem.find('td  [data-name="elgzst4"]').attr('disabled', 'disabled');
                        //elem.removeAttr('disabled');
                    }
                    else
                    {
                        $trElem.find('td  [data-name="elgzst4"]').removeAttr('disabled');
                        //elem.attr('disabled', 'disabled');
                        if(!elem.is(':checkbox'))
                            elem.val('');
                        else
                            elem.removeAttr('checked');
                    }

                    if(params.value==1) {
                        if(elem.is(':checkbox'))
                            elem.attr('disabled', 'disabled');
                    }
                    else {
                        if(elem.is(':checkbox'))
                            elem.removeAttr('disabled');
                    }
                }

                if ($that.is(':text'))
                {
                    if($that.val()!=""&&parseFloat( $that.val() )>=0)
                        $that.removeClass('not-value');
                    else
                        $that.addClass('not-value');

                    if($that.data('name')=='elgzst4')
                        if($that.val()!=""&&parseFloat( $that.val() )>=0)
                            $trElem.find(':checkbox').attr('disabled','disabled');
                        else
                            $trElem.find(':checkbox').removeAttr('disabled');

                    if($that.data('name')=='elgzst5')
                    {
                        if(parseFloat( $that.val() )>0)
                            $trElem.find('td [data-name="elgzst4"]').attr('disabled','disabled');
                        else
                            $trElem.find('td [data-name="elgzst4"]').removeAttr('disabled');
                    }
                }

                var elem5 = $trElem.find('td [data-name="elgzst5"]');
                var elem3 = $trElem.find('td [data-name="elgzst3"]');
                var elem4 = $trElem.find('td [data-name="elgzst4"]');
                if(elem5){
                    if(elem3.prop('checked')||parseFloat(elem4.val())<=minBal) {
                        if (parseFloat(elem5.val()) > minBal) {
                            $trElem.find('td .btn-retake').hide();
                            elem5.attr('disabled', 'disabled');
                        }
                        else {
                            $trElem.find('td .btn-retake').show();
                            elem5.removeAttr('disabled');
                        }
                    }
                    else {
                        $trElem.find('td .btn-retake').hide();
                        elem5.attr('disabled', 'disabled');
                    }
                }else{
                    if(elem3.prop('checked')) {
                        $trElem.find('td .btn-retake').show();
                        elem5.removeAttr('disabled');
                    }
                    else {
                        $trElem.find('td .btn-retake').hide();
                        elem5.attr('disabled', 'disabled');
                    }
                }
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            if (jqXHR.status == 500) {
                addGritter('', 'Internal error: ' + jqXHR.responseText, 'error')
            } else {
                if (jqXHR.status == 403) {
                    addGritter('', 'Access error: ' + jqXHR.responseText, 'error')
                } else {
                    addGritter('', 'Unexpected error.', 'error')
                }
            }
            $td.addClass('error');
            $spinner1.hide();
        }
    });
}

