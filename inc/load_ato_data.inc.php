<?php

// Inputs
$PARTID = 773532;
$PARTID2 = 773534;

function fmtOutput($title, $table, $inp) {
    echo "<h2>$title</h2>";
    echo "<p>Table / View: <b style='padding: 5px; border-radius: 5px; border: 1px solid #ccc;'>$table</b></p>";
    echo '<pre>'.var_export($inp, true).'</pre><br>';
    echo "<hr>";
}

function loadData($PARTID){
    echo "<h1>ATO ID: $PARTID</h1>";

    $exams = json_decode(api(array("cmd"=>"read", "paramJS" => array("table" => "v_csvexport_trainingorg_exam", "where" => "a.coms_training_organisation_id = $PARTID"))), true);
    $trainer =  json_decode(api(array("cmd"=>"read", "paramJS" => array("table" => "v_csvexport_trainingorg_trainer", "where" => "a.coms_training_organisation_id = $PARTID"))), true);
    $proctor = json_decode(api(array("cmd"=>"read", "paramJS" => array("table" => "v_csvexport_trainingorg_proctor", "where" => "a.coms_training_organisation_id = $PARTID"))), true);
    $booked_exams = json_decode(api(array("cmd"=>"read", "paramJS" => array("table" => "v_coms_trainingorg_exam_events", "where" => "a.coms_training_org_id = $PARTID"))), true);
    /*
    $tridcsv = implode(",", $trid);
    $exidcsv = implode(",", $exid);
    $trexor = json_decode(api( array("cmd"=>"read", "paramJS" => array("table" => "v_csvexport_trainer_exam", "where" => "coms_trainer_id in ($tridcsv) && coms_exam_id in ($exidcsv)"))), true);
    */
    fmtOutput('Exams', 'v_csvexport_trainingorg_exam', $exams);
    fmtOutput('Trainer', 'v_csvexport_trainingorg_trainer', $trainer);
    fmtOutput('Proctor', 'v_csvexport_trainingorg_proctor', $proctor);
    fmtOutput('Exam-Events', 'v_coms_trainingorg_exam_events', $booked_exams);
}

loadData($PARTID);
loadData($PARTID2);


/*
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
    $invalid = true;
}


//"login"
    if (isset($_POST['booking-pw'])) {
        $bookingpw = hash("sha512",htmlspecialchars($_POST['booking-pw']).$PARTID_MD5);
        $db_content = json_decode(api(array("cmd"=>"read", "paramJS" => array("table" => "v_csvexport_trainingorg",
                "where" => "coms_training_organisation_passwd_hash = '$bookingpw'"))), true);
    }

    if (isset($_POST['exam']) && htmlspecialchars($_POST['exam']) != "") {
        $date = implode("-", array_reverse(explode(".", htmlspecialchars($_POST['date']))));
        $ATO_NAME = $_POST['ATO_NAME'];
        $errr = api(array("cmd"=>"create", "paramJS" => array("table" => "coms_exam_event", "row" => array(
                "coms_exam_id" => htmlspecialchars($_POST['exam']),
                "coms_trainer_id" => htmlspecialchars($_POST['trainer']),
                "coms_training_org_id" => htmlspecialchars($PARTID),
                "coms_proctor_id" => htmlspecialchars($_POST['proctor']),
                "coms_exam_event_start_date" => $date. " " .htmlspecialchars($_POST['start-time']),
                "coms_exam_event_location" => htmlspecialchars($_POST['location']),
                "coms_delivery_type_id" => "5"))));
    }
*/
?>