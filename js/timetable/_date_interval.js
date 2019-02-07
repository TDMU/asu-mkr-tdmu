$(document).ready(function(){

    $('#id-date-range-picker-1').daterangepicker({
        format: 'DD.MM.YYYY',
        locale: {
            applyLabel: 'Ок',
            cancelLabel: 'Отмена',
            fromLabel: 'С',
            toLabel: 'По',
            monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь',
                'Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
            monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн',
                'Июл','Авг','Сен','Окт','Ноя','Дек'],
            daysOfWeek: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
            firstDay: 1
        },
        startDate: startDate,
        endDate: endDate,
        dateLimit: {days: 140}

    }, function(start, end) {
        $('#TimeTableForm_date1').val(start.format('DD.MM.YYYY'));
        $('#TimeTableForm_date2').val(end.format('DD.MM.YYYY'));
    });

    $(document).on('click', '#sem-date', function(){
        $('#TimeTableForm_date1').val($(this).data('date1'));
        $('#TimeTableForm_date2').val($(this).data('date2'));
        $(this).closest('form').submit();
    });

});