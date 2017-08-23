$(document).ready(function(){

    $spinner1 = $('#spinner1');

    initFilterForm($spinner1);

    $('#FilterForm_semester, #FilterForm_month, #FilterForm_discipline ').change(function(){

        var $form = $('#filter-form');

        var selects = [];
        selects.push($('#FilterForm_semester'));
        if ($(this).is('#FilterForm_month')||$(this).is('#FilterForm_discipline')) {
            selects.push($('#FilterForm_month'));
            selects.push($('#FilterForm_discipline'));
        }


        for (var i in selects) {
            var $that   = selects[i];
            var $select = $that.clone();
            var value   = $that.val();

            if (value.length == 0)
                continue;

            $select.find('option[value="'+$that.val()+'"]').attr('selected', 'selected');

            $form.append($select);
        }
		var $student = $('#student-field');
		$form.append($student);
        $form.submit();
    });
	
	$('.student-statistic').click(function(){
		
		event.preventDefault();
		var st=$(this).data('st1');
		$('#student-field').val(st);
        var $form = $('#filter-form');

        var selects = [];
        selects.push($('#FilterForm_semester'));

        for (var i in selects) {
            var $that   = selects[i];
            var $select = $that.clone();
            var value   = $that.val();

            if (value.length == 0)
                continue;

            $select.find('option[value='+$that.val()+']').attr('selected', 'selected');

            $form.append($select);
        }
		var $student = $('#student-field');
		$form.append($student);
        $form.submit();
    });

    initTooltips();

    $('[name="show-percents"]').change(function(){
        $('.attendance-statistic-table-2 tr span').toggleClass('hide');
    });
});