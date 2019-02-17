$(document).ready(function(){
    
    $('#name_module').live('change',function(){
        var module_id = $(this).val();
        get_hook_by_module_id(module_id);
    })
    
    function get_hook_by_module_id(module_id) {
        $.ajax({
            type: 'POST',
            url:'../modules/labmanagerblocks/ajax.php',
            data: 'module_id='+module_id,
            dataType: 'json',
            success: function(json) {
                var obj = JSON.parse(json);
                var option = "";
                $.each(obj, function (index, value) {
                    var hook_id = value.id_hook
                    var hook_name = value.name;
                    option +="<option value='"+hook_id+"'>"+hook_name+"</option>";
                })
                if(option!=""){
                    $('#hook_module').html(option);
                }else {
                    option = "<option value=0>No Hook</option>";
                    $('#hook_module').html(option);
                }
            }
        });
    }
    
    if( $( "#active_off_module" ).attr('checked')=='checked'){

        $('#name_module').attr('disabled','disabled');
        $('#hook_module').attr('disabled','disabled');
    }
            
    $( "input[name$='insert_module']" ).bind('click',function(){
        var insert_module = $(this).val();
        if(insert_module==0) {
            $('#name_module').attr('disabled','disabled');
            $('#hook_module').attr('disabled','disabled');
        } else {
            $('#name_module').removeAttr('disabled');
            $('#hook_module').removeAttr('disabled');
        }
    })
    
    var module_id = $('#name_module').val();
        get_hook_by_module_id(module_id);
    var option = "<option value=0>No Hook</option>";
    //$('#hook_module').html(option);


})