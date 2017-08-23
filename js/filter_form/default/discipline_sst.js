$(document).ready(function(){

    $spinner1 = $('#spinner1');

    $(document).on('change', '#FilterForm_group', function() {

        var $form  = $(this).closest('form');
        var formId = $form.attr('id');
        var url    = $form.attr('action');
        $('#FilterForm_sem1').val('0');
        $('#FilterForm_discipline').val('0');

        $spinner1.show();

        $.get(url, $form.serialize(), function(data){

            var html = $('#'+formId, data).html();
            $('div', $form).replaceWith(html);
            initChosen();
            $spinner1.hide();
        })
    });

    $('#FilterForm_sem1').change(function(){

        var $that   = $(this);
        var $select = $that.clone();
        var $form   = $('#filter-form');
        var value   = $that.val();

        if (value.length == 0)
            return;

        $select.find('option[value='+$that.val()+']').attr('selected', 'selected');

        $form.append($select);

        $form.submit();
    });

    $(document).on('change', '#FilterForm_discipline,#FilterForm_type_lesson', function() {
        $(this).closest('form').submit();
    });

});