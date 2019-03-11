<div class="modal fade" id="main_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <?php if (isset($error)) : ?>
                <div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
            <?php elseif (isset($success)) : ?>
                <div class="alert alert-success" role="alert"><?php echo $success; ?></div>
            <?php endif; ?>

            <div class="row mb-5">
                <div class="col-lg-2">
                    <a href="/"><img class="img-fluid" src="https://via.placeholder.com/180x180.png?text=LOGO" alt=""></a>
                </div>
                <div class="col-lg-8">
                    <h1><?php echo $_SESSION['name'] . ' (' . $PARTID . ')'; ?></h1>
                </div>
                <div class="col-lg-2 text-right">
                    <form method="post" action="" id="ato_logout">
                        <input type="submit" value="Logout" name="ato_logout" />
                    </form>
                </div>
            </div>
            <ul class="nav nav-tabs ato-nav">
                <li id="exam_event" class="table-switch-button active"><a href="#">Exam Event</a></li>
                <li id="exams" class="table-switch-button"><a href="#">Exam</a></li>
                <li id="trainer" class="table-switch-button"><a href="#">Trainer</a></li>
                <li id="proctor" class="table-switch-button"><a href="#">Proctor</a></li>
            </ul>
            <div class="table-container">
                <?php
                echo examEventsOutput($booked_exams);
                echo examsOutput($exams);
                echo trainerOutput($trainers);
                echo proctorOutput($proctors);
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="create_event_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <h2>new Exam Event</h2>
            <form method="post" action="" id="create_event" class="needs-validation">
                <input type="hidden" value="<?php echo $PARTID; ?>" id="part_id">
                <div class="form-group row">
                    <div class="col-lg-6">
                        <div class="form-group row">
                            <label for="select_exam" class="col-lg-4 col-sm-6">Exam</label>
                            <select id="select_exam" name="select_exam" class="form-control col-lg-8" required>
                                <option value="" selected disabled>Please select</option>
                                <?php foreach($exams as $exam) {
                                    echo "<option value='" . $exam['coms_exam_id'] . "'>" . $exam['coms_exam_name'] . "</option>";
                                } ?>
                            </select>
                        </div>
                        <div class="form-group row">
                            <label for="select_trainer" class="col-lg-4 col-sm-6">Trainer</label>
                            <select id="select_trainer" name="select_trainer" class="form-control col-lg-8" required>
                                <option value="" selected disabled>Please select</option>
                                <?php foreach($trainers as $trainer) {
                                    echo "<option value='" . $trainer['coms_trainer_id'] . "'>" . $trainer['coms_trainer_firstname'] . " " . $trainer['coms_trainer_lastname'] . "</option>";
                                } ?>
                            </select>
                        </div>
                        <div class="form-group row">
                            <label for="select_proctor" class="col-lg-4 col-sm-6">Proctor</label>
                            <select id="select_proctor" name="select_proctor" class="form-control col-lg-8" required>
                                <option value="" selected disabled>Please select</option>
                                <?php foreach($proctors as $proctor) {
                                    echo "<option value='" . $proctor['coms_proctor_id'] . "'>" . $proctor['coms_proctor_firstname'] . " " . $proctor['coms_proctor_lastname'] . "</option>";
                                } ?>
                            </select>
                        </div>
                        <div class="form-group row">
                            <label for="location" class="col-lg-4 col-sm-6">Location</label>
                            <input type="text" id="location" name="location" class="form-control col-lg-8" required />
                        </div>
                        <div class="form-group row">
                            <label for="date" class="col-lg-4 col-sm-6">Start Date</label>
                            <input type="date" id="date" name="date" class="form-control col-lg-8" required />
                        </div>
                        <div class="form-group row">
                            <label for="time" class="col-lg-4 col-sm-6">Start Time</label>
                            <input type="time" id="time" name="time" class="form-control col-lg-8" required />
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <label for="exam_event_info">Exam Info</label>
                        <textarea name="exam_event_info" class="form-control exam-event-info" placeholder="Is there something we should know?" rows="11"></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-lg-6">
                        <p>All fields are mandatory</p>
                    </div>
                    <div class="col-lg-6">
                        <button class="btn btn-primary" type="submit" name="create_event">Create</button>
                        <button type="button" class="btn cancel">Cancel</button>
                        <button type="button" class="btn reset">Reset</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    var exams_json = <?php echo $exams_json; ?>;
    var trainers_json = <?php echo $trainers_json; ?>;
    var proctors_json = <?php echo $proctors_json; ?>;
    var trexor_json = <?php echo $trexor; ?>;
    var all_participants = <?php echo $all_participants; ?>;
</script>
<script type="text/javascript" src="/js/COMS_Client_coms_client.js"></script>
<script type="text/javascript" src="/js/COMS_Client_participation.js"></script>