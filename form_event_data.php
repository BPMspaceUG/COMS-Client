<!-- Modal  -->


<?php


/* ************* for testing purpose only, delete before going live ******************************** */

$PARTID_MD5 = "5eaf1b26e33089eadf2d3652262f5dc0";

$PARTID = "773532";

/* ********************************************************************************************* */










require '../../_path_url.inc.php';
require $filepath_liam."api/lib/php_crud_api_transform.inc.php";

function api($method, $url, $data = false) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
	curl_setopt($ch, CURLOPT_URL, $url);
	if ($data) {
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$headers = array();
		$headers[] = 'Content-Type: application/json';
		$headers[] = 'Content-Length: ' . strlen($data);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	}
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	return curl_exec($ch);
}
function clrjson($input){
	$jsonObject = json_decode($input, true);

	$output = php_crud_api_transform($jsonObject);
	//$output = json_encode($jsonObject, JSON_PRETTY_PRINT);
	return $output;
}



	// $object = array('user_id'=>1,'category_id'=>1,'content'=>'from php');
	// call('POST', 'http://localhost/api.php/posts',json_encode($object));



$success = false;
$showerror = false;
$invalid = false;
$submit = NULL;

//read the url
$actionurl = $main_domain . $canonical ."/" . $PARTID_MD5 . "/" . $PARTID . "/";



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

  // Show error if something was submitted
if (strlen($submit) > 0) {

	$showerror = true;
}





//"login"
if (isset($_POST['booking-pw'])) {
	$bookingpw = hash("sha512",htmlspecialchars($_POST['booking-pw']).$PARTID_MD5);
	$db_content = clrjson(api('GET', "http://dev.bpmspace.org:4040/~amade/COMS/api/api.php/v_csvexport_trainingorg?filter[]=coms_training_organisation_passwd_hash,eq,".$bookingpw));
	
	if (count($db_content['v_csvexport_trainingorg']) == 1 && $db_content['v_csvexport_trainingorg']['0']['coms_training_organisation_id'] == $PARTID){
		$ATO_NAME = $db_content['v_csvexport_trainingorg']['0']['coms_training_organisation_name'];
		$showerror = false;
		$success = false;
		
	}else{
		$showerror = true;
		$success = false;
	}
}





if (isset($_POST['type']) && htmlspecialchars($_POST['type']) != "") {
	

	$EventID = 'EVID'.time();
	$date = implode("-", array_reverse(explode(".", htmlspecialchars($_POST['date']))));
	

	@$logentry = array(
		$EventID => array(

			"call create_event('".$PARTID."', '".htmlspecialchars($_POST['type'])."', '".htmlspecialchars($_POST['trainer'])."', '".htmlspecialchars($_POST['supervisor'])."', '".htmlspecialchars($_POST['location'])."', '".$date." ".htmlspecialchars($_POST['start-time'])."');"

		)
	);


	
	

	//write to .json file
	$fp = fopen("log/form_event.json", 'a');
	fwrite($fp, json_encode($logentry, JSON_PRETTY_PRINT));
	fclose($fp);
	
	//$_POST['PartID'] = $PARTID;

	//prepare SQL statement
	$query = "INSERT INTO log (form, message, date_add) VALUES ('formular-event', '".json_encode($logentry, JSON_PRETTY_PRINT)."', '".date("Y-m-d H:i:s")."');";
	//$data = array('table' => "log.log (form, message, date_add)", 'row' => array(basename($_SERVER['SCRIPT_FILENAME'], $sql_statement, date("Y-m-d H:i:s"))));

	//write to database
	//$mysqli->query($query);

	//write to logfile
	//logRequest("Eingegebene Daten: ".var_export($_POST, true)."\n".var_export($sql_statement, true)); 



	$showerror = false;
	$success = true;



}

$exvalue = "Please select exam...";

//Read data for authenticated ATO

$exams = clrjson(api('GET', "http://dev.bpmspace.org:4040/~amade/COMS/api/api.php/v_csvexport_trainingorg_exam?filter[]=coms_training_organisation_id,eq,".$PARTID));


$trainer = clrjson(api('GET', "http://dev.bpmspace.org:4040/~amade/COMS/api/api.php/v_csvexport_trainingorg_trainer?filter[]=coms_training_organisation_id,eq,".$PARTID));


$proctor = clrjson(api('GET', "http://dev.bpmspace.org:4040/~amade/COMS/api/api.php/v_csvexport_trainingorg_proctor?filter[]=coms_training_organisation_id,eq,".$PARTID));
$trid = "";
for ($i=0; $i <count($trainer['v_csvexport_trainingorg_trainer']) ; $i++) { 
	$trid .= ",".$trainer['v_csvexport_trainingorg_trainer'][$i]['coms_trainer_id'];
}
$trex = clrjson(api('GET', "http://dev.bpmspace.org:4040/~amade/COMS/api/api.php/v_csvexport_trainer_exam?filter[]=coms_trainer_id,in".$trid));
									//var_dump($trex);
?>

<script type="text/javascript">


	var TOID = '<?php echo $PARTID;?>';
	var run2 = false;
	var run3 = false;
	console.log('<?php echo $PARTID;?>');
	document.addEventListener("DOMContentLoaded", step1);
	document.addEventListener("DOMContentLoaded", step4);
		// select exams depending on Training Organisation	
		function step1() {
			var exams = <?php echo (json_encode($exams['v_csvexport_trainingorg_exam']));?>;
			var $firstChoicee = $("#sel1");
			$.each(exams, function(index, value) {
				$firstChoicee.append("<option value='" + value.coms_exam_id + "'>" + value.coms_exam_name + "</option>");
			});
			var trainer = <?php echo (json_encode($trainer['v_csvexport_trainingorg_trainer']));?>;
			var $firstChoicet = $("#sel2");
			$.each(trainer, function(index, value) {
				$firstChoicet.append("<option value='" + value.coms_trainer_id + "'>" + value.coms_trainer_lastname + " " + value.coms_trainer_firstname + "</option>");
			});
		};   
		

		function step2() { //  now change trainers depending at TO and Exams

			if ($("#sel2").val() == '' || run3 == true) {
				run3 = false;
				var trfilt = new Array();
				
				if($("#sel1").val() == ''){
					var trainer = <?php echo (json_encode($trainer['v_csvexport_trainingorg_trainer']));?>;
					trfilt = trainer;
				}else {
					var ttr = <?php echo (json_encode($trex['v_csvexport_trainer_exam']));?>;
					$.each(ttr, function(index, value){
						if (value.coms_exam_id == $("#sel1").val()) {
							trfilt.push(value);} 


						});
				};

				var $secondChoice = $("#sel2");
				$secondChoice.empty();
				$secondChoice.append("<option selected value value=''>Please select...</option>");
				$.each(trfilt, function(index, value) {
					$secondChoice.append("<option value='" + value.coms_trainer_id + "'>" + value.coms_trainer_lastname + " " + value.coms_trainer_firstname + "</option>");
				});
			}else{
				run2 = true;
			};
		};
		function step3() { //  now change trainers depending at TO and Exams			
			if ($("#sel1").val() == '' || run2 == true){
				run2 = false;
				exfilt = new Array();
				if($("#sel2").val() == ''){
					var exams = <?php echo (json_encode($exams['v_csvexport_trainingorg_exam']));?>;
					exfilt = exams;
				}else {
					var tex = <?php echo (json_encode($trex['v_csvexport_trainer_exam']));?>;
					$.each(tex, function(index, value){
						if (value.coms_trainer_id == $("#sel2").val()) {
							exfilt.push(value);
						}  
					});
				};
				var $secondChoice = $("#sel1");
				$secondChoice.empty();
				$secondChoice.append("<option selected value value=''>Please select...</option>");
				$.each(exfilt, function(index, value) {
					$secondChoice.append("<option value='" + value.coms_exam_id + "'>" + value.coms_exam_name + "</option>");
				});
			} else{
				run3 = true;
			};
		};
		
		
		function step4() { //just need to select proctor

			var pctr = <?php echo (json_encode($proctor['v_csvexport_trainingorg_proctor']));?>;
			var $proctorChoice = $("#sel3");
					//$proctorChoice.empty();
					$.each(pctr, function(index, value) {
						$proctorChoice.append("<option>" + value.coms_proctor_lastname + " " + value.coms_proctor_firstname + "</option>");
					});


				};

			</script>

			<div class="md-margin-bottom-40"></div>
			<div class="modal-body">
				<?php
				if ($mysqli->connect_error) {
					echo '<div style="width: 85%; margin: 0 auto;">';
					echo '<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Connection to DB is not working - please contact us via mail&nbsp;<a href="mailto:office@ico-cert.org?subject=Book event">office@ico-cert.org</a> </div>';
					echo "</div>";
				}
				if ($invalid) {

					echo '<div style="width: 85%; margin: 0 auto;">';
					echo '<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Your URL is not valid - please contact us via mail&nbsp;<a href="mailto:office@ico-cert.org?subject=Book event">office@ico-cert.org</a> if you want to book an exam. </div>';
					echo "</div>";
				}else{
					?>

					<div style="width: 85%; margin: 0 auto;">
						<?php

						if ($success) {
							echo '<div class="alert alert-success" role="alert">
							<span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Vielen Dank! Ihre Daten werden überprüft.</div>';
						}
						if ($showerror)
							echo '<div class="alert alert-danger" role="alert">
						<span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Fehlende oder fehlerhafte Eingabe:<ul><li> Bitte geben Sie hier Ihr Bestellpasswort ein.</li><ul></div>';
						if (!$success) {
							if (!isset($bookingpw)) {



								?>
								<!-- Show password input first-->
								<form class="form-horizontal" method="post" action="<?php $actionurl ?>">
									<div class="form-group row">
										<label class="col-sm-2 control-label">Bestellpasswort<span class="text-danger">*</span></label>
										<div class="col-sm-6">
											<input  style="padding: 5px 10px; border-radius: 2px; border: 1px solid rgb(216, 222, 228);" type="password" class="form-control required" name="booking-pw" placeholder="Passwort" required>
										</div>
									</div>

									<div class="form-group">
										<label for="inputPassword" class="col-sm-2 control-label"></label>
										<div class="col-sm-6">
											<input type="submit" class="btn btn-primary" name="submit" value="&#8594; Weiter">

										</div> 



									</div>
								</form>
								<!--End of password input -->


								<?php
							}else if (!$showerror){
								?>


								<!-- form for booking an exam, after password input-->
								<form class="form-horizontal" method="post" action="<?php $actionurl ?>">
									<div class="form-group">
										<label class="col-sm-2  control-label">ATO Name</label>
										<div class="col-sm-6 text">
											<p class="form-control-static">
												<?php 
												echo $ATO_NAME;
												?>
											</p>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-2" for="sel1">Topic of the exam:<span class="text-danger">*</span></label>
										<div class="col-sm-6">
											<select style="padding: 5px 10px; border-radius: 2px; border: 1px solid rgb(216, 222, 228);" class="form-control required" id="sel1" name="type" required onchange="step2()">
												<option  selected value value=''>Please select</option>

											</select>
										</div>
									</div> 

									<div class="form-group row">
										<label class="col-sm-2 control-label">Trainer<span class="text-danger">*</span></label>
										<div class="col-sm-6">
											<select style="padding: 5px 10px; border-radius: 2px; border: 1px solid rgb(216, 222, 228);" class="form-control required" id="sel2" name="type" required onchange="step3()">
												<option  selected value value=''>Please select</option>

											</select>
										</div>
									</div>

									<div class="form-group row">
										<label class="col-sm-2 control-label">Prüfungsaufsicht<span class="text-danger">*</span></label>
										<div class="col-sm-6">
											<select style="padding: 5px 10px; border-radius: 2px; border: 1px solid rgb(216, 222, 228);" class="form-control required" id="sel3" name="type" required>
												<option disabled selected value value="">Please select...</option>

											</select>
										</div>
									</div>

									<div class="form-group row">
										<label class="col-sm-2 control-label">Location<span class="text-danger">*</span></label>
										<div class="col-sm-6">
											<input  style="padding: 5px 10px; border-radius: 2px; border: 1px solid rgb(216, 222, 228);" type="text" class="form-control required" name="location" placeholder="Location" required>
										</div>
									</div>

									<div class="form-group row">
										<label class="col-sm-2 control-label">Start date<span class="text-danger">*</span></label>
										<div class="col-sm-6">
											<input style="padding: 5px 10px; border-radius: 2px; border: 1px solid rgb(216, 222, 228);" type="text" required class="form-control  required" name="date" placeholder="dd.mm.yyyy" required>
										</div>
									</div>

									<div class="form-group row">
										<label class="col-sm-2 control-label">Start time<span class="text-danger">*</span></label>
										<div class="col-sm-6">
											<input style="padding: 5px 10px; border-radius: 2px; border: 1px solid rgb(216, 222, 228);" autocomplete="off" type="time" required class="form-control required" name="start-time" value="15:00" required>
										</div>
									</div>

									<div class="text-danger row">
										<label for="inputPassword" class="col-sm-2 control-label"></label>
										<p class="col-sm-4 text-danger">* = Mandatory field</p>
									</div> 

									<div class="form-group">
										<label for="inputPassword" class="col-sm-2 control-label"></label>
										<div class="col-sm-6">
											<input type="submit" class="btn btn-primary" name="submit" value="&#10004; Submit">
											<input type="reset" class="btn btn-primary" name="cancel" value="&otimes; Cancel">
										</div> 
									</div>
								</form>
								<!--End of booking form-->



								<?php
							}
						}
						?>
					</div>
					<?php 
				} 
				?>		
			</div>






<!-- END -->