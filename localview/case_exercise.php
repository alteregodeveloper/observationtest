<div class="row">
    <div class="col">
        <div class="row">
            <div class="col text-center mt-5 mb-5">
                <a href="" class="btn btn-success btn-lg mt-5 mb-5" id="start-test"><i class="fas fa-camera-retro"></i>
                    <?= get_string('start_test', 'observationtest') ?></a>
            </div>
        </div>
        <div class="row hide" id="case-content">
            <div class="col text-center">
                <img src="<?= $image_route ?>" class="img-fluid" alt="Case Image">
            </div>
        </div>
        <div class="row justify-content-center hide" id="case-questions">
            <div class="col-8 bg-light p-3 pl-5 rounded">
                <div class="alert hide" role="alert"></div>
                <form action="" class="form">
                    <input type="hidden" name="testid" value="<?= $observationtestid ?>">
                    <input type="hidden" name="caseid" value="<?= $case->id ?>">
                    <input type="hidden" name="exercise" value="<?= $_GET['exe'] ?>">
                    <input type="hidden" name="complexity" value="<?= $complexity ?>">
                    <?php echo $qnas; ?>
                    <div class="col text-right">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> <?= get_string('end_test', 'observationtest') ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>