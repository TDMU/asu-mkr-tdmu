$(document).ready(function(){
    $spinner1 = $('#spinner1');
    $spinner2 = $('#spinner2');

    initSpinner('spinner2');

    initFilterForm($spinner1);

    $('#thematic-print').click(function(){
        /*$("#filter-form").attr("action", $(this).data('url'));
         $("#filter-form").submit();*/
        var action=$("#filter-form").attr("action");
        $("#filter-form").attr("action", $(this).data('url'));
        $("#filter-form").submit();
        $("#filter-form").attr("action", action);
    });

   /* function appendSelect()
    {
        var $select = $('#FilterForm_discipline').clone();
        var $form   = $('#filter-form');

        $select.find('option[value='+$('#FilterForm_discipline').val()+']').attr('selected', 'selected');

        $form.append($select);
    }*/

	//initDataTable('themes');
	
    $('#themes').on('click','.delete-new-theme',function(e){
		var elem=$(this).parents('.new-theme');
		elem.remove();
		e.preventDefault();
	});
        
	$('#themes').on('click','.save-new-theme',function(e){
        $(this).attr("disabled", true);
        var elem=$(this).parents('.new-theme');
        var elem3= elem.find(".ustem3");
        var elem5= elem.find(".ustem5");
		var us1	=$(this).parents('[data-us1]').data('us1');
		var url	=$(this).parents('[data-url]').data('url');
        var ustem3=elem3.val();
        var ustem4=elem.find(".ustem4").val();
        var ustem5=elem5.val();
        var ustem6=elem.find(".ustem6").val();
        var ustem7=elem.find(".ustem7").val();
        var ustem11=elem.find(".ustem11").val();
        var ustem7_text=elem.find('.ustem7 option:selected').text();
        var ustem6_text=elem.find('.ustem6 option:selected').text();
        var ustem11_text=elem.find('.ustem11 option:selected').text();

        var params = {
            us1 : us1,
            ustem3 : ustem3,
            ustem4 : ustem4,
            ustem5 : ustem5,
            ustem6 : ustem6,
            ustem7 : ustem7,
            ustem11 : ustem11
        }

        if (isNaN(params.ustem6)||params.ustem3==''||isNaN(params.ustem4)||params.ustem5=='') {
            elem3.removeClass('input-error');
            elem5.removeClass('input-error');
            addGritter('', tt.error, 'error');
            elem.addClass('error');
            $(this).attr("disabled", false);
            if(params.ustem3=='')
                elem3.addClass('input-error');
            if(params.ustem5=='')
                elem5.addClass('input-error');
            return false;
        }

         $spinner1.show()

        $.get(url, params, function(data){

            if (data.error) {
                var title=tt.error;
                if(data.errorType==1)
                    title=tt.errorUs6;
                if(data.errorType==3)
                    title=tt.errorAccess;
                addGritter('', title, 'error');
                elem.addClass('error');
                $(this).attr("disabled", false);
            } else {
                addGritter('', tt.success, 'success')
                elem.removeClass('error').addClass('success');
                setTimeout(function() { elem.removeClass('success') }, 3000);
                elem.html('<td class="td-ustem4">'+params.ustem4+'</td>'+
                    '<td>'+ustem11_text+'</td>'+
                    '<td>'+ustem7_text+'</td>'+
                    '<td>'+params.ustem3+'</td>'+
                    '<td>'+params.ustem5+'</td>'+
                    '<td>'+ustem6_text+'</td>'+
                    '<td>'+
                        '<a href="'+urlEdit+data.ustem1+'" class="edit-theme btn btn-mini btn-info">'+
                            '<i class="icon-edit bigger-120"></i>'+
                        '</a>'+
                        '<a href="'+urlDelete+data.ustem1+'" class="delete-theme btn btn-mini btn-danger">'+
                            '<i class="icon-trash bigger-120"></i>'+
                       ' </a>'+
                    '</td>');
                refreshPage = true;
            }

            $spinner1.hide();
        }, 'json');
	});
	
	$('[name=delete-thematic-plan]').click(function(e){

        e.preventDefault();

        $( "#dialog-confirm" ).dialog({
            resizable: false,
            modal: true,
            title: "<div class='widget-header'><h4 class='smaller'><i class='icon-warning-sign red'></i> Удалить тем. план?</h4></div>",
            title_html: true,
            buttons: [
                {
                    html: "<i class='icon-trash bigger-110'></i>&nbsp; Удалить",
                    "class" : "btn btn-danger btn-mini",
                    click: function() {
                        var $input = $('<input>', {
                            'type' : 'hidden',
                            'value': '1',
                            'name' : 'delete-thematic-plan'
                        })
                        //appendSelect();
                        $('#filter-form').append($input);
                        $('#filter-form').submit();
                    }
                }
                ,
                {
                    html: "<i class='icon-remove bigger-110'></i>&nbsp; Отмена",
                    "class" : "btn btn-mini",
                    click: function() {
                        $( this ).dialog( "close" );
                    }
                }
            ]
        });

    });


    $('[name=accept-thematic-plan]').click(function(e){

        e.preventDefault();

        $( "#dialog-confirm-accept" ).dialog({
            resizable: false,
            modal: true,
            title: "<div class='widget-header'><h4 class='smaller'><i class='icon-warning-sign blue'></i> Подтвердить тем. план?</h4></div>",
            title_html: true,
            buttons: [
                {
                    html: "<i class='icon-ok bigger-110'></i>&nbsp; Подтвердить",
                    "class" : "btn btn-primary btn-mini",
                    click: function() {
                        var $input = $('<input>', {
                            'type' : 'hidden',
                            'value': '1',
                            'name' : 'accept-thematic-plan'
                        })
                        //appendSelect();
                        $('#filter-form').append($input);
                        $('#filter-form').submit();
                    }
                }
                ,
                {
                    html: "<i class='icon-remove bigger-110'></i>&nbsp; Отмена",
                    "class" : "btn btn-mini",
                    click: function() {
                        $( this ).dialog( "close" );
                    }
                }
            ]
        });

    });

	function getNew(number)
	{
            return '<tr class="new-theme">'+
                //'<td><input type="number" class="ustem4" value="'+(number)+'"></td>'+
                '<td class="wd-20"><input type="number" disabled class="ustem4 " value="'+(number)+'"/></td>'+
                '<td>'+selectUstem11+'</td>'+
                '<td class="wd-25">'+selectUstem7+'</td>'+
                '<td><input type="number" class="ustem3"/></td>'+
                '<td><input type="text" class="ustem5" maxlength="'+ustem5Lenght+'"/></td>'+
                '<td>'+selectUstem6+'</td>'+
                '<td>'+
                    '<a class="save-new-theme btn btn-mini btn-success"><i class="icon-ok bigger-120"></i></a>'+
                    '<a class="delete-new-theme btn btn-mini btn-warning"><i class="icon-trash bigger-120"></i></a>'+
                '</td>'+
            '</tr>';
	}
	
	$('[name=add-thematic-plan]').click(function(e){

        e.preventDefault();
		$( "#dialog-confirm-add" ).dialog({
            resizable: false,
            modal: true,
            title: "<div class='widget-header'><h4 class='smaller'><i class='icon-question-sign green'></i> Добавить тем. план?</h4></div>",
            title_html: true,
            buttons: [
                {
                    html: "<i class='icon-plus bigger-110'></i>&nbsp; Добавить",
                    "class" : "btn btn-success btn-mini",
                    click: function() {
						var count=$('#count-rows').val();
                        var us6=$('#us6-rows').val();

                        var f_l=$('.td-ustem4:last').text();
                        var s_l=$('.new-theme .ustem4:last').val();
                        var l=0;
                        if(f_l!='')
                            l=f_l;
                        if(s_l!=''&&f_l<s_l)
                            l=s_l;
                        l=parseInt(l);

						if(count!=""){
							$('#dialog-confirm-add .count-rows-group').removeClass('error');
							$('#dialog-confirm-add .count-rows-group .help-inline').hide();
							for (var i = 1; i <= count; i++) {
							  $('#themes tbody').append(getNew(l+i));
							}
                            $('.select-new-ustem7').val(us6);
							$( this ).dialog( "close" );
						}else
						{
							$('#dialog-confirm-add .count-rows-group').addClass('error');
							$('#dialog-confirm-add .count-rows-group .help-inline').show();
						}
                    }
                }
                ,
                {
                    html: "<i class='icon-remove bigger-110'></i>&nbsp; Отмена",
                    "class" : "btn btn-mini",
                    click: function() {
						$('#count-rows').val('');
						$('#dialog-confirm-add .control-group').removeClass('error');
						$('#dialog-confirm-add .control-group .help-inline').hide();
                        $( this ).dialog( "close" );
                    }
                }
            ]
        });
    });
	
    $(document).on('click', '.delete-theme', function(){
        if (! confirm(tt.confirmDeleteMsg))
            return false;

        var url = $(this).attr('href')

        $.getJSON(url, {}, function(data){
            if (data.deleted === true) {
                window.location.reload();
            } else {
                console.log(1);
                addGritter('', 'error', 'error')
            }

            $spinner1.hide();
        })

        return false;
    });
	
    $(document).on('click', '.edit-theme', function(){
        var url = $(this).attr('href');

        $.getJSON(url, {}, function(data){
            $('#themes').append(data.html);
            initChosen();
            //$('#theme-form').find('div.chosen-container').attr('style', 'min-width:220px');
            $('#modal-table').modal('show');
        })

        return false;
    })

    $(document).on('click', '[name=paste-thematic-plan]', function(){
        var url = $(this).attr('href');

        $.getJSON(url, {}, function(data){
            $('#themes').append(data.html);
            initChosen();
            $('#theme-form').find('div.chosen-container').attr('style', 'min-width:220px');
            $('#modal-table').modal('show');
        })

        return false;
    })

    $(document).on('click', '[name=copy-thematic-plan]', function(e){

        e.preventDefault();
        $( "#dialog-confirm-copy" ).dialog({
            resizable: false,
            modal: true,
            width: 500,
            title: "<div class='widget-header'><h4 class='smaller'><i class='icon-question-sign green'></i> Копировать тем. план?</h4></div>",
            title_html: true,
            buttons: [
                {
                    html: "<i class='icon-ok bigger-110'></i>&nbsp; Копировать",
                    "class" : "btn btn-success btn-mini",
                    click: function() {
                        var url = $(this).data('action');
                        var params = {
                            us1 : $(this).data('us1'),
                            us1_2:$('#group-rows').val()
                        }
                        if(params.us1_2==null)
                        {
                            $('.group-rows-control').addClass('error');
                            return false;
                        }
                        $spinner1.show();
                        $( this ).dialog( "close" );
                        $.get(url, params, function(data){

                            if (data.error) {
                                var title=tt.error;
                                if(data.errorType==1)
                                    title=tt.errorUs6;
                                if(data.errorType==3)
                                    title=tt.errorAccess;
                                addGritter('', title, 'error');
                                //elem.addClass('error');
                                $(this).attr("disabled", false);
                            } else {
                                addGritter('', tt.success, 'success')
                                setTimeout(function() {$('#filter-form').submit(); }, 1000);
                                $('.group-rows-control').removeClass('error');
                            }
                            $spinner1.hide();
                        }, 'json');
                    }
                }
                ,
                {
                    html: "<i class='icon-remove bigger-110'></i>&nbsp; Отмена",
                    "class" : "btn btn-mini",
                    click: function() {
                        $( this ).dialog( "close" );
                    }
                }
            ]
        });
        return false;
    })

    $(document).on('hidden', '#modal-table', function (){
        $(this).remove();
    });

    $(document).on('click', '#save-theme', function(){
        //appendSelect();
        $('#theme-form').submit();
    });

    $(document).on('submit', '#theme-form', function(){

        var $form = $(this);
        var url   = $form.attr('action');

        $spinner1.show();

        $.getJSON(url, $form.serialize(), function(data){
            if (data.errors.length  === 0) {
                if(!data.error)
                {
                    //window.location = window.location.href+'?'+$('#filter-form').serialize();
                    $('#modal-table').modal('hide');
                    $('#filter-form').submit();
                }else
                {
                    var title=tt.error;
                    if(data.errorType==1)
                        title=tt.errorUs6;
                    if(data.errorType==3)
                        title=tt.errorAccess;
                    addGritter('', title, 'error')
                }
            } else {
                var html = $('form > div', data.html);
                $('div', $form).replaceWith(html);
            }

            $spinner1.hide();
        });

        return false;
    });

    $(document).on('change','#sem-rows,#year-rows',function(){
        var sem = $('#sem-rows').val();
        var year = $('#year-rows').val();
        var d1 = $('#copy-theme').data('d1');
        var url    = $('#copy-theme').data('action');
        var params = {
            sem_rows: sem,
            year_rows: year,
            d1:d1
        };
        $spinner2.show();

        $.get(url, params, function(data){
            //alert(data.tmp);
            $('#copy-theme').html(data.tmp);
            $spinner2.hide();
        },'json')
    });
});