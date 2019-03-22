/**
 * Created by Neff on 09.02.2016.
 */
$(document).ready(function() {
    initSelect('');

    initDialogSettings();

    $(window).on('load', function () {
        var $preloader = $('#page-preloader'),
            $spinner   = $preloader.find('.spinner-preloader');
        $spinner.delay(1000).fadeOut('slow');
        $preloader.delay(500).fadeOut('slow');
    });

    $('.cs-options li').click(function () {
        $("html, body").animate({ scrollTop: 0 }, "slow");
    });
});

/*function addGritter(title, text, className)
{
    obj = {
        title: title,
        text: text,
        class_name: 'gritter-'+className
    }
    $.gritter.add(obj)
}*/

function addNoty(title, text, className){
    var n = noty({
        text        : text +'</br>'+title,
        type        : className,
        dismissQueue: true,
        modal       : false,
        layout      : 'topRight',
        closeWith   : ['click'],
        theme       : 'relax',
        maxVisible  : 4,
        timeout     : 2000,
        animation   : {
            open  : 'animated bounceInRight',
            close : 'animated bounceOutRight',
            easing: 'swing',
            speed : 500
        }
    });
    console.log('html: ' + n.options.id);
}

function initDialogSettings()
{
    if ($.ui != undefined)
        $.widget("ui.dialog", $.extend({}, $.ui.dialog.prototype, {
            _title: function(title) {
                var $title = this.options.title || '&nbsp;'
                if( ("title_html" in this.options) && this.options.title_html == true )
                    title.html($title);
                else title.text($title);
            }
        }));
}

function initSelect(id)
{
    (function() {
        [].slice.call( document.querySelectorAll( id+' select.cs-select' ) ).forEach( function(el) {
            new SelectFx(el, {
                onChange: function(elem,val) {
                    var id = elem.getAttribute('id');
                    //$('#'+id).val(val);
                    $('#'+id+' option[value="'+val+'"]').attr('selected','selected');
                    $('#'+id).change();
                }
            });
        } );
    })();
}

function initFilterForm($spinner)
{
    $(document).on('change', '#timeTable-form select:not(:last), #filter-form fieldset:not(.not-submit) select:not(:last)', function(){
        $spinner.show();

        var $form  = $(this).parents('form');
        var formId = $form.attr('id');
        var url    = $form.attr('action');
        $('#filter-form fieldset:not(.not-submit) select:last').val('0');

        $.get(url, $form.serialize(), function(data){

            var html = $('#'+formId, data).html();
            $('div', $form).replaceWith(html);
            initSelect('#'+formId)
            $spinner.hide();
        })

    });

    $(document).on('change', '#timeTable-form select:last, #filter-form fieldset:not(.not-submit) select:last', function(){
        $(this).parents('form').submit();
    });
}