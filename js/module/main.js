$(document).ready(function(){

    $spinner1 = $('#spinner1');

    initFilterForm($spinner1);

    $('#ModuleForm_countModules').change(function(){
        submitForm();
    });

    function submitForm() {
        var $that   = $('#ModuleForm_countModules');
        var $field = $that.clone();
        $field.hide();

        var $form   = $('#filter-form');

        $form.append($field);
        $form.submit();
    }

    $('.btn-select-module').click(function(e){
        e.preventDefault();
        var $tr = $(this).parents('tr');
        $('#ModuleForm_module').val($tr.data('id'));
        $('#filter-form').submit();
    });

    $('.input-module').change(function(){
        var $url = $('#modules').data('url');
        var $tr = $(this).parents('tr');

        $spinner1.show();

        var params = {
            field : $(this).data('field'),
            id   : $tr.data('module'),
            value : $(this).val()
        }

        $.ajax({
            url: $url,
            dataType: 'json',
            data:params,
            success: function(data) {
                addGritter('', 'Успешно', 'success');
                $spinner1.hide();
            },
            error: function(jqXHR, textStatus, errorThrown){
                if (jqXHR.status == 500) {
                    addGritter('', 'Internal error: ' + jqXHR.responseText, 'error')
                } else {
                    if (jqXHR.status == 403) {
                        addGritter('', 'Access error: ' + jqXHR.responseText, 'error')
                    } else {
                        addGritter('', jqXHR.status + ' ' + jqXHR.responseText, 'error');
                    }
                }
                $spinner1.hide();
            }
        });
    });

    $('.input-mark, .input-exam-mark').change(function(){
        var $td = $(this).parents('td');

        var params = {
            st1 : $(this).data('st1'),
            module   : $(this).data('module'),
            mark : $(this).val()
        }

        $spinner1.show();


        $.ajax({
            url: $('#marks').data('url'),
            dataType: 'json',
            data:params,
            success: function(data) {
                addGritter('', 'Успешно', 'success');
                $td.removeClass('error').addClass('success');
                $spinner1.hide();
                recalculate(params.st1)
            },
            error: function(jqXHR, textStatus, errorThrown){
                if (jqXHR.status == 500) {
                    addGritter('', 'Internal error: ' + jqXHR.responseText, 'error')
                } else {
                    if (jqXHR.status == 403) {
                        addGritter('', 'Access error: ' + jqXHR.responseText, 'error')
                    } else {
                        addGritter('', jqXHR.status + ' ' + jqXHR.responseText, 'error');
                    }
                }
                $td.removeClass('success').addClass('error');
                $spinner1.hide();
            }
        });
    });

    function recalculate(st1) {
        var modules = '#marks .input-mark[data-st1=' + st1 + ']';
        var exams = '#marks .input-exam-mark[data-st1=' + st1 + ']';

        var total = 0;
        $(modules).each(function () {

            var mark = $(this).val();

            if (!isNaN(mark) && mark)
                total += parseInt(mark);
        });
        $('#marks .summ[data-st1=' + st1 + ']').text(total);

        $(exams).each(function () {

            var mark = $(this).val();

            if (!isNaN(mark) && mark)
                total += parseInt(mark);
        });
        $('#marks .itog-summ[data-st1=' + st1 + ']').text(total);
    }
});