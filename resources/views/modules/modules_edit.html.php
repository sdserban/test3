<h3><?= ucfirst($activePageLabel) ;?></h3>
<h6>Edit module</h6>

<?php
$db = DB::getInstance();

if(count($errors) > 0) {
    foreach($errors as $error) {
        ?>
        <div class="alert alert-danger" role="alert">
            <?= $error; ?>
        </div>
        <?php
    }
} else {
    if(isset($_POST['id'])) {
        $record = $db->getById($_POST['id'], 'modules');
        $_POST['code'] = $record['modulecode'];
        $_POST['name'] = $record['modulename'];
    }
}
?>


<div class="form_main">
    <div class="form py-4">
        <form method="post" class="form-horizontal">
            <input type="hidden" name="active_page" value="modules" />
            <input type="hidden" name="action" value="Edit" />
            <input type="hidden" name="id" value="<?= $_POST['id']; ?>" />;
            <div class="form-group d-flex">
                <label class="control-label col-sm-3 my-auto" for="ModuleCode">Code</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="moduleCode" name="code" value="<?= isset($_POST['code']) ? $_POST['code'] : '';?>" placeholder="Module code" />
                </div>
            </div>
            <div class="form-group d-flex">
                <label class="control-label col-sm-3 my-auto" for="moduleName">Name</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="moduleName" name="name" value="<?= isset($_POST['code']) ? $_POST['name'] : '';?>" placeholder="Module name" />
                </div>
            </div>
            <div class="form-group d-flex">
                <div class="col-sm-3">
                </div>
                <div class="col-sm-8">
                    <button type="submit" value="List" name="action" class="btn btn-default">Cancel</button>
                </div>
                <div class="col-sm-1">
                    <input type="submit" value="Update" name="confirmation" class="btn btn-primary" />
                </div>
            </div>
        </form>
    </div>
</div>
