$(document).ready(function(){

    $('#id-input-file-3').ace_file_input({
        style:'well',
        btn_choose:'Drop files here or click to choose',
        btn_change:null,
        no_icon:'icon-cloud-upload',
        droppable:true,
        thumbnail:'small'
        //,icon_remove:null//set null, to hide remove/reset button
        /**,before_change:function(files, dropped) {
						//Check an example below
						//or examples/file-upload.html
						return true;
					}*/
        /**,before_remove : function() {
						return true;
					}*/
        ,
        preview_error : function(filename, error_code) {
            //name of the file that failed
            //error_code values
            //1 = 'FILE_LOAD_FAILED',
            //2 = 'IMAGE_LOAD_FAILED',
            //3 = 'THUMBNAIL_FAILED'
            console.log(error_code);
        }

    }).on('change', function(){
        //console.log($(this).data('ace_input_files'));
        //console.log($(this).data('ace_input_method'));
    });

    $(document).on('submit', '#fill-data-form, #attach-file-form', function(){

        var $that   = $(this);
        var $inputs = $('#filter-form select');

        $inputs.each(function(i, el){
            $that.append( $(el).clone() )
        });

        return true;
    });

    $('.autocomplete').autocomplete({
        serviceUrl: $('[data-autocompleteurl]').data('autocompleteurl'),
        minChars:2,
        delimiter: /(,|;)\s*/, // regex or character
        maxHeight:300,
        width:'auto',
        zIndex: 9999,
        deferRequestBy: 0, //miliseconds
        params: { }, //aditional parameters
        noCache: true, //default is false, set to true to disable caching
        // callback function:
        onSelect: function(obj){
            var url = $('[data-updatenkrs]').data('updatenkrs');
            var post = {
                'field' : 'nkrs6',
                'value' : obj.p1,
                'nkrs1' : $(this).parent().data('nkrs1'),
                'st1' : $(this).parent().data('st1'),
                'us1' : $(this).parent().data('us1')
            };

            $.post(url, post, function(data){
                if (data.res)
                    addGritter('', 'Имя руководителя было изменено!', 'success')
                else
                    addGritter('', 'Произошла ошибка!', 'error')
            }, 'json')
        }
    });

    $(document).on('change', '[name=nkrs7]', function(){

        var $that = $(this);

        var url = $('[data-updatenkrs]').data('updatenkrs');
        var post = {
            'field' : 'nkrs7',
            'value' : $that.val(),
            'nkrs1' : $(this).parent().data('nkrs1'),
            'st1' : $(this).parent().data('st1'),
            'us1' : $(this).parent().data('us1')
        };

        /*$.post(url, post, function(data){
            if (data.res) {
                addGritter('', 'Название курсовой было изменено!', 'success');
                $('#nkrs7-eng').val(post.value);
            } else
                addGritter('', 'Произошла ошибка!', 'error')
        }, 'json')*/

        $.ajax({
            url: url,
            dataType: 'json',
            data:post,
            success: function( data ) {
                if (data.res) {
                    addGritter('', 'Название курсовой было изменено!', 'success');
                    $('#nkrs7-eng').val(post.value);
                } else
                    addGritter('', 'Произошла ошибка!', 'error')
            },
            error: function(jqXHR, textStatus){
                if (jqXHR.status == 500) {
                    addGritter('', 'Internal error 500: ' + jqXHR.responseText, 'error')
                } else {
                    if (jqXHR.status == 403) {
                        addGritter('', 'Access error 403: ' + jqXHR.responseText, 'error')
                    } else {
                        addGritter('', 'Unexpected error '+ jqXHR.status +': '+jqXHR.responseText, 'error')
                    }
                }

            }
        });
    });
    
    $(document).on('click', '.show-passport', function(){

        var $that = $(this);
        var url = $that.data('url');
        var data = {
            psp1 : $that.data('psp1'),
            type : $that.data('type')
        };
        
        $.ajax({
            url: url,
            dataType: 'json',
            data: data,
            success: function(html){
                if (!html.error) {
                    $('#myModal #modal-content').html(html.html);
                    $('#myModal .modal-header h4').html(html.title);
                    $('#myModal').modal('show');
                }else
                    addGritter('', 'Произошла ошибка!', 'error')
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
                
            }
          });
       
    });

    $(document).on('click', '.btn-add-spkr', function(){

        var $that = $(this);
        var url = $that.data('url');

        $.ajax({
            url: url,
            dataType: 'json',
            success: function(html){
                if (!html.error) {
                    $('#myModal #modal-content').html(html.html);
                    $('#myModal .modal-header h4').html(html.title);
                    $('#myModal').modal('show');
                }else
                    addGritter('', 'Произошла ошибка!', 'error')
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

            }
        });

    });

    $(document).on('click', '#spkr-form a', function(){

        var $that = $(this);

        var url =$that.parents('form').attr('action');

        var data = {
            spkr2 : $('#Spkr_spkr2').val(),
            spkr3 : $('#Spkr_spkr3').val()
        };
        $('#myModal').modal('hide');
        $.ajax({
            url: url,
            dataType: 'json',
            data:data,
            success: function(html){
                if (!html.error) {
                    addGritter('', 'Сохранено!', 'success');
                    $('#filter-form').submit();
                }else
                    addGritter('', 'Произошла ошибка!', 'error')
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

            }
        });

    });
    
    $(document).on('click', '.change-passport', function(){

        var $that = $(this);
        var url = $that.data('url');
        var data = {
            psp1 : $that.data('psp1'),
            type : $that.data('type')
        };
        
        $.ajax({
            url: url,
            dataType: 'json',
            data: data,
            success: function(html){
                if (!html.error) {
                    //alert(html.html);
                    $('#myModal #modal-content .block').append(html.html);
                    $('#myModal .modal-header h4').html(html.title);
                    $('#myModal').modal('show');
                }else
                    addGritter('', 'Произошла ошибка!', 'error')
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
                
            }
          });
       
    });
    
    $(document).on('click', '.delete-passport', function(){

        var $that = $(this);
        var url = $that.data('url');
        var data = {
            psp1 : $that.data('psp1'),
            type : $that.data('type')
        };
        
        $.ajax({
            url: url,
            dataType: 'json',
            data: data,
            success: function(html){
                if (!html.error) {
                    addGritter('', 'Успешно!', 'success')
                    location.reload();
                }else
                    addGritter('', 'Произошла ошибка!', 'error')
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
                
            }
          });
       
    });
});