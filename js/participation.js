$(document).ready(function(){
    $('.show-participation-list').click(function(){
        $.post("/inc/ajax_requests.php", {
            data: 'show-participation-list',
            exam_event_id: $(this).data('coms_exam_event_id')
        },function(data) {
            if (!data) {
                alert('Incorrect Exam Event');
            } else {
                $('#main_modal').modal('hide');
                $('body').append(data);
                var participation_list_table = $('.participation-list-table').DataTable({
                    paging: false,
                    scrollY: 400,
                    order: [1,'desc'],
                    columnDefs: [{
                        'targets'  : 'no-sort',
                        'orderable': false
                    }],
                    language: {
                        search: '',
                        searchPlaceholder: 'Search'
                    }
                });
                $('#show_participation_list_modal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $('.cancel').click(function () {
                    $('#show_participation_list_modal').remove();
                    $('.modal-backdrop').last().remove();
                    $('#main_modal').modal('show');
                });
                $(document).on('shown.bs.modal', '#show_participation_list_modal', function () {
                    participation_list_table.columns.adjust();
                    $('body').addClass('modal-open');
                });

                $('.cancel-participant-state').click(function(){
                    if (!confirm("Do you really want to cancel this participant?")){
                        return false;
                    }
                    $.post("/inc/ajax_requests.php", {
                        data: 'cancel-participant-state',
                        coms_participant_exam_event_id: $(this).data('coms_participant_exam_event_id')
                    },function(data) {
                        var is_json = true;
                        try
                        {
                            var json = $.parseJSON(data);
                        }
                        catch(err)
                        {
                            is_json = false;
                        }
                        if(!is_json) {
                            alert(data);
                        } else {
                            alert('The participant was successfully canceled.');
                            location.reload();
                        }
                    });
                });

                $('.additional-emails-button').hover(function(){
                    $(this).children('div').toggle();
                });

                $('.add-participant').click(function(){
                    $('#add_participant_modal').modal({
                        backdrop: 'static',
                        keyboard: false
                    }).show();
                    $('#show_participation_list_modal').css('z-index', 1039);
                });

                $('.cancel-participant').click(function(){
                    $('#add_participant_modal').modal('hide');
                });
                $('#add_participant_modal').on('hidden.bs.modal', function () {
                    $('body').addClass('modal-open');
                    $('#show_participation_list_modal').css('z-index', 1041);
                });
                $('.reset-participant').click(function(){
                    $('#create_participant').trigger("reset");
                });
                $('.create-participant').click(function(){
                    $.post("/inc/ajax_requests.php", {
                        data: 'check-participant-name',
                        firstname: $('#firstname').val(),
                        lastname: $('#lastname').val()
                    },function(data) {
                        if (data) {
                            if (!confirm("Participant with this firstname and lastname already exists. Do you wish to continue?")) {
                                return false;
                            }
                        }
                    });
                });

                $('.edit-participant').click(function(){
                    var participant_id = $(this).data('coms_participant_id');
                    $.post("/inc/ajax_requests.php", {
                        data: 'edit-participant',
                        participant_id: participant_id
                    },function(data) {
                        if (!data) {
                            alert('Incorrect Participant');
                        } else {
                            $('body').append(data);
                            $('#edit_participant_modal').on('show.bs.modal', function () {
                                $('#show_participation_list_modal').css('z-index', 1039);
                            });
                            $('#edit_participant_modal').modal({
                                backdrop: 'static',
                                keyboard: false
                            }).show();
                            $('.cancel-participant').click(function () {
                                $('#edit_participant_modal').remove();
                                $('#show_participation_list_modal').css('z-index', 1041);
                                $('.modal-backdrop').last().remove();
                            });
                            $('.edit-participant-button').click(function(){
                                $.post("/inc/ajax_requests.php", {
                                    data: 'check-participant-name',
                                    participant_id: participant_id,
                                    firstname: $('#edit_participant #firstname').val(),
                                    lastname: $('#edit_participant #lastname').val()
                                },function(data) {
                                    if (data) {
                                        if (!confirm("Participant with this firstname and lastname already exists. Do you wish to continue?")) {
                                            return false;
                                        }
                                    }
                                    $('#edit_participant').submit();
                                });
                            });
                        }
                    });
                });

                $('.search-participant').click(function(){
                    /*$.post("/inc/ajax_requests.php", {
                        data: 'search-participant',
                        exam_event_id: $(this).data('coms_exam_event_id'),
                        all_participants: all_participants
                    },function(data) {
                        if (!data) {
                            alert('Incorrect Participant');
                        } else {*/

                    var exam_event_id = $(this).data('coms_exam_event_id');
                    var exam_event_name = $(this).data('coms_exam_event_name');
                    var exam_event_state_name = $(this).data('coms_exam_event_state_name');
                    $.post("/inc/ajax_requests.php", {
                        data: 'search-participant',
                        exam_event_id: exam_event_id
                    },function(data) {
                        var data = JSON.parse(data);
                        var data2 = "<div class='modal fade' id='search_participant_modal' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>" +
                            "    <div class='modal-dialog modal-dialog-centered modal-sm' role='document'>" +
                            "        <div class='modal-content'>" +
                            "            <h3>add existing Participants for " + exam_event_name + " - " + exam_event_state_name + "</h3>" +
                            "            <form method='post' action='' id='add_existing_participant' class='needs-validation'>" +
                            "                <table id='search_participants' class='coms-js-table participants-search-table'>" +
                            "                    <thead>" +
                            "                        <tr>" +
                            "                            <th class='no-sort'></th>" +
                            "                            <th>Matricle ID</th>" +
                            "                            <th>Lastname</th>" +
                            "                            <th>Firstname</th>" +
                            "                            <th>E-Mail</th>" +
                            "                            <th>Date of Birth</th>" +
                            "                            <th>Place of Birth</th>" +
                            "                            <th>Country of Birth</th>" +
                            "                            <th>state_person</th>" +
                            "                        </tr>" +
                            "                    </thead>" +
                            "                    <tbody>";
                        $.each(all_participants, function(index, value){
                            if ($.inArray(value['coms_participant_id'], data) == -1) {
                                data2 += "<tr>" +
                                    "   <td><input type='checkbox' name='check_participant[]' value='" + value['coms_participant_id'] + "'/></td>" +
                                    "   <td>" + value['coms_participant_matriculation'] + "</td>" +
                                    "   <td>" + value['coms_participant_lastname'] + "</td>" +
                                    "   <td>" + value['coms_participant_firstname'] + "</td>" +
                                    "   <td>" + value['coms_participant_emailadresss'] + "</td>" +
                                    "   <td>" + value['coms_participant_dateofbirth'] + "</td>" +
                                    "   <td>" + value['coms_participant_placeofbirth'] + "</td>" +
                                    "   <td>" + value['coms_participant_birthcountry'] + "</td>" +
                                    "   <td>" + value['participation_state_name'] + "</td>" +
                                    "</tr>";
                            }
                        });
                        data2 += "</tbody>" +
                            "   </table>" +
                            "   <div class='form-group row'>" +
                            "       <div class='col-lg-6'></div>" +
                            "       <div class='col-lg-6'>" +
                            "           <button class='btn btn-primary' type='submit' name='add_existing_particiant'>Add</button>" +
                            "           <button type='button' class='btn cancel-existing-participant'>Cancel</button>" +
                            "           <button type='button' class='btn reset-existing-participant'>Reset</button>" +
                            "       </div>" +
                            "   </div>" +
                            "   <input type='hidden' name='exam_event_id' value='" + exam_event_id + "'/>" +
                            "   </form>" +
                            "   </div>" +
                            "   </div>" +
                            "</div>";

                        $('body').append(data2);
                        var search_participants_table = $('.participants-search-table').DataTable({
                            paging: false,
                            scrollY: 400,
                            order: [1,'desc'],
                            columnDefs: [{
                                'targets'  : 'no-sort',
                                'orderable': false
                            }],
                            language: {
                                search: '',
                                searchPlaceholder: 'Search'
                            }
                            });
                        $('#search_participant_modal').on('show.bs.modal', function () {
                            $('#show_participation_list_modal').css('z-index', 1039);
                            participation_list_table.columns.adjust();
                        });
                        $('#search_participant_modal').modal({
                            backdrop: 'static',
                            keyboard: false
                        }).show();
                        $(document).on('shown.bs.modal', '#search_participant_modal', function () {
                            search_participants_table.columns.adjust();
                        });
                        $('.cancel-existing-participant').click(function () {
                            $('#search_participant_modal').remove();
                            $('#show_participation_list_modal').css('z-index', 1041);
                            $('.modal-backdrop').last().remove();
                        });
                        $('.reset-existing-participant').click(function(){
                            $('#add_existing_participant').trigger("reset");
                        });
                    });
                });
                $('.anonymous-exams').click(function(){
                    if ($('.number-of-anonymous-exams').val() > 3) {
                        $('#anonymous_exams').text("Please provide us with " + $('.number-of-anonymous-exams').val() + " anonymous exams (Dummy Exam)");
                        var date = new Date($.now());
                        $('#anonymous_exams_date').val(date.toLocaleString());
                        $('#add_anonymous_exams_modal').modal({
                            backdrop: 'static',
                            keyboard: false
                        }).show();
                        $('#show_participation_list_modal').css('z-index', 1039);
                        $('.cancel-anonymous-exam').click(function () {
                            $('#add_anonymous_exams_modal').modal('hide');
                            $('#show_participation_list_modal').css('z-index', 1041);
                        });
                    }
                });
                $('.import-button').click(function() {
                    var exam_event_id = $(this).data('coms_exam_event_id');
                    $('#import_file_modal .alert-danger').remove();
                    $('#import_file_modal').modal({
                        backdrop: 'static',
                        keyboard: false
                    }).show();
                    $('#show_participation_list_modal').css('z-index', 1039);
                    $('.cancel-import').click(function(){
                        $('#import_file_modal').modal('hide');
                        $('#show_participation_list_modal').css('z-index', 1041);
                    });
                    $('#import_file_modal').on('hidden.bs.modal', function () {
                        $('body').addClass('modal-open');
                    });
                    $('#save_import_csv').click(function(e) {
                        e.stopImmediatePropagation();
                        if(!$('#import_csv').val()) {
                            return;
                        }
                        var formData = new FormData();
                        formData.append('file', $('#import_csv').prop('files')[0]);
                        $.ajax({
                            url : '/inc/ajax_requests.php',
                            type : 'POST',
                            data : formData,
                            processData: false,
                            contentType: false,
                            success : function(data) {
                                if (!data) {
                                    $('#import_file_modal h3').before("<div class='alert alert-danger' role='alert'>Incorrect file.</div>");
                                } else {
                                    $('body').append(data);
                                    $('#import_participants_modal').modal({
                                        backdrop: 'static',
                                        keyboard: false
                                    }).show();
                                    $('#show_participation_list_modal').css('z-index', 1039);
                                    $('.cancel-import-window').click(function () {
                                        $('#import_participants_modal').remove();
                                        $('.modal-backdrop').last().remove();
                                        $('#import_file_modal').modal('hide');
                                        $('#show_participation_list_modal').css('z-index', 1041);
                                    });
                                    $('.deselect-all').change(function () {
                                        if ($(this).is(':checked')) {
                                            $(this).parent().next('form').find('input').prop("checked", false).trigger('change');
                                        }
                                    });
                                    $('.select-all').change(function () {
                                        if ($(this).is(':checked')) {
                                            $(this).parent().next('form').find('input').prop("checked", true).trigger('change');
                                        }
                                    });
                                    $('.create-participant-from-import-button').click(function() {
                                        var json_items = Array();
                                        $.each($('.create-check-participant:checked'), function(index, value){
                                            json_items.push(jQuery.parseJSON($(value).val()));
                                        });
                                        $.post("/inc/ajax_requests.php", {
                                            data: 'create-participant-from-csv',
                                            items: JSON.stringify(json_items),
                                            exam_event_id: exam_event_id
                                        },function(data) {
                                            if (data) {
                                                if (data == 'success') {
                                                    $.each($('.create-check-participant:checked'), function (index, value) {
                                                        $(value).parent('div').remove();
                                                    });
                                                    $('#import_participants_modal h3').before("<div class='alert alert-success' role='alert'>The selected participants have been successfully created and added to the exam event.</div>");
                                                    if ($('#create_participant_form_csv').find('.create-check-participant').length < 1) {
                                                        $('.create-records-container').remove();
                                                    }
                                                } else {
                                                    $('#import_participants_modal h3').before("<div class='alert alert-danger' role='alert'>" + data + "</div>");
                                                }
                                            }
                                        });
                                    });
                                    $('.book-participant-from-import-button').click(function() {
                                        var json_items = Array();
                                        $.each($('.book-check-participant:checked'), function(index, value){
                                            json_items.push(jQuery.parseJSON($(value).val()));
                                        });
                                        $.post("/inc/ajax_requests.php", {
                                            data: 'book-participant-from-csv',
                                            items: JSON.stringify(json_items),
                                            exam_event_id: exam_event_id
                                        },function(data) {
                                            if (data) {
                                                if (data == 'success') {
                                                    $.each($('.book-check-participant:checked'), function (index, value) {
                                                        $(value).parent('div').remove();
                                                    });
                                                    $('#import_participants_modal h3').before("<div class='alert alert-success' role='alert'>The selected participants have been successfully added to the exam event.</div>");
                                                    if ($('#book_participant_form_csv').find('.book-check-participant').length < 1) {
                                                        $('.book-records-container').remove();
                                                    }
                                                } else {
                                                    $('#import_participants_modal h3').before("<div class='alert alert-danger' role='alert'>" + data + "</div>");
                                                }
                                            }
                                        });
                                    });
                                }
                            }
                        });
                    });
                });
            }
        });
    });
});