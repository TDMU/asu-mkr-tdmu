$(document).ready(function(){

    initSpinner('spinner1');

    $spinner1 = $('#spinner1');

    // flag for reloading page
    var refreshPage = false;

    $(document).on('change', 'tr.min-max input', function(){

        var $that = $(this);

        var params = {
            value : parseFloat( $that.val().replace(',','.') ),
            field : $that.data('name'),
            vvmp1 : vvmp1
        }

        var $td = $that.parent();

        if (isNaN(params.value)) {
            addGritter('', tt.error, 'error')
            $td.addClass('error')
            return false;
        }

        var url = $that.parents('[data-url]').data('url');

        $spinner1.show()

        $.get(url, params, function(data){

            if (data.error) {
                addGritter('', tt.error, 'error')
                $td.addClass('error');
            } else {
                addGritter('', tt.success, 'success')
                $td.removeClass('error').addClass('success');

                setTimeout(function() { $td.removeClass('success') }, 1000)

                refreshPage = true;
            }

            $spinner1.hide();
        }, 'json')
    });

    $('div[class*=journal_div_table] tr:not(.min-max) input').change(function(){

        $that = $(this);

        var st1  = $that.parents('[data-st1]').data('st1');

        var params = {
            vvmp1  : vvmp1,
            st1    : st1,
            field  : $that.data('name'),
            module : $that.data('module'),
            value  : parseFloat( $that.val().replace(',','.') )
        }

        var stName = $('table.journal_table_1 tr[data-st1='+st1+'] td:eq(1)').text();
        var index  = $that.parent().index();
        var module = $that.parents('table').find('th:eq('+index+')').html();
        var title  = stName+'<br>'+module+'<br>';
        var $td    = $that.parent();

        if (isNaN(params.value)) {
            addGritter(title, tt.error, 'error')
            $td.addClass('error')
            return false;
        }

        // min max check
        // ps16 - portal settings
        if (ps16 == '1' && $that.parents('.journal_div_table2').length > 0) {

            var $tr  = $that.closest('table').find('.min-max');
            var $th1 = $tr.find('th:eq('+(index*2)+')');
            var $th2 = $th1.next();

            var min = parseFloat( $th1.find('input').val() );
            var max = parseFloat( $th2.find('input').val() );

            if ( params.value < min || params.value > max ) {
                addGritter(title, tt.minMaxError, 'error')
                $td.addClass('error')
                return false;
            }
        }

        var url = $that.parents('[data-url]').data('url');

        $spinner1.show();

        $.get(url, params, function(data){

            if (data.error) {
                addGritter(title, tt.error, 'error')
                $td.addClass('error');
            } else {
                addGritter(title, tt.success, 'success')
                $td.removeClass('error').addClass('success');

                setTimeout(function() { $td.removeClass('success') }, 1000)

                recalculateAllTotals(st1);
            }

            $spinner1.hide();
        }, 'json')
    });

    $('#close-module').click(function(){
        var url = $(this).data('url');
        $.get(url, {vvmp1: vvmp1}, function(data){
            if (data.res)
                $('#filter-form').submit();
        }, 'json')
    });

    $('.show-extended-module').click(function(){
        var url = $(this).parents('[data-extended_module_url]').data('extended_module_url');
        var params = {
            uo1:  uo1,
            gr1:  $('#FilterForm_group').val(),
            d1:   $('#FilterForm_discipline').val(),
            module_num: $(this).parent().data('module_num')
        }

        $.get(url, params, function(data){
            $('.journal-bottom').append(data);
            $('#modal-table').modal('show');
        })
    });

    $(document).on('hide', '#modal-table', function () {
        if (refreshPage)
            $('#filter-form').submit();
    });

    $(document).on('hidden', '#modal-table', function () {
        $(this).remove();
    });

    $(document).on('change', '.expanded-module tr:not(.min-max) input', function(){

        $that = $(this);

        var st1 = $that.parents('[data-st1]').data('st1');

        var params = {
            vvmp1  : vvmp1,
            st1    : st1,
            field  : $that.data('name'),
            module : $that.parents('tr').data('module'),
            value  : parseFloat( $that.val().replace(',','.') )
        }

        var stName = $('table.journal_table_1 tr[data-st1='+st1+'] td:eq(1)').text();
        var index  = $that.parent().index();
        var module = $that.parents('table').find('th:eq('+index+')').html();
        var title  = stName+'<br>'+module+'<br>';
        var $td    = $that.parent();

        if (isNaN(params.value)) {
            addGritter(title, tt.error, 'error')
            $td.addClass('error')
            return false;
        }

        // min max check
        // ps16 - portal settings
        if (ps16 == '1') {

            var $tr  = $that.closest('table').find('.min-max');
            var $th1 = $tr.find('th:eq(0)');
            var $th2 = $th1.next();

            var min = parseFloat( $th1.find('input').val() );
            var max = parseFloat( $th2.find('input').val() );

            var sum = 0;
            var $inputs = $('.expanded-module tr[data-st1='+st1+'] input');
            $inputs.each(function(){
                var mark = parseFloat($(this).val())
                if (! isNaN(mark))
                    sum += mark;
            });

            if ( sum < min || sum > max ) {
                addGritter(title, tt.minMaxError, 'error')
                $td.addClass('error')
                return false;
            }
        }

        var url = $that.parents('[data-url]').data('url');

        $spinner1.show();

        $.get(url, params, function(data){

            if (data.error) {
                addGritter(title, tt.error, 'error')
                $td.addClass('error');
            } else {
                addGritter(title, tt.success, 'success')
                $td.removeClass('error').addClass('success');

                setTimeout(function() { $td.removeClass('success') }, 1000)

                $('.expanded-module tr[data-st1='+st1+'] td.module_total').html(sum);

                refreshPage = true;
            }

            $spinner1.hide();
        }, 'json')


    });
});


function recalculateAllTotals(st1)
{
    var total_1 = 0;
    var total_2 = 0;
    var total_3 = 0;

    var table_2 = 'div.journal_div_table2 tr[data-st1='+st1+']';
    var table_3 = 'div.journal_div_table3 tr[data-st1='+st1+']';

    $(table_2 +' td input').each(function(){

        var mark = parseFloat($(this).val())

        if (! isNaN(mark))
            total_1 += mark;
    });

    $(table_3 +' td input').each(function(){

        var mark = parseFloat($(this).val())

        if (! isNaN(mark))
            if ($(this).data('module') == '0' || $(this).data('name') == 'stus3')
                total_2 += mark;
            else
                total_3 += mark;
    });

    $(table_3 +' td[data-total=1]').text(total_1);
    $(table_3 +' td[data-total=2]').text(total_1 + total_2);
    $(table_3 +' td[data-total=3]').text(total_1 + total_2 + total_3);
}