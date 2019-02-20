<div class="modal fade" id="edit_participant_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <h2>edit Participant (<?php echo $participant_matriculation; ?>)</h2>
            <form method="post" action="" id="edit_participant" class="needs-validation">
                <div class="form-group row">
                    <label for="firstname" class="col-lg-4 col-sm-6">Firstname *</label>
                    <input type="text" id="firstname" name="firstname" class="form-control col-lg-8" value="<?php echo $firstname; ?>" required />
                </div>
                <div class="form-group row">
                    <label for="lastname" class="col-lg-4 col-sm-6">Lastname *</label>
                    <input type="text" id="lastname" name="lastname" class="form-control col-lg-8" value="<?php echo $lastname; ?>" required />
                </div>
                <div class="form-group row">
                    <label for="email" class="col-lg-4 col-sm-6">Add another E-Mail</label>
                    <input type="text" id="email" name="email" class="form-control col-lg-8" />
                </div>
                <div class="form-group row">
                    <label for="language" class="col-lg-4 col-sm-6">Language</label>
                    <?php foreach ($languages as $language_list) {
                        if ($language_list['coms_language_id'] == 5 || $language_list['coms_language_id'] == 6) {
                            if ($language_list['coms_language_id'] == $language) {
                                echo "<input type='radio' name='language' class='radio-button' value='" . $language_list['coms_language_id'] . "' checked/><span class='text-lowercase'>" . $language_list['language_short'] . "</span>";
                            } else {
                                echo "<input type='radio' name='language' class='radio-button' value='" . $language_list['coms_language_id'] . "'/><span class='text-lowercase'>" . $language_list['language_short'] . "</span>";
                            }
                        }
                    } ?>
                </div>
                <div class="form-group row">
                    <label for="gender" class="col-lg-4 col-sm-6">Gender</label>
                    <?php foreach ($genders as $gender_list) {
                        if ($gender_list == $gender) {
                                echo "<input type='radio' name='gender' class='radio-button' value='" . $gender_list . "' checked/><span class='text-lowercase'>" . $gender_list . "</span>";
                            } else {
                                echo "<input type='radio' name='gender' class='radio-button' value='" . $gender_list . "'/><span class='text-lowercase'>" . $gender_list . "</span>";
                            }
                    } ?>
                </div>
                <div class="form-group row">
                    <label for="date_of_birth" class="col-lg-4 col-sm-6">Date of Birth</label>
                    <input type="date" id="date_of_birth" name="date_of_birth" class="form-control col-lg-8" value="<?php echo $date_of_birth; ?>" />
                </div>
                <div class="form-group row">
                    <label for="place_of_birth" class="col-lg-4 col-sm-6">Place of Birth</label>
                    <input type="text" id="place_of_birth" name="place_of_birth" class="form-control col-lg-8" value="<?php echo $place_of_birth; ?>" />
                </div>
                <div class="form-group row">
                    <label for="country_of_birth" class="col-lg-4 col-sm-6">Country of Birth</label>
                    <select id="country_of_birth" name="country_of_birth" class="form-control col-lg-8">
                        <option value="<?php echo $country_of_birth; ?>" selected><?php echo $country_of_birth; ?></option>
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
                        <input type="hidden" value="" name="edit_participant" />
                        <button class="btn btn-primary edit-participant-button" type="button">Save</button>
                        <button type="button" class="btn cancel-participant">Cancel</button>
                    </div>
                </div>
                <input type="hidden" name="participant_id" value="<?php echo $participant_id; ?>">
            </form>
        </div>
    </div>
</div>