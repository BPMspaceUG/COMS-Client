<div class="modal fade" id="show_participation_list_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <h3><?php echo $heading; ?></h3>
            <div class='participation-nav'>
                <a href='#'><i class="add-participant fas fa-user-plus fa-2x"></i></a>
                <a href='#'><div class="search-participant"><i class="fas fa-user fa-2x"></i><i class="participant-arrow-right fas fa-angle-right fa-2x"></i><i class="fas fa-calendar-alt fa-2x"></i></div></a>
                <a href='#'><img class="anonymous-exams" src="https://www.iconspng.com/clipart/anonymous-face-mask/anonymous-face-mask.svg"></a>
                <input type="number" min="3" value="3" class="number-of-anonymous-exams">
            </div>
            <table id='show_participation_list' class='coms-js-table participation-list-table'>
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
                        <th>state_participation</th>
                        <th class='no-sort'></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($participants) : foreach ($participants as $participant) : ?>
                        <tr>
                            <td><i class='fas fa-edit'></i></td>
                            <td><?php echo $participant['coms_participant_matriculation']; ?></td>
                            <td><?php echo $participant['coms_participant_lastname']; ?></td>
                            <td><?php echo $participant['coms_participant_firstname']; ?></td>
                            <td><?php echo $participant['coms_participant_emailadresss']; ?></td>
                            <td><?php echo $participant['coms_participant_dateofbirth']; ?></td>
                            <td><?php echo $participant['coms_participant_placeofbirth']; ?></td>
                            <td><?php echo $participant['coms_participant_birthcountry']; ?></td>
                            <td><?php echo $participant['participation_state_name']; ?></td>
                            <td><?php echo $participant['participant_state_name']; ?></td>
                            <td><i class='far fa-times-circle'></i></td>
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