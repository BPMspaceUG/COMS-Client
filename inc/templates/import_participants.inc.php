<div class="modal fade" id="import_participants_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <h3>import Participants</h3>
            <?php if ($error_records) : ?>
                <div class="import-records-container">
                    <span class="import-records-container-title">can not import</span>
                    <?php foreach ($error_records as $record) : ?>
                        <p><?php echo $record; ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php if ($create_records) : ?>
                <div class="import-records-container create-records-container">
                    <span class="import-records-container-title">create and book and event</span>
                    <div class="float-right">
                        <input type="checkbox" class="select-all" /> select all
                        <input type="checkbox" class="deselect-all" /> deselect all
                    </div>
                    <form method="post" action="" id="create_participant_form_csv" class="needs-validation">
                        <?php foreach ($create_records as $record) : ?>
                            <div><input type="checkbox" class="create-check-participant" name="check_participant[]" value='<?php echo json_encode($record); ?>'> <?php echo $record[1] . ", " . $record[2] . ", " . $record[4]; ?><br /></div>
                        <?php endforeach; ?>
                        <button class="btn btn-primary create-participant-from-import-button" type="button">create & book</button>
                    </form>
                </div>
            <?php endif; ?>
            <?php if ($book_records) : ?>
                <div class="import-records-container book-records-container">
                    <span class="import-records-container-title">only book an event</span>
                    <div class="float-right">
                        <input type="checkbox" class="select-all" /> select all
                        <input type="checkbox" class="deselect-all" /> deselect all
                    </div>
                    <form method="post" action="" id="book_participant_form_csv" class="needs-validation">
                        <?php foreach ($book_records as $record) :
                            $ico = $record[7] ? ", " . $record[7] : ''; ?>
                            <div><input type="checkbox" class="book-check-participant" name="check_participant[]" value='<?php echo json_encode($record); ?>'> <?php echo $record[1] . ", " . $record[2] . ", " . $record[4] . $ico; ?><br /></div>
                        <?php endforeach; ?>
                        <button class="btn btn-primary book-participant-from-import-button" type="button">book</button>
                    </form>
                </div>
            <?php endif; ?>
            <div class="form-group row">
                <div class="col-lg-6"></div>
                <div class="col-lg-6">
                    <button type="button" class="btn cancel-import-window">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>