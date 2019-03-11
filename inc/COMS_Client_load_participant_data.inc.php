<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/inc/captcha/captcha.inc.php');
if (isset($_POST['ato_logout'])) {
    session_destroy();
    header('Location: //' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
    exit();
}
if (isset($_POST['login'])) {
    if (file_exists($_POST['captcha-image'])) unlink($_POST['captcha-image']);
    if (!$_POST['booking-pw']) {
        $error = 'Please fill all the fields.';
    } else {
        $sentCode = htmlspecialchars($_POST["code"]);
        $result = (int)$_POST["result"];
        if (getExpressionResult($sentCode) !== $result) {
            $error = "Wrong Captcha.";
        } else {
            $bookingpw = hash("sha512", htmlspecialchars($_POST['booking-pw']) . $PARTID_MD5);
            $db_content = json_decode(api(array("cmd" => "read", "paramJS" => array("table" => "v_coms_participant__id__email",
                "where" => "a.coms_participant_md5 = '$PARTID_MD5' && a.coms_participant_id = $PARTID"))), true);
            if ($db_content) {
                $_SESSION['name'] = $db_content[0]['coms_participant_firstname'] . ' ' . $db_content[0]['coms_participant_lastname'];
                $_SESSION['user_id'] = $db_content[0]['coms_participant_id'];
                $_SESSION['user_type'] = $USER_TYPE;
            } else {
                $error = 'Wrong password';
            }
        }
    }
}
if (isset($_POST['complete_participant_data'])) {
    if (!isset($_POST['language']) || !isset($_POST['gender']) || !$_POST['date_of_birth'] || !$_POST['place_of_birth'] || !isset($_POST['country_of_birth'])) {
        $error = 'Please fill all the fields.';
    } else {
        $language = htmlspecialchars($_POST['language']);
        $gender = htmlspecialchars($_POST['gender']);
        $date_of_birth = htmlspecialchars($_POST['date_of_birth']);
        $place_of_birth = htmlspecialchars($_POST['place_of_birth']);
        $country_of_birth = htmlspecialchars($_POST['country_of_birth']);
        $errr = json_decode(api(array("cmd" => "makeTransition", "paramJS" => array("table" => "coms_participant", "row" => array(
            "coms_participant_id" => $_SESSION['user_id'],
            "coms_participant_language_id" => $language,
            "coms_participant_gender" => $gender,
            "coms_participant_dateofbirth" => $date_of_birth,
            "coms_participant_placeofbirth" => $place_of_birth,
            "coms_participant_birthcountry" => $country_of_birth,
            "state_id" => 111
        )))), true);
        if (count($errr) > 2) {
            $success = "Your data has been updated.";
        } else {
            $errr = "Data can't be updated.";
        }
    }
}
if (isset($_POST['change_participant_language'])) {
    if (!isset($_POST['language'])) {
        $error = 'Please choose a language.';
    } else {
        $language = htmlspecialchars($_POST['language']);
        $errr = json_decode(api(array("cmd" => "makeTransition", "paramJS" => array("table" => "coms_participant", "row" => array(
            "coms_participant_id" => $_SESSION['user_id'],
            "coms_participant_language_id" => $language,
            "state_id" => 111
        )))), true);
        if (count($errr) > 2) {
            $success = "Language changed successfully.";
        } else {
            $errr = "Language can't be changed.";
        }
    }
}

/**
 * @param $key
 * @param $language
 * @return string
 * @throws Exception
 */
function translate($key, $language) {
    $coms_client_lang = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/translations/COMS_Client_lang.json');
    $coms_client_lang = json_decode($coms_client_lang);
    try {
        if (isset($coms_client_lang->$key->$language->text)) {
            $text = $coms_client_lang->$key->$language->text;
        } else {
            throw new Exception();
        }
    } catch (Exception $e) {
        $text = '';
    }
    return $text;
}

if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] !== $PARTID || $_SESSION['user_type'] != 'participant') {
    $db_content = json_decode(api(array("cmd" => "read", "paramJS" => array("table" => "v_coms_participant__id__email",
        "where" => "a.coms_participant_md5 = '$PARTID_MD5' && a.coms_participant_id = $PARTID"))), true);
    if ($db_content[0]['coms_participant_LIAM_id']) {
        generateImage($expression->n1.' + '.$expression->n2.' =', $captchaImage);
        require_once($_SERVER['DOCUMENT_ROOT'] . '/inc/templates/participant/COMS_Client_login.inc.php');
    } else {
        echo 'redirect to LIAM2';
    }
} else {
    $participant = json_decode(api(array(
        "cmd" => "read", "paramJS" => array(
            "table" => "v_coms_participant__id__email",
            "where" => "a.coms_participant_id = $_SESSION[user_id]"
        )
    )), true);
    $genders = array(
        'female',
        'male',
        'inter',
        'n/a'
    );
    $languages = json_decode(api(array("cmd" => "read", "paramJS" => array("table" => "v_coms_language"))), true);
    $participant_language = $participant[0]['coms_participant_language_id'];
    $participant_language = array_filter($languages, function($arr)  use ($participant_language) {
        return ($arr['coms_language_id'] == $participant_language);
    });
    $participant_language = strtolower(array_values($participant_language)[0]['language_short']);
    $participant_state = $participant[0]['state_id'];
    $participant_events = json_decode(api(array(
        "cmd" => "read", "paramJS" => array(
            "table" => "v_coms_participant__exam_event",
            "where" => "a.coms_participant_id = $_SESSION[user_id]",
            "orderby" => "coms_exam_event_start_date",
            "ascdesc" => "desc"
        )
    )), true);
    $certificates = json_decode(api(array(
        "cmd" => "read", "paramJS" => array(
            "table" => "v_certificate_participant",
            "where" => "a.coms_participant_id = $_SESSION[user_id] && a.coms_certificate_type_id != 6 && state_id in (73, 74, 75)"
        )
    )), true);
    require_once($_SERVER['DOCUMENT_ROOT'] . '/inc/templates/participant/COMS_Client_main.inc.php');
}