$(document).ready( function () {
    $('#main_participant_modal').modal({
        backdrop: 'static',
        keyboard: false
    });
    $('#participant_change_language_button').click(function() {
        $('#main_participant_modal').css('z-index', 1039);
        $('#change_language_modal').modal({
            backdrop: 'static',
            keyboard: false
        });
    });
    $('.cancel').click(function(){
        $(this).closest('.modal').modal('hide');
        $('#main_participant_modal').css('z-index', 1041);
    })
});