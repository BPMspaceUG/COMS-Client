<div class="modal fade" id="search_participant_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <form method="post" action="" id="add_existing_participant" class="needs-validation">
                <table id='search_participants' class='coms-js-table participants-search-table'>
                    <thead>
                        <tr>
                            <th class='no-sort'></th>
                            <th>Matricle ID</th>
                            <th>Lastname</th>
                            <th>Firstname</th>
                            <th>E-Mail</th>
                            <th>Date of Birth</th>
                            <th>Place of Birth</th>
                            <th>Country of Birth</th>
                            <th>state_person</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($participants) : foreach ($participants as $participant) : ?>
                            <tr>
                                <td><input type="checkbox" name="check_participant[]" value="<?php echo $participant['coms_participant_id']; ?>"/></td>
                                <td><?php echo $participant['coms_participant_matriculation']; ?></td>
                                <td><?php echo $participant['coms_participant_lastname']; ?></td>
                                <td><?php echo $participant['coms_participant_firstname']; ?></td>
                                <td><?php echo $participant['coms_participant_emailadresss']; ?></td>
                                <td><?php echo $participant['coms_participant_dateofbirth']; ?></td>
                                <td><?php echo $participant['coms_participant_placeofbirth']; ?></td>
                                <td><?php echo $participant['coms_participant_birthcountry']; ?></td>
                                <td><?php echo $participant['participation_state_name']; ?></td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
                <div class="form-group row">
                    <div class="col-lg-6"></div>
                    <div class="col-lg-6">
                        <button class="btn btn-primary" type="submit" name="add_existing_particiant">Add</button>
                        <button type="button" class="btn cancel-existing-participant">Cancel</button>
                        <button type="button" class="btn reset-existing-participant">Reset</button>
                    </div>
                </div>
                <input type="hidden" name="exam_event_id" value="<?php echo $exam_event_id; ?>"/>
            </form>
        </div>
    </div>
</div>
