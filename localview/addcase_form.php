<form action="" method="POST" enctype="multipart/form-data" id="newCase">
    <input type="hidden" name="action" value="addcase">
    <div class="row">
        <div class="col">
            <label for="category"><?= get_string('category', 'observationtest') ?></label>
            <div class="form-row">
                <div class="form-group col-10">
                    <select name="category" id="category" class="form-control">
                        <option value=""> - </option>
                        <?php foreach($categories as $key => $category): ?>
                        <option value="<?= $key ?>"><?= $category ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="hidden" name="categoryname" value="">
                </div>
                <div class="form-group col-2">
                    <a class="btn btn-primary" data-toggle="modal" data-target="#categoryModal"><i
                            class="fas fa-plus"></i></a>
                </div>
            </div>
        </div>
        <div class="col">
            <label for="complexity"><?= get_string('complexity', 'observationtest') ?></label>
            <select name="complexity" id="complexity" class="form-control">
                <option value=""> - </option>
                <?php foreach($complexityranges as $key => $range): ?>
                <option value="<?= $key ?>"><?= $range ?></option>
                <?php endforeach; ?>
            </select>
            <input type="hidden" name="complexityname" value="">
        </div>
    </div>
    <div class="row">
        <div class="col"></div>
        <div class="col">
            <label for="customFile"><?= get_string('image', 'observationtest') ?></label>
            <div class="custom-file">
                <input type="file" name="customFile" class="custom-file-input" id="customFile" required>
                <label class="custom-file-label" for="customFile"><?= get_string('choose_file', 'observationtest') ?></label>
                <small class="form-text text-muted">* <?= get_string('file_extension', 'observationtest') ?></small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col text-right mt-5">
            <a href="<?= '//' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?id=' . $activity ?>"
                class="btn btn-secondary"><?= get_string('exit', 'observationtest') ?></a>
            <button class="btn btn-primary" type="submit" id="add-case"><?= get_string('add_new', 'observationtest') ?></button>
        </div>
    </div>
</form>
<div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalLabel"><?= get_string('new_category', 'observationtest') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert hide" role="alert"></div>
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="category"><?= get_string('category_name', 'observationtest') ?></label>
                        <input type="text" name="category" id="newcategory" placeholder="<?= get_string('new_category_name', 'observationtest') ?>"
                            class="form-control">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= get_string('close', 'observationtest') ?></button>
                <button type="button" id="add-category" class="btn btn-primary"><?= get_string('add_category', 'observationtest') ?></button>
            </div>
        </div>
    </div>
</div>