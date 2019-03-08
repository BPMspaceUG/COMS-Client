$(document).ready( function () {
    $('.modal').modal({
        backdrop: 'static',
        keyboard: false
    });

    $('#liam2_add_another_email').click(function(){
        $('#liam2_add_another_email_form').submit();
    })
});