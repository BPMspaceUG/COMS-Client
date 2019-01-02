<script src="//code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.css"/>
<script type="text/javascript" src="//cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.js"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<script src="//stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<link rel="stylesheet" href="//use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="/css/main.css"/>

<?php
session_start();
require_once(__DIR__.'/captcha/captcha.php');
$url_array = explode('/', $_SERVER['REQUEST_URI']);
if (count($url_array) < 2) {
    header('Location: //' . $_SERVER['SERVER_NAME'] . '/404.php');
    exit();
}
$url_array = array_reverse($url_array);
$PARTID = $url_array[0];
$PARTID_MD5 = $url_array[1];
if (isset($_POST['ato_logout'])) {
    session_destroy();
    header('Location: //' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
    exit();
}
if (isset($_POST['ato_login'])) {
    if (file_exists($_POST['captcha-image'])) unlink($_POST['captcha-image']);
    if (!isset($_POST['booking-pw'])) {
        $error = 'Please fill all the fields.';
    } else {
        $sentCode = htmlspecialchars($_POST["code"]);
        $result = (int)$_POST["result"];
        if (getExpressionResult($sentCode) !== $result) {
            $error = "Wrong Captcha.";
        } else {
            $bookingpw = hash("sha512", htmlspecialchars($_POST['booking-pw']) . $PARTID_MD5);
            $db_content = json_decode(api(array("cmd" => "read", "paramJS" => array("table" => "v_csvexport_trainingorg",
                "where" => "a.coms_training_organisation_passwd_hash = '$bookingpw' && a.coms_training_organisation_id = $PARTID"))), true);
            if ($db_content) {
                $_SESSION['ato_name'] = $db_content[0]['coms_training_organisation_name'];
                $_SESSION['user_id'] = $db_content[0]['coms_training_organisation_id'];
            } else {
                $error = 'Wrong password';
            }
        }
    }
}
if (isset($_POST['create_event'])) {
    if (!isset($_POST['select_exam']) || !isset($_POST['select_trainer']) ||  !isset($_POST['select_proctor']) || !isset($_POST['date']) ||  !isset($_POST['time']) ||  !isset($_POST['location'])) {
        $error = 'Please fill all the fields.';
    }
    if (!isset($error)) {
        $date = implode("-", array_reverse(explode(".", htmlspecialchars($_POST['date']))));
        $errr = json_decode(api(array("cmd" => "create", "paramJS" => array("table" => "coms_exam_event", "row" => array(
            "coms_exam_id" => htmlspecialchars($_POST['select_exam']),
            "coms_trainer_id" => htmlspecialchars($_POST['select_trainer']),
            "coms_training_org_id" => htmlspecialchars($PARTID),
            "coms_proctor_id" => htmlspecialchars($_POST['select_proctor']),
            "coms_exam_event_start_date" => $date . " " . htmlspecialchars($_POST['time']),
            "coms_exam_event_location" => htmlspecialchars($_POST['location']),
            "coms_exam_event_info" => htmlspecialchars($_POST['exam_event_info']),
            "coms_delivery_type_id" => "5")))), true);
        if (!$errr[0]['allow_transition']) {
            $error = $errr[0]['message'];
        } elseif (isset($errr[0]['errormsg'])) {
            $error = $errr[0]['errormsg'];
        }
    }
}
if (isset($_POST['edit_event'])) {
    if (!isset($_POST['edit_trainer']) || !isset($_POST['edit_proctor']) || !isset($_POST['edit_location']) || !isset($_POST['time'])) {
        $error = 'Please fill all the fields.';
    } else {
        $exam_event_id = htmlspecialchars($_POST['event_exam_id']);
        $edited_exam = json_decode(api(array("cmd" => "read", "paramJS" => array("table" => "v_coms_trainingorg_exam_events", "where" => "a.coms_exam_event_id = $exam_event_id && coms_training_org_id = $_SESSION[user_id]"))), true);
        if (!$edited_exam) {
            $error = 'You are trying to edit an incorrect Exam Event';
        } else {
            $date = htmlspecialchars($_POST['date']) . ' ' . htmlspecialchars($_POST['time']);
            $result = api(array(
                "cmd" => "makeTransition",
                "paramJS" => array("table" => 'coms_exam_event',
                    "row" => array(
                        "coms_exam_event_id" => $exam_event_id,
                        "coms_trainer_id" => htmlspecialchars($_POST['edit_trainer']),
                        "coms_proctor_id" => htmlspecialchars($_POST['edit_proctor']),
                        "coms_exam_event_location" => htmlspecialchars($_POST['edit_location']),
                        "coms_exam_event_info" => htmlspecialchars($_POST['exam_event_info']),
                        "coms_exam_event_start_date" => $date,
                        "state_id" => htmlspecialchars($_POST['state_id']),
                    )
                )
            ));
        }
    }
}

//check validity of URL
if (ctype_xdigit($PARTID_MD5) && strlen($PARTID_MD5) == 32 && strlen($PARTID) == 6 && $PARTID_MD5 == (md5($PARTID))) {
    // MatrNr Postfix calculate
    $PARTID_MD5_first5 = substr($PARTID_MD5, 0, 5);
    $matNr_postfix = substr(base_convert($PARTID_MD5_first5, 16, 10), 0, 3);
    //echo "PostFIX MatrkNr = ".$matNr_postfix."<br/>";
    $matNr = $PARTID.substr(base_convert($PARTID_MD5_first5, 16, 10), 0, 3);
    $matNr = str_pad(strtoupper(base_convert($matNr, 10, 32)), 8, "0", STR_PAD_LEFT);
    //echo $actionurl;
}
else {
    header('Location: //' . $_SERVER['SERVER_NAME'] . '/404.php');
    exit();
}

/**
 * @param $exam
 * @return bool
 */
function pastExams($exam) {
    return($exam['coms_exam_event_start_date'] < date('Y-m-d H:i:s'));
}

/**
 * @param $exam
 * @return bool
 */
function futureExams($exam) {
    return($exam['coms_exam_event_start_date'] >= date('Y-m-d H:i:s'));
}

/**
 * @param $booked_exams
 * @return string
 */
function examEventsOutput($booked_exams) {
    $past_exams = array_filter($booked_exams, 'pastExams');
    $future_exams = array_filter($booked_exams, 'futureExams');
    $output = "<div class='events-nav'><div class='add-new-exam-event'>
        <a href='#'><i class='fas fa-plus-circle fa-3x'></i></a>
        </div>
        <div class='events-buttons'>
        <a id='future' href='#'>Future (" . count($future_exams) . ")</a>
        <a id='past' href='#'>Past (" . count($past_exams) . ")</a>
        <a id='all' class='active' href='#'>All (" . count($booked_exams) . ")</a>
        </div></div>
        <table id='exam_event_table' class='coms-js-table main-table'><thead>
        <th class='no-sort'></th>
        <th class='no-sort'></th>
        <th>Event ID</th>
        <th>Event Name</th>
        <th class='sort-by-date'>Start Date</th>
        <th>Trainer Lastname</th>
        <th>Trainer Firstname</th>
        <th>Proctor Lastname</th>
        <th>Proctor Firstname</th>
        <th>Event State</th>
        <th class='no-sort'></th>
        </thead><tbody>";
        $first = true;
    foreach ($booked_exams as $booked_exam) {
        if ($booked_exam['coms_exam_event_start_date'] < date('Y-m-d H:i:s') && $first) {
            $past_event = true;
        } elseif (($booked_exam['coms_exam_event_start_date'] < date('Y-m-d H:i:s')) && !isset($past_event) && !$first) {
            $output .= "<tr class='row-border-top past-events'>";
            $past_event = true;
        } else {
            $output .= "<tr>";
        }
        if ($first) $first = false;
        $cancellable_states = array(
            33,
            34,
            147
        );
        $editable_states = array(
            33,
            34,
            35,
            36,
            37,
            147,
        );
        if (in_array($booked_exam['state_id'], $editable_states)) {
            $output .= "<td><a href='#' class='edit-exam-event' data-coms_exam_event_id='" . $booked_exam['coms_exam_event_id'] . "' data-coms_exam_event_state='" . $booked_exam['state'] . "'><i class='fas fa-edit'></i></a></td>";
        } else {
            $output .= "<td></td>";
        }
        $output .= "<td><a href='#' class='show-participation-list' data-coms_exam_event_id='" . $booked_exam['coms_exam_event_id'] . "'><i class='fas fa-th-list'></i></a></td>";
        $output .= "<td>" . $booked_exam['coms_exam_event_id'] . "</td>";
        $output .= "<td>" . $booked_exam['coms_exam_name'] . "</td>";
        $output .= "<td>" . $booked_exam['coms_exam_event_start_date'] . "</td>";
        $output .= "<td>" . $booked_exam['coms_trainer_lastname'] . "</td>";
        $output .= "<td>" . $booked_exam['coms_trainer_firstname'] . "</td>";
        $output .= "<td>" . $booked_exam['coms_proctor_lastname'] . "</td>";
        $output .= "<td>" . $booked_exam['coms_proctor_firstname'] . "</td>";
        $output .= "<td>" . $booked_exam['state'] . "</td>";
        if (in_array($booked_exam['state_id'], $cancellable_states)) {
            $event_name = $booked_exam['coms_exam_name'] . ' ' . $booked_exam['coms_exam_event_start_date'];
            $output .= "<td><a href='#' class='cancel-exam-event' data-coms_exam_event_name='" . $event_name . "' data-coms_exam_event_id='" . $booked_exam['coms_exam_event_id'] . "'><i class='far fa-times-circle'></i></a></td>";
        } else {
            $output .= "<td></td>";
        }
        $output .= "</tr>";
    }
    $output .= "</tbody></table>";
    return $output;
}

/**
 * @param $exams
 * @return string
 */
function examsOutput($exams) {
    $output = "<table id='exams_table' class='coms-js-table not-main-table'><thead>
        <th>Event ID</th>
        <th>Exam Name</th>
        <th>State</th>
        <th>Language</th>
        </thead><tbody>";
    foreach ($exams as $exam) {
        $output .= "<tr>";
        $output .= "<td>" . $exam['coms_exam_id'] . "</td>";
        $output .= "<td>" . $exam['coms_exam_name'] . "</td>";
        $output .= "<td>" . $exam['state'] . "</td>";
        $output .= "<td>" . $exam['language'] . "</td>";
        $output .= "</tr>";
    }
    $output .= "</tbody></table>";
    return $output;
}

/**
 * @param $trainers
 * @return string
 */
function trainerOutput($trainers) {
    $output = "<table id='trainer_table' class='coms-js-table not-main-table'><thead>
        <th>Trainer Id</th>
        <th>Trainer Firstname</th>
        <th>Trainer Lastname</th>
        <th>State</th>
        </thead><tbody>";
    foreach ($trainers as $trainer) {
        $output .= "<tr>";
        $output .= "<td>" . $trainer['coms_trainer_id'] . "</td>";
        $output .= "<td>" . $trainer['coms_trainer_firstname'] . "</td>";
        $output .= "<td>" . $trainer['coms_trainer_lastname'] . "</td>";
        $output .= "<td>" . $trainer['state'] . "</td>";
        $output .= "</tr>";
    }
    $output .= "</tbody></table>";
    return $output;
}

/**
 * @param $proctors
 * @return string
 */
function proctorOutput($proctors) {
    $output = "<table id='proctor_table' class='coms-js-table not-main-table'><thead>
        <th>Proctor Id</th>
        <th>Proctor Firstname</th>
        <th>Proctor Lastname</th>
        <th>State</th>
        </thead><tbody>";
    foreach ($proctors as $proctor) {
        $output .= "<tr>";
        $output .= "<td>" . $proctor['coms_proctor_id'] . "</td>";
        $output .= "<td>" . $proctor['coms_proctor_firstname'] . "</td>";
        $output .= "<td>" . $proctor['coms_proctor_lastname'] . "</td>";
        $output .= "<td>" . $proctor['state'] . "</td>";
        $output .= "</tr>";
    }
    $output .= "</tbody></table>";
    return $output;
}

/**
 * @param $a
 * @param $b
 * @return false|int
 */
function date_compare($a, $b)
{
    return strtotime($b["coms_exam_event_start_date"]) - strtotime($a["coms_exam_event_start_date"]);
}

if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] !== $PARTID) {
    generateImage($expression->n1.' + '.$expression->n2.' =', $captchaImage);
    require_once(__ROOT__.'/templates/login.phtml');
} else {
    $exams_json = api(array("cmd" => "read", "paramJS" => array("table" => "v_csvexport_trainingorg_exam", "where" => "a.coms_training_organisation_id = $PARTID")));
    $exams = json_decode($exams_json, true);
    $trainers_json = api(array("cmd" => "read", "paramJS" => array("table" => "v_csvexport_trainingorg_trainer", "where" => "a.coms_training_organisation_id = $PARTID")));
    $trainers = json_decode($trainers_json, true);
    $proctors_json = api(array("cmd" => "read", "paramJS" => array("table" => "v_csvexport_trainingorg_proctor", "where" => "a.coms_training_organisation_id = $PARTID")));
    $proctors = json_decode($proctors_json, true);
    $booked_exams = json_decode(api(array("cmd" => "read", "paramJS" => array("table" => "v_coms_trainingorg_exam_events", "where" => "a.coms_training_org_id = $PARTID"))), true);
    usort($booked_exams, 'date_compare');

    $trid = array();
    foreach ($trainers as $trainer) {
        $trid[] = $trainer['coms_trainer_id'];
    }
    $exid = array();
    foreach ($exams as $exam) {
        $exid[] = $exam['coms_exam_id'];
    }
    $tridcsv = implode(",", $trid);
    $exidcsv = implode(",", $exid);
    $trexor = api(array("cmd" => "read", "paramJS" => array("table" => "v_csvexport_trainer_exam", "where" => "coms_trainer_id in ($tridcsv) && coms_exam_id in ($exidcsv)")));
    require_once(__ROOT__ . '/templates/main.phtml');
}