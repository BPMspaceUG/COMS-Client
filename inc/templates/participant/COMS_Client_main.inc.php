<div class="modal fade" id="main_participant_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <?php if (isset($error)) : ?>
                <div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
            <?php elseif (isset($success)) : ?>
                <div class="alert alert-success" role="alert"><?php echo $success; ?></div>
            <?php endif; ?>
            <div class="row modal-header">
                <div class="col-lg-2">
                    <a href="/"><img class="img-fluid" style="margin: 0 auto;" src="https://via.placeholder.com/180x180.png?text=LOGO" alt=""></a>
                </div>
                <div class="col-lg-8">
                    <div class="headline">
                        <h2 class="modal-title" id="iso27001Lable">Participant form</h2>
                    </div>
                </div>
                <div class="col-lg-2 text-right">
                    <a class="btn btn-link" href="/" role="button" data-toggle="tooltip" title="home">
                        <i class="far fa-times-circle fa-2x" aria-hidden="true"></i>
                    </a>
                </div>
            </div>

            <div class="modal-body">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="navbar-collapse collapse" id="navbarSupportedContent">
                        <ul class="nav nav-tabs navbar-nav w-100 participant-nav">
                            <li class="nav-item">
                                <a class="nav-link active" href="#personal_data" data-toggle="tab" aria-expanded="true">Personal data</a>
                            </li>
                            <?php if ($participant_state == 111) : ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="#events" data-toggle="tab" aria-expanded="false">Events</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#certificates" data-toggle="tab" aria-expanded="false">Certificates</a>
                                </li>
                            <?php endif; ?>
                            <li class="nav-item dropdown ml-auto">
                                <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="far fa-user fa-2x"></i>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="#">Change password</a>
                                    <a class="dropdown-item" href="#">Add new E-Mail</a>
                                    <a class="dropdown-item" href="#">Delete E-Mail</a>
                                    <a class="dropdown-item" href="#">Delete account</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>

                <div class="tab-content">
                    <div id="personal_data" class="tab-pane fade in active show">
                        <h3>Personal data</h3>
                        <p><strong>Your personal data right now:</strong></p>
                        <table id="participant_info_table" class="w-100 mb-5">
                            <thead>
                                <th>Firstname</th>
                                <th>Lastname</th>
                                <th>Gender</th>
                                <th>Birthday</th>
                                <th>Birthplace</th>
                                <th>Birthcountry</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php echo $participant[0]['coms_participant_firstname']; ?></td>
                                    <td><?php echo $participant[0]['coms_participant_lastname']; ?></td>
                                    <td><?php echo $participant[0]['coms_participant_gender']; ?></td>
                                    <td><?php echo $participant[0]['coms_participant_dateofbirth']; ?></td>
                                    <td><?php echo $participant[0]['coms_participant_placeofbirth']; ?></td>
                                    <td><?php echo $participant[0]['coms_participant_birthcountry']; ?></td>
                                </tr>
                            </tbody>
                        </table>
                        <?php if ($participant_state == 110) : ?>
                            <div class="alert alert-warning" role="alert">
                                <i class="fas fa-pencil-alt"></i> Please fill out the fields where your data was incorrect or empty in the sent mail.
                                In order to issue a certificate, the following fields must be completed correctly and in full.
                                ICO can not issue a certificate if only the name has been provided as this information alone is not sufficient for clear identification of the person.
                            </div>
                            <form method="post" action="" id="complete_participant_data" class="needs-validation">
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label for="gender" class="float-right">Gender</label>
                                    </div>
                                    <div class="col-lg-6">
                                        <?php foreach ($genders as $gender) :
                                            if ($gender == $participant[0]['coms_participant_gender']) : ?>
                                                <input type="radio" name="gender" class="radio-button" value="<?php echo $gender; ?>" checked required />
                                            <?php else : ?>
                                                <input type="radio" name="gender" class="radio-button" value="<?php echo $gender; ?>" required />
                                            <?php endif; ?>
                                            <span class="text-lowercase"><?php echo $gender; ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label for="date_of_birth" class="float-right">Date of Birth</label>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" value="<?php echo $participant[0]['coms_participant_dateofbirth']; ?>" required />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label for="place_of_birth" class="float-right">Place of Birth</label>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" id="place_of_birth" name="place_of_birth" class="form-control" value="<?php echo $participant[0]['coms_participant_placeofbirth']; ?>" required />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label for="country_of_birth" class="float-right">Country of Birth</label>
                                    </div>
                                    <div class="col-lg-6">
                                        <select id="country_of_birth" name="country_of_birth" class="form-control" required>
                                            <?php if ($participant[0]['coms_participant_birthcountry']) : ?>
                                                <option value="<?php echo $participant[0]['coms_participant_birthcountry']; ?>" selected><?php echo $participant[0]['coms_participant_birthcountry']; ?></option>
                                            <?php else : ?>
                                                <option value="" selected disabled>Please select country</option>
                                            <?php endif; ?>
                                            <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/inc/iso_3166_country_codes/array_of_countries.inc.php');
                                            foreach ($countries as $country) : ?>
                                                <option value="<?php echo $country; ?>"><?php echo $country; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label for="language" class="float-right">Language</label>
                                    </div>
                                    <div class="col-lg-6">
                                        <?php foreach ($languages as $language) {
                                            if ($language['coms_language_id'] == 5 || $language['coms_language_id'] == 6) :
                                                if ($language['coms_language_id'] == $participant[0]['coms_participant_language_id']) : ?>
                                                    <input type="radio" name="language" class="radio-button" value="<?php echo $language['coms_language_id']; ?>" checked required />
                                                <?php else : ?>
                                                    <input type="radio" name="language" class="radio-button" value="<?php echo $language['coms_language_id']; ?>" required />
                                                <?php endif; ?>
                                                <span class="text-lowercase"><?php echo $language['language_short']; ?></span>
                                            <?php endif;
                                        } ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-6"></div>
                                    <div class="col-lg-6">
                                        <button class="btn btn-primary float-left" type="submit" name="complete_participant_data"><i class="fas fa-long-arrow-alt-right"></i> Update</button>
                                    </div>
                                </div>
                            </form>
                            <p><strong>Confidentiality</strong> - By sending this form, the candidate agrees that the test results will be shared with the reporting.</p>
                        <?php elseif ($participant_state == 111) : ?>
                            <a id="participant_change_language_button" class="float-right" href="#">Change Language</a>
                            <p><?php echo translate('test', $participant_language); ?></p>
                        <?php endif; ?>
                    </div>
                    <?php if ($participant_state == 111) : ?>
                        <div id="events" class="tab-pane fade">
                            <h3>Events</h3>
                            <table id="participant_events_table" class="w-100">
                                <thead>
                                    <tr>
                                        <th>Event ID</th>
                                        <th>Event name</th>
                                        <th>Percentage</th>
                                        <th>Start date</th>
                                        <th>State</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $first = true;
                                    foreach ($participant_events as $event) {
                                        $border_top = '';
                                        if ($event['coms_exam_event_start_date'] < date('Y-m-d H:i:s') && $first) {
                                            $past_event = true;
                                        } elseif (($event['coms_exam_event_start_date'] < date('Y-m-d H:i:s')) && !isset($past_event) && !$first) {
                                            $border_top = 'row-border-top';
                                            $past_event = true;
                                        }
                                        if ($first) $first = false; ?>
                                        <tr class="<?php echo $border_top; ?>">
                                            <td><?php echo $event['coms_exam_event_id']; ?></td>
                                            <td><?php echo $event['coms_exam_event_name']; ?></td>
                                            <td><?php echo $event['coms_exam_event_percent']; ?></td>
                                            <td><?php echo $event['coms_exam_event_start_date']; ?></td>
                                            <td><?php echo $event['participant_state_name']; ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <div id="certificates" class="tab-pane fade">
                            <h3>Certificates</h3>
                            <table id="certificates_table" class="w-100">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Download</th>
                                        <th>Exam name</th>
                                        <th>State</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($certificates as $certificate) : ?>
                                        <tr>
                                            <td><?php echo $certificate['coms_certificate_id']; ?></td>
                                            <?php if ($certificate['state_id'] == 73 || $certificate['state_id'] == 75) : ?>
                                                <td>Download</td>
                                            <?php else : ?>
                                                <td><a href="#">Download</a></td>
                                            <?php endif; ?>
                                            <td><?php echo $certificate['exam_name']; ?></td>
                                            <td><?php echo $certificate['state']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="modal-footer">
                <a class="btn btn-primary" href="/" role="button" data-toggle="tooltip" title="home">
                    <i class="fa fa-times-circle-o" aria-hidden="true"></i>&nbsp;Home
                </a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="change_language_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <form method="post" action="" id="complete_participant_data" class="needs-validation">
                <div class="form-group row">
                    <div class="col-lg-6">
                        <label for="language" class="float-right">Language</label>
                    </div>
                    <div class="col-lg-6">
                        <?php foreach ($languages as $language) {
                            if ($language['coms_language_id'] == 5 || $language['coms_language_id'] == 6) :
                                if ($language['coms_language_id'] == $participant[0]['coms_participant_language_id']) : ?>
                                    <input type="radio" name="language" class="radio-button" value="<?php echo $language['coms_language_id']; ?>" checked required />
                                <?php else : ?>
                                    <input type="radio" name="language" class="radio-button" value="<?php echo $language['coms_language_id']; ?>" required />
                                <?php endif; ?>
                                <span class="text-lowercase"><?php echo $language['language_short']; ?></span>
                            <?php endif;
                        } ?>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-12">
                        <button class="btn btn-primary float-right" type="submit" name="change_participant_language">Save</button>
                        <button type="button" class="btn cancel float-right">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript" src="/js/COMS_Client_participant.js"></script>