$(document).ready( function () {
    $('#main_modal').modal({
        backdrop: 'static',
        keyboard: false
    });

    var main_table = $('.main-table').DataTable({
        paging: false,
        scrollY: 400,
        order: [4,'desc'],
        columnDefs: [{
            'targets'  : 'no-sort',
            'orderable': false
        }],
        language: {
            search: '',
            searchPlaceholder: 'Search'
        }
    });

    var not_main_table = $('.not-main-table').DataTable({
        paging: false,
        scrollY: 400,
        order: [0,'desc'],
        language: {
            search: '',
            searchPlaceholder: 'Search'
        }
    });

    $('.dataTables_wrapper').hide();
    $('#exam_event_table_wrapper').show();
    $('.table-switch-button').click(function () {
        if ($(this).attr('id') !== 'exam_event') {
            $('.events-nav').hide();
        } else {
            $('.events-nav').show();
        }
        $.fn.dataTableExt.afnFiltering.length = 0;
        main_table.draw();
        not_main_table.draw();
        $('.dataTables_wrapper').hide();
        $('#' + $(this).attr('id') + '_table_wrapper').show();
        $('.table-switch-button').removeClass('active');
        $(this).addClass('active');
        $('.events-buttons a').removeClass('active');
        $('.events-buttons a#all').addClass('active');
        main_table.columns.adjust();
        not_main_table.columns.adjust();
    });

    $(document).on('shown.bs.modal', '#main_modal', function () {
        main_table.columns.adjust();
    });

    $('#future').on( 'keyup click', function () {
        $('.past-events').addClass('hide-past-events-separator');
        $('.events-buttons a').removeClass('active');
        $(this).addClass('active');
        $.fn.dataTableExt.afnFiltering.length = 0;
        main_table.draw();
        $.fn.dataTable.ext.search.push(
            function( settings, data, dataIndex ) {
                var future = new Date();
                var current_date = new Date(data[4]);
                if (current_date > future || future == null)
                {
                    return true;
                }
                return false;
            }
        );
        main_table.draw();
    });

    $('#past').on( 'keyup click', function () {
        $('.past-events').addClass('hide-past-events-separator');
        $('.events-buttons a').removeClass('active');
        $(this).addClass('active');
        $.fn.dataTableExt.afnFiltering.length = 0;
        main_table.draw();
        $.fn.dataTable.ext.search.push(
            function( settings, data, dataIndex ) {
                var past = new Date();
                var current_date = new Date(data[4]);
                if (current_date < past || past == null)
                {
                    return true;
                }
                return false;
            }
        );
        main_table.draw();
    });

    $('#all').on( 'keyup click', function () {
        $('.events-buttons a').removeClass('active');
        $(this).addClass('active');
        $.fn.dataTableExt.afnFiltering.length = 0;
        main_table.draw();
        $('.past-events').removeClass('hide-past-events-separator');
    });

    $('.sort-by-date').click(function() {
        var past_events = $('.past-events');
        past_events.removeClass('row-border-none');
        past_events.toggleClass('row-border-top');
        past_events.toggleClass('row-border-bottom');
    });

    $('#exam_event_table_wrapper .sorting').not('.past-events').click(function(){
        var past_events = $('.past-events');
        if (!past_events.hasClass('row-border-none')) {
            past_events.addClass('row-border-none');
        }
    });

    $('.cancel-exam-event').click(function(){
        if (!confirm("Do you really want to cancel the event " + $(this).data('coms_exam_event_name'))){
            return false;
        }
        $.post("/inc/ajax_requests.php", {
            data: 'cancel-exam-event',
            exam_id: $(this).data('coms_exam_event_id')
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
                alert('The exam event was successfully canceled.');
                location.reload();
            }
        });
    });

    $('.add-new-exam-event a').click(function(){
        $('#create_event_modal').modal({
            backdrop: 'static',
            keyboard: false
        }).show();
        $('#main_modal').css('z-index', 1039);
    });

    $('.cancel').click(function(){
        $('#create_event_modal').modal('hide');
    });

    $('#create_event_modal').on('hidden.bs.modal', function () {
        $('body').addClass('modal-open');
        $('#main_modal').css('z-index', 1041);
    });

    $('#select_exam').change(function(){
        var exam_id = $(this).val();
        var data = "<option value='' selected disabled >Please select</option>";
        $.each(trexor_json, function(index, value){
            if (value.coms_exam_id === exam_id) {
                data += "<option value='" + value.coms_trainer_id + "'>" + value.coms_trainer_lastname + " " + value.coms_trainer_firstname + "</option>";
            }
        });
            var trainer_element = $('#select_trainer');
            var trainer_element_value = trainer_element.val();
            //if (!trainer_element.val()) {
            trainer_element.empty();
            trainer_element.append(data);
            //}
            trainer_element.val(trainer_element_value);
        });

    $('#select_trainer').change(function(){
        var trainer_id = $(this).val();
        var data = "<option value='' selected disabled >Please select</option>";
        $.each(trexor_json, function(index, value){
            if (value.coms_trainer_id === trainer_id) {
                data += "<option value='" + value.coms_exam_id + "'>" + value.coms_exam_name + "</option>";
            }
        });
        var exam_element = $('#select_exam');
        var exam_element_value = exam_element.val();
        //if (!exam_element.val()) {
        exam_element.empty();
        exam_element.append(data);
        //}
        exam_element.val(exam_element_value);
    });

    $('.reset').click(function(){
        $('#create_event').trigger("reset");
        var exam_element = $('#select_exam');
        exam_element.empty();
        var exams = "<option value=''>Please select</option>";
        $.each( exams_json, function( i, val ) {
            exams += "<option value='" + val['coms_exam_id'] + "'>" + val['coms_exam_name'] + "</option>";
        });
        exam_element.append(exams);

        var trainer_element = $('#select_trainer');
        trainer_element.empty();
        var trainers = "<option value=''>Please select</option>";
        $.each( trainers_json, function( i, val ) {
            trainers += "<option value='" + val['coms_trainer_id'] + "'>" + val['coms_trainer_firstname'] + " " + val['coms_trainer_lastname'] + "</option>";
        });
        trainer_element.append(trainers);
    });

    $('.edit-exam-event').click(function(){
        $.post("/inc/ajax_requests.php", {
            data: 'edit-exam-event',
            exam_event_id: $(this).data('coms_exam_event_id'),
            trexor: trexor_json,
            proctors: proctors_json
        },function(data) {
            if (!data) {
                alert('Incorrect Exam Event');
            } else {
                $('body').append(data);
                $('#edit_event_modal').on('show.bs.modal', function () {
                    $('#main_modal').css('z-index', 1039);
                });
                $('#edit_event_modal').modal({
                    backdrop: 'static',
                    keyboard: false
                }).show();
                $('.cancel').click(function () {
                    $('#edit_event_modal').remove();
                    $('#main_modal').css('z-index', 1041);
                    $('.modal-backdrop').last().remove();
                });
                $('.add-info').click(function () {
                    $('#add_info_modal').modal({
                        backdrop: 'static',
                        keyboard: false
                    }).show();
                });
                $('.submit-add-info').click(function () {
                    if ($('.added-info').val() !== '') {
                        var date = new Date($.now());
                        var old_info = $('#edit_event_modal .exam-event-info').text();
                        $('#edit_event_modal .exam-event-info').empty();
                        $('#edit_event_modal .exam-event-info').append(date.toLocaleString() + ":<hr>\r\n" + $('.added-info').val() + "<hr>\r\n" + old_info)
                    }
                    $('#add_info_modal').modal('hide');
                    $('.added-info').val('');
                });
                $('.cancel-add-info').click(function () {
                    $('#add_info_modal').modal('hide');
                    $('.added-info').val('');
                });
                $('#add_info_modal').on('hidden.bs.modal', function () {
                    $('body').addClass('modal-open');
                });
            }
        });
    });
});