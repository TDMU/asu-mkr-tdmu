$(document).ready(function(){

    $spinner1 = $('#spinner1');

    initFilterForm($spinner1);

    function updateList(){
        $.fn.yiiGridView.update('oprrez-list',{ data: $('#filter-form').serialize() });
    }

    $(document).on('change', '.oprrez-select', function(){

        var $that = $(this);
        var data  = {
            st1  : $that.data('st1'),
            opr1 : !$that.val() ? -1 : $that.val()
        };
        var url   = $('#oprrez-list').data('url');

        $that.prop('disabled', 'disabled');

        $.ajax({
            type: 'POST',
            url:  url,
            data: data,
            success:function(data){
                if (data == 'ok') {
                    addGritter( 'Удачно', 'Сохранено', 'success')
                    updateList();
                } else
                    addGritter('Ошибка', '"Ошибка добавления', 'error');
                $that.prop('disabled', false);
            },
            error:function(jqXHR, textStatus, errorThrown) {
                if (jqXHR.status == 500) {
                    addGritter( 'Ошибка', 'Internal error: ' + jqXHR.responseText, 'error')
                } else {
                    if (jqXHR.status == 403) {
                        addGritter('Ошибка','Access error: ' + jqXHR.responseText, 'error')
                    } else {
                        addGritter('Ошибка','Unexpected error: ' + jqXHR.responseText, 'error')
                    }
                }
                $that.prop('disabled', false);
            },

            dataType:'html'
        });

        return false;
    })
});