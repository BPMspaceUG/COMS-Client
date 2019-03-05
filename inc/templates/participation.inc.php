<div class="modal fade" id="show_participation_list_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="row">
                <div class="col-lg-2">
                    <a href="/"><img class="img-fluid" src="https://via.placeholder.com/180x180.png?text=LOGO" alt=""></a>
                </div>
            </div>
            <h3><?php echo $heading; ?></h3>
            <div class='participation-nav'>
                <?php if ($exam_event_state_id == 147) : ?>
                    <a href='#' class="add-participant"><i class="fas fa-user-plus fa-2x"></i></a>
                    <a href='#' class="search-participant" data-coms_exam_event_id="<?php echo $exam_event_id; ?>" data-coms_exam_event_name="<?php echo $exam_event[0]['coms_exam_event_name']; ?>" data-coms_exam_event_state_name="<?php echo $exam_event[0]['event_state_name']; ?>"><div><i class="fas fa-user fa-2x"></i><i class="participant-arrow-right fas fa-angle-right fa-2x"></i><i class="fas fa-calendar-alt fa-2x"></i></div></a>
                    <a href='#' class="anonymous-exams" data-coms_exam_event_id="<?php echo $exam_event_id; ?>"><img src="/images/anonymous-face-mask.svg"></a>
                    <input type="number" min="3" value="3" id="number_of_anonymous_exams" class="number-of-anonymous-exams"><label for="number_of_anonymous_exams">Dummy Exams</label>
                    <button type="button" class="btn import-button" data-coms_exam_event_id="<?php echo $exam_event_id; ?>">Import</button>
                <?php endif; ?>
            </div>
            <table id='show_participation_list' class='coms-js-table participation-list-table'>
                <thead>
                    <tr>
                        <th class='no-sort'></th>
                        <th>Matricle ID</th>
                        <th>Lastname</th>
                        <th>Firstname</th>
                        <th>E-Mail</th>
                        <th>Lang</th>
                        <th>Date of Birth</th>
                        <th>Place of Birth</th>
                        <th>Country of Birth</th>
                        <th>state_person</th>
                        <th>state_participation</th>
                        <th class='no-sort'></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($participants) :
                        $diplicate_emails = array();
                        $unique_data = array();
                        foreach ($participants as $participant) {
                            if (in_array($participant['coms_participant_id'], $unique_data)) {
                                $diplicate_emails[$participant['coms_participant_id']][] = $participant['coms_participant_emailadresss'];
                                continue;
                            }
                            array_push($unique_data, $participant['coms_participant_id']);
                        }
                        $unique_data = array();
                        foreach ($participants as $participant) :
                            if (in_array($participant['coms_participant_id'], $unique_data)) continue;
                            array_push($unique_data, $participant['coms_participant_id']);
                            $emails = array();
                            if (array_key_exists($participant['coms_participant_id'], $diplicate_emails)) {
                                foreach ($diplicate_emails[$participant['coms_participant_id']] as $item) {
                                    array_push($emails, $item);
                                }
                            }
                            $emails = implode(', ', $emails);
                            ?>
                            <tr>
                                <?php if ($exam_event_state_id == 147 && $participant['participation_state'] == 110) : ?>
                                    <td><a href="#" class="edit-participant" data-coms_participant_id="<?php echo $participant['coms_participant_id']; ?>"><i class='fas fa-edit'></i></a></td>
                                <?php else : ?>
                                    <td></td>
                                <?php endif; ?>
                                <td><?php echo $participant['coms_participant_matriculation']; ?></td>
                                <td><?php echo $participant['coms_participant_lastname']; ?></td>
                                <td><?php echo $participant['coms_participant_firstname']; ?></td>
                                <td><?php echo $participant['coms_participant_emailadresss'];
                                    if ($emails) : ?>
                                        <span class="additional-emails-button"><i class="fas fa-info-circle"></i><div><?php echo "Additional emails: <br />" . $emails; ?></div></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-lowercase"><?php echo $participant['language_short']; ?></td>
                                <td><?php echo $participant['coms_participant_dateofbirth']; ?></td>
                                <td><?php echo $participant['coms_participant_placeofbirth']; ?></td>
                                <td><?php echo $participant['coms_participant_birthcountry']; ?></td>
                                <td><?php echo $participant['participation_state_name']; ?></td>
                                <td><?php echo $participant['participant_state_name']; ?></td>
                                <?php if ($exam_event_state_id == 147 && $participant['participation_state'] == 110 && in_array($participant['participant_state'], $cancellable_participant_states)) : ?>
                                    <td><a href='#' class='cancel-participant-state' data-coms_participant_exam_event_id='<?php echo $participant['coms_participant_exam_event_id']; ?>'><i class='far fa-times-circle'></i></a></td>
                                <?php else : ?>
                                    <td></td>
                                <?php endif; ?>
                            </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
            <div class="form-group row">
                <div class="col-lg-12">
                    <button type="button" class="btn cancel">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add_participant_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <h2>new Participant</h2>
            <form method="post" action="" id="create_participant" class="needs-validation">
                <div class="form-group row">
                    <label for="firstname" class="col-lg-4 col-sm-6">Firstname *</label>
                    <input type="text" id="firstname" name="firstname" class="form-control col-lg-8" required />
                </div>
                <div class="form-group row">
                    <label for="lastname" class="col-lg-4 col-sm-6">Lastname *</label>
                    <input type="text" id="lastname" name="lastname" class="form-control col-lg-8" required />
                </div>
                <div class="form-group row">
                    <label for="email" class="col-lg-4 col-sm-6">E-Mail *</label>
                    <input type="text" id="email" name="email" class="form-control col-lg-8" required />
                </div>
                <div class="form-group row">
                    <label for="language" class="col-lg-4 col-sm-6">Language</label>
                    <?php foreach ($languages as $language) {
                        if ($language['coms_language_id'] == 5 || $language['coms_language_id'] == 6) {
                            echo "<input type='radio' name='language' class='radio-button' value='" . $language['coms_language_id'] . "'/><span class='text-lowercase'>" . $language['language_short'] . "</span>";
                        }
                    } ?>
                </div>
                <div class="form-group row">
                    <label for="gender" class="col-lg-4 col-sm-6">Gender</label>
                    <?php foreach ($genders as $gender) {
                        echo "<input type='radio' name='gender' class='radio-button' value='" . $gender . "'/><span class='text-lowercase'>" . $gender . "</span>";
                    } ?>
                </div>
                <div class="form-group row">
                    <label for="date_of_birth" class="col-lg-4 col-sm-6">Date of Birth</label>
                    <input type="date" id="date_of_birth" name="date_of_birth" class="form-control col-lg-8" />
                </div>
                <div class="form-group row">
                    <label for="place_of_birth" class="col-lg-4 col-sm-6">Place of Birth</label>
                    <input type="text" id="place_of_birth" name="place_of_birth" class="form-control col-lg-8" />
                </div>
                <div class="form-group row">
                    <label for="country_of_birth" class="col-lg-4 col-sm-6">Country of Birth</label>
                    <select id="country_of_birth" name="country_of_birth" class="form-control col-lg-8">
                        <option value="" selected disabled>Please select country</option>
                        <?php require_once($_SERVER['DOCUMENT_ROOT'] . '/inc/iso_3166_country_codes/array_of_countries.inc.php');
                        foreach ($countries as $country) {
                            echo "<option value='" . $country . "'>" . $country . "</option>";
                        } ?>
                    </select>
                </div>
                <div class="form-group row">
                    <div class="col-lg-6">
                        <p>* fields are mandatory</p>
                    </div>
                    <div class="col-lg-6">
                        <button class="btn btn-primary create-participant" type="submit" name="create_participant">Create</button>
                        <button type="button" class="btn cancel-participant">Cancel</button>
                        <button type="button" class="btn reset-participant">Reset</button>
                    </div>
                </div>
                <input type="hidden" name="exam_event_id" value="<?php echo $exam_event_id; ?>">
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="add_anonymous_exams_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <h3>additional Exam Info</h3>
            <form method="post" action="" id="add_anonymous_exams">
                <textarea id="anonymous_exams" name="anonymous_exams" class="form-control" rows="5" readonly></textarea>
                <input type="hidden" name="exam_event_id" value="<?php echo $exam_event_id; ?>"/>
                <input type="hidden" id="anonymous_exams_date" name="anonymous_exams_date" value=""/>
                <div class="form-group row">
                    <div class="col-lg-6">
                    </div>
                    <div class="col-lg-6">
                        <button class="btn btn-primary" type="submit" name="add_anonymous_exams">Save</button>
                        <button type="button" class="btn cancel-anonymous-exam">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="import_file_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <h3>import CSV</h3>
            <form method="post" action="" id="import_csv_form" class="needs-validation">
                <input type="file" name="import_csv" id="import_csv" required />
                <div class="form-group row">
                    <div class="col-lg-6">
                    </div>
                    <div class="col-lg-6">
                        <button class="btn btn-primary" type="button" id="save_import_csv" name="save_import_csv">OK</button>
                        <button type="button" class="btn cancel-import">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>