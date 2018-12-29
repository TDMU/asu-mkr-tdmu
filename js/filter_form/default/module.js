$(document).ready(function(){

    $spinner1 = $('#spinner1');

    $(document).on('change', '#FilterForm_chair,#FilterForm_stream,#FilterForm_students2moodle,#FilterForm_discipline,#FilterForm_group,#FilterForm_module,#FilterForm_statement', function() {

        $(this).closest('form').submit();
    });

});