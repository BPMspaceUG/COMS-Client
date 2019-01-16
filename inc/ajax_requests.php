<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/inc/api.secret.inc.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/inc/api.inc.php');

/**
 * @param $array
 * @param $key
 * @return array
 */
function unique_multidim_array($array, $key) {
    $temp_array = array();
    $i = 0;
    $key_array = array();

    foreach($array as $val) {
        if (!in_array($val[$key], $key_array)) {
            $key_array[$i] = $val[$key];
            $temp_array[$i] = $val;
        }
        $i++;
    }
    return $temp_array;
}
if (isset($_POST['data'])) {
    if ($_POST['data'] == 'cancel-exam-event') {
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
            require_once($_SERVER['DOCUMENT_ROOT'] . '/templates/edit_exam_event.php');
        }
    }
    if ($_POST['data'] == 'show-participation-list') {
        $exam_event_id = htmlspecialchars($_POST['exam_event_id']);
        $exam_event = json_decode(api(array("cmd" => "read", "paramJS" => array("table" => "v_exam_event__exam__trainingorg__trainer", "where" => "a.coms_training_org_id = $_SESSION[user_id] && coms_exam_event_id = $exam_event_id"))), true);
        $exam_event_state_id = $exam_event[0]['event_state_id'];
        if ($exam_event && isset($exam_event[0]['coms_exam_event_name'])) {
            $heading = "manage Participants for " . $exam_event[0]['coms_exam_event_name'] . " - " . $exam_event[0]['event_state_name'];
        } else {
            $heading = 'manage Participants';
        }
        $participants = json_decode(api(array("cmd" => "read", "paramJS" => array("table" => "v_coms_participant__exam_event", "where" => "coms_training_org_id = $_SESSION[user_id] && coms_exam_event_id = $exam_event_id"))), true);
        $languages = json_decode(api(array("cmd" => "read", "paramJS" => array("table" => "v_coms_language"))), true);
        $genders = array(
            'female',
            'male',
            'inter',
            'n/a'
        );
        $cancellable_participant_states = array(
            27,
            28,
            30
        );
        require_once($_SERVER['DOCUMENT_ROOT'] . '/templates/participation.php');

    }
    if ($_POST['data'] == 'edit-participant') {
        $participant_id = htmlspecialchars($_POST['participant_id']);
        $languages = json_decode(api(array("cmd" => "read", "paramJS" => array("table" => "v_coms_language"))), true);
        $genders = array(
            'female',
            'male',
            'inter',
            'n/a'
        );
        $participant = json_decode(api(array("cmd" => "read", "paramJS" => array("table" => "v_coms_participant__id__email", "where" => "coms_participant_id = $participant_id"))), true);
        if ($participant) {
            $participant_id = $participant[0]['coms_participant_id'];
            $participant_matriculation = $participant[0]['coms_participant_matriculation'];
            $firstname = $participant[0]['coms_participant_firstname'];
            $lastname = $participant[0]['coms_participant_lastname'];
            $email = $participant[0]['coms_participant_emailadresss'];
            $language = $participant[0]['coms_participant_language_id'];
            $gender = $participant[0]['coms_participant_gender'];
            $date_of_birth = $participant[0]['coms_participant_dateofbirth'];
            $place_of_birth = $participant[0]['coms_participant_placeofbirth'];
            $country_of_birth = $participant[0]['coms_participant_birthcountry'];
        }
        require_once($_SERVER['DOCUMENT_ROOT'] . '/templates/edit_participant.php');
    }
    if ($_POST['data'] == 'search-participant') {
        $exam_event_id = htmlspecialchars($_POST['exam_event_id']);
        /*$already_joined_participants = json_decode(api(array("cmd" => "read", "paramJS" => array("table" => "v_coms_participant__exam_event", "where" => "coms_training_org_id = $_SESSION[user_id] && coms_exam_event_id != $exam_event_id"))), true);
        if ($already_joined_participants) {
            $participants_ids = array();
            foreach ($already_joined_participants as $already_joined_participant) {
                array_push($participants_ids, $already_joined_participant['coms_participant_id']);
            }
            $participants_ids = implode(',', $participants_ids);
            $participants = json_decode(api(array("cmd" => "read", "paramJS" => array("table" => "v_coms_participant__exam_event", "where" => "coms_training_org_id = $_SESSION[user_id] && coms_participant_id not in ($participants_ids)"))), true);
        } else {
            $participants = json_decode(api(array("cmd" => "read", "paramJS" => array("table" => "v_coms_participant__exam_event", "where" => "coms_training_org_id = $_SESSION[user_id]"))), true);
        }*/

        //$participants = $_POST['all_participants'];

        /*$participants = json_decode(api(array("cmd" => "read", "paramJS" => array("table" => "v_coms_participant__exam_event", "where" => "coms_training_org_id = $_SESSION[user_id] && coms_exam_event_id != $exam_event_id"))), true);
        $participants = unique_multidim_array($participants, 'coms_participant_id');*/
        //require_once($_SERVER['DOCUMENT_ROOT'] . '/templates/search_participant.php');

        $participants = json_decode(api(array("cmd" => "read", "paramJS" => array("table" => "v_coms_participant__exam_event", "where" => "coms_training_org_id = $_SESSION[user_id] && coms_exam_event_id = $exam_event_id"))), true);
        $arr = array();
        foreach ($participants as $participant) {
            array_push($arr, $participant['coms_participant_id']);
        }
        echo json_encode($arr);
    }
    if ($_POST['data'] == 'check-participant-name') {
        $firstname = htmlspecialchars($_POST['firstname']);
        $lastname = htmlspecialchars($_POST['lastname']);
        if (isset($_POST['participant_id'])) {
            $check_participant_name = json_decode(api(array("cmd" => "read", "paramJS" => array("table" => "v_coms_participant__id__email", "where" => "coms_participant_firstname = '$firstname' && coms_participant_lastname = '$lastname' && coms_participant_id != $_POST[participant_id]"))), true);
        } else {
            $check_participant_name = json_decode(api(array("cmd" => "read", "paramJS" => array("table" => "v_coms_participant__id__email", "where" => "coms_participant_firstname = '$firstname' && coms_participant_lastname = '$lastname'"))), true);
        }
        if ($check_participant_name) {
            echo true;
        } else {
            echo false;
        }
    }
    if ($_POST['data'] == 'cancel-participant-state') {
        $participant_exam_event_id = htmlspecialchars($_POST['coms_participant_exam_event_id']);
        $check_id = json_decode(api(array("cmd" => "read", "paramJS" => array("table" => "v_coms_participant__exam_event", "where" => "a.coms_participant_exam_event_id = $participant_exam_event_id && a.coms_training_org_id = $_SESSION[user_id]"))), true);
        if (!$check_id) {
            $result = 'Incorrect Participant';
        } else {
            $result = api(array(
                "cmd" => "makeTransition",
                "paramJS" => array("table" => 'coms_participant_exam_event',
                    "row" => array(
                        "coms_participant_exam_event_id" => $participant_exam_event_id,
                        "state_id" => 85,
                        "confirmcancel" => "plsdie"
                    )
                )
            ));
        }
        echo $result;
    }
    if ($_POST['data'] == 'create-participant-from-csv') {
        $participants = json_decode($_POST['items']);
        $exam_event_id = htmlspecialchars($_POST['exam_event_id']);
        foreach ($participants as $item) {
            $firstname = htmlspecialchars($item[2]);
            $lastname = htmlspecialchars($item[1]);
            $email = htmlspecialchars($item[4]);
            $check_participant_email = json_decode(api(array("cmd" => "read", "paramJS" => array("table" => "v_coms_participant__id__email", "where" => "coms_participant_emailadresss = '$email'"))), true);
            if ($check_participant_email) {
                $error = 'Participant with this email address already exists';
            }
            if (!isset($error)) {
                $place_of_birth = $item[9] ? htmlspecialchars($item[9]) : null;
                $country_of_birth = $item[10] ? htmlspecialchars($item[10]) : null;
                $gender = $item[3] ? htmlspecialchars($item[3]) : null;
                $date_of_birth = $item[8] ? htmlspecialchars($item[8]) : null;
                $errr = json_decode(api(array("cmd" => "create", "paramJS" => array("table" => "coms_participant", "row" => array(
                    "coms_participant_firstname" => $firstname,
                    "coms_participant_lastname" => $lastname,
                    "coms_participant_email" => $email,
                    "coms_participant_language_id" => 6,
                    "coms_participant_gender" => $gender,
                    "coms_participant_dateofbirth" => $date_of_birth,
                    "coms_participant_placeofbirth" => $place_of_birth,
                    "coms_participant_birthcountry" => $country_of_birth
                )))), true);
                if (!$errr[0]['allow_transition']) {
                    $error = $errr[0]['message'];
                } elseif (isset($errr[0]['errormsg'])) {
                    $error = $errr[0]['errormsg'];
                } else {
                    $created_participant = json_decode(api(array("cmd" => "read", "paramJS" => array("table" => "v_coms_participant__id__email", "where" => "coms_participant_emailadresss = '$email'"))), true);
                    if ($created_participant) {
                        $success = "The participant has been successfully created.";
                        $errr = json_decode(api(array("cmd" => "create", "paramJS" => array("table" => "coms_participant_exam_event", "row" => array(
                            "coms_participant_id" => $created_participant[0]['coms_participant_id'],
                            "coms_exam_event_id" => $exam_event_id
                        )))), true);
                        if (!$errr[0]['allow_transition']) {
                            $error = $errr[0]['message'];
                        } elseif (isset($errr[0]['errormsg'])) {
                            $error = $errr[0]['errormsg'];
                        } else {
                            $success = "The participant has been successfully added to the event.";
                        }
                    } else {
                        $error = "The Participant can't be created.";
                    }
                }
            }
        }
        if (isset($success)) {
            echo 'success';
        } elseif (isset($error)) {
            echo $error;
        }
    }
    if ($_POST['data'] == 'book-participant-from-csv') {
        $participants = json_decode($_POST['items'], true);
        $exam_event_id = htmlspecialchars($_POST['exam_event_id']);
        foreach ($participants as $participant) {
            $participant_exist = json_decode(api(array("cmd" => "read", "paramJS" => array("table" => "v_coms_participant__exam_event", "where" => "coms_training_org_id = $_SESSION[user_id] && coms_exam_event_id = $exam_event_id && coms_participant_id = $participant[99]"))), true);
            if ($participant_exist) {
                $error = 'This participant is already added to this exam event';
            } else {
                $errr = json_decode(api(array("cmd" => "create", "paramJS" => array("table" => "coms_participant_exam_event", "row" => array(
                    "coms_participant_id" => $participant[99],
                    "coms_exam_event_id" => $exam_event_id
                )))), true);
                if (!$errr[0]['allow_transition']) {
                    $error = $errr[0]['message'];
                } elseif (isset($errr[0]['errormsg'])) {
                    $error = $errr[0]['errormsg'];
                } else {
                    $success = "The participants have been successfully added to the event.";
                }
            }
        }
        if (isset($success)) {
            echo 'success';
        } elseif (isset($error)) {
            echo $error;
        }
    }
} else {
    if (isset($_FILES)) {
        $handle = fopen($_FILES['file']['tmp_name'], 'r');
        $error_records = array();
        $create_records = array();
        $book_records = array();
        while (($data = fgetcsv($handle)) !== FALSE) {
            if ((int)$data[0]) {
                /*echo $lastname;
                echo preg_replace("/\W\s/u", '', $lastname) . "<br />";
                exit();*/
                $lastname = isset($data[1]) ? $data[1] : false;
                $firstname = isset($data[2]) ? $data[2] : false;
                $email = isset($data[4]) ? $data[4] : false;
                if (!$lastname && !$firstname && !$email) continue;
                $ico = isset($data[7]) ? $data[7] : false;
                if ($ico) {
                    $participant = json_decode(api(array("cmd" => "read", "paramJS" => array("table" => "v_coms_participant__id__email", "where" => "coms_participant_emailadresss = '$email'"))), true);
                    if ($participant) {
                        if ($firstname == $participant[0]['coms_participant_firstname'] && $lastname == $participant[0]['coms_participant_lastname']) {
                            $data[99] = $participant[0]['coms_participant_id'];
                            array_push($book_records, $data);
                        } else {
                            $firstname = $firstname ? $firstname . ", " : '';
                            $lastname = $lastname ? $lastname . ", " : '';
                            $error_record = $firstname . $lastname . $email . ", Ico Id exists but firstname or lastname don't match.";
                            array_push($error_records, $error_record);
                        }
                    } else {
                        $firstname = $firstname ? $firstname . ", " : '';
                        $lastname = $lastname ? $lastname . ", " : '';
                        $error_record = $firstname . $lastname . $email . ", Ico Id exists but firstname or lastname don't match.";
                        array_push($error_records, $error_record);
                    }
                } else {
                    if (!$lastname || !$firstname || !$email) {
                        $firstname_text = $firstname ? $firstname : "No Firstname";
                        $lastname_text = $lastname ? $lastname : "No Lastname";
                        $email_text = $email ? $email : "No Email";
                        $error_record = $firstname_text . ", " . $lastname_text . ", " . $email_text;
                        array_push($error_records, $error_record);
                    } else {
                        $participant = json_decode(api(array("cmd" => "read", "paramJS" => array("table" => "v_coms_participant__id__email", "where" => "coms_participant_emailadresss = '$email'"))), true);
                        if ($participant) {
                            if ($firstname == $participant[0]['coms_participant_firstname'] && $lastname == $participant[0]['coms_participant_lastname']) {
                                $data[99] = $participant[0]['coms_participant_id'];
                                array_push($book_records, $data);
                            } else {
                                $error_record = $firstname . ", " . $lastname . ", " . $email . ", Email exists but Firstname and lastname are different.";
                                array_push($error_records, $error_record);
                            }
                        } else {
                            array_push($create_records, $data);
                        }
                    }
                }
            }
        }
        fclose($handle);
    }
    if (!$error_records && !$book_records && !$create_records) {
        echo false;
    } else {
        require_once($_SERVER['DOCUMENT_ROOT'] . '/templates/import_participants.php');
    }
}