<?php
session_start();
if ($_POST['data'] == 'cancel-exam-event') {
    require_once(__DIR__ . '/api.secret.inc.php');
    require_once(__DIR__.'/api.inc.php');
    $exam_event_id = htmlspecialchars($_POST['exam_id']);
    $check_id = json_decode(api(array("cmd" => "read", "paramJS" => array("table" => "v_coms_trainingorg_exam_events", "where" => "a.coms_exam_event_id = $exam_event_id && a.coms_training_org_id = $_SESSION[user_id]"))), true);
    if (!$check_id) {
        $result = 'Incorrect Exam Event';
    } else {
        $result = api(array(
            "cmd" => "makeTransition",
            "paramJS" => array("table" => 'coms_exam_event',
                "row" => array(
                    "coms_exam_event_id" => $exam_event_id,
                    "state_id" => 39,
                    "confirmcancel" => "plsdie"
                )
            )
        ));
    }
    echo $result;
}
if ($_POST['data'] == 'edit-exam-event') {
    require_once(__DIR__ . '/api.secret.inc.php');
    require_once(__DIR__ . '/api.inc.php');
    $exam_event_id = htmlspecialchars($_POST['exam_event_id']);
    $exam_event_data = json_decode(api(array("cmd" => "read", "paramJS" => array("table" => "v_exam_event__exam__trainingorg__trainer", "where" => "a.coms_exam_event_id = $exam_event_id && a.coms_training_org_id = $_SESSION[user_id]"))), true);
    if ($exam_event_data) {
        $exam_id = $exam_event_data[0]['coms_exam_id'];
        $trainer_id = $exam_event_data[0]['coms_trainer_id'];
        $proctor_id = $exam_event_data[0]['coms_proctor_id'];
        $proctors = $_POST['proctors'];
        $location = $exam_event_data[0]['coms_exam_event_location'];
        $trexor = $_POST['trexor'];
        $trainers = array();
        foreach ($trexor as $value) {
            if ($value['coms_exam_id'] == $exam_id) {
                array_push($trainers, $value);
            }
        }
        $state = $exam_event_data[0]['event_state_name'];
        $state_id = $exam_event_data[0]['event_state_id'];
        $event_date = $exam_event_data[0]['coms_exam_event_start_date'];
        $exam_event_info = $exam_event_data[0]['coms_exam_event_info'];
        require_once($_SERVER['DOCUMENT_ROOT'] . '/templates/edit_exam_event.phtml');
    }
}
if ($_POST['data'] == 'show-participation-list') {
    require_once(__DIR__ . '/api.secret.inc.php');
    require_once(__DIR__ . '/api.inc.php');
    $exam_event_id = htmlspecialchars($_POST['exam_event_id']);
    $exam_event = json_decode(api(array("cmd" => "read", "paramJS" => array("table" => "v_exam_event__exam__trainingorg__trainer", "where" => "a.coms_training_org_id = $_SESSION[user_id] && coms_exam_event_id = $exam_event_id"))), true);
    if ($exam_event && isset($exam_event[0]['coms_exam_event_name'])) {
        $heading = "manage Participants for " . $exam_event[0]['coms_exam_event_name'] . " - " . $exam_event[0]['event_state_name'];
    } else {
        $heading = 'manage Participants';
    }
    $participants = json_decode(api(array("cmd" => "read", "paramJS" => array("table" => "v_coms_participant__exam_event", "where" => "coms_training_org_id = $_SESSION[user_id] && coms_exam_event_id = $exam_event_id"))), true);
    require_once($_SERVER['DOCUMENT_ROOT'] . '/templates/participation.php');
}