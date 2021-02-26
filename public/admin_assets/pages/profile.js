

 $('#option_day').show();
    $('#option_week').hide();
    $('#option_month').hide();

$(document).on('change',"#repeat_value",function(){

     $(this).find('option').removeAttr("selected");
     $("#repeat_value").find('option').removeAttr("selected");

    var options = $(this).find("option:selected");
      
    if(options.val() == 'month'){
        $('#eoption_day').css('display', 'none');
        $('#eoption_week').css('display', 'none');
        $('#eoption_month').css('display', 'block');
        $('#repeat_option option[value=1]').attr('selected','selected');

        $('#option_day').hide();
        $('#option_week').hide();
        $('#option_month').show();
    }

    if(options.val() == 'day'){
        $('#eoption_day').css('display', 'block');
        $('#eoption_week').css('display', 'none');
        $('#eoption_month').css('display', 'none');
        $('#repeat_option option[value=1]').attr('selected','selected');

        $('#option_day').show();
        $('#option_week').hide();
        $('#option_month').hide();
    }

    if(options.val() == 'week'){

        $('#eoption_day').css('display', 'none');
        $('#eoption_week').css('display', 'block');
        $('#eoption_month').css('display', 'none');
        $('#repeat_option option[value=1]').attr('selected','selected');

        $('#option_day').hide();
        $('#option_week').show();
        $('#option_month').hide();
    }

});