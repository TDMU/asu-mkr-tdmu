/**
 * Created by Neff on 13.03.2017.
 */
$(document).ready(function(){
    $spinner1 = $('#spinner1');

    $('.checkbox-sg1').change(function(event ) {
        event.preventDefault();

        var $that = $(this);

        var sg1 = $that.data('sg1');

        var params = {
            sg1: sg1,
            type: $that.is(':checked') ? 1 : 0
        }
        var title = $that.closest('a').children('span').html();

        var url = $that.parents('[data-url]').data('url');
        $spinner1.show();
        $.ajax({
            url: url,
            dataType: 'json',
            data:params,
            success: function( data ) {
                if (data.error) {
                    addGritter(title, tt.error, 'error')
                } else {
                    addGritter(title, tt.success, 'success')
                }
                $spinner1.hide();
            },
            error: function( data ) {
                addGritter(title, tt.error, 'error');
                $spinner1.hide();
            }
        });
    });
});