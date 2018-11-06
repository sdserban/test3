<h3><?= ucfirst($activePageLabel) ;?></h3>
<h6>Add client</h6>

<?php
if(count($errors) > 0) {
    foreach($errors as $error) {
        ?>
        <div class="alert alert-danger" role="alert">
            <?= $error; ?>
        </div>
        <?php
    }
}
?>

<div class="form_main">
    <div class="form py-4">
        <form method="post" class="form-horizontal">
            <input type="hidden" name="active_page" value="clients" />
            <input type="hidden" name="action" value="Add new" />
            <div class="form-group d-flex">
                <label class="control-label col-sm-3 my-auto" for="clientName">Name</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="clientName" name="name" value="<?= isset($_POST['name']) ? $_POST['name'] : '';?>" placeholder="Client name" />
                </div>
            </div>
            <div class="form-group d-flex">
                <label class="control-label col-sm-3 my-auto" for="isActive">Is active</label>
                <div class="col-sm-9">
                    <input type="checkbox" class="ml-2 my-auto mr-auto" name="active" <?= ($_POST['active'] == 'on' ? 'checked' : ''); ?> />
                </div>
            </div>
            <div class="form-group d-flex">
                <label class="control-label col-sm-3 my-auto" for="details">Details</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="details" name="details" value="<?= isset($_POST['details']) ? $_POST['details'] : '';?>" placeholder="Details" />
                </div>
            </div>
            <div class="form-group d-flex">
                <div class="col-sm-3">
                </div>
                <div class="col-sm-8">
                    <button type="submit" value="List" name="action" class="btn btn-default">Cancel</button>
                </div>
                <div class="col-sm-1">
                    <input type="submit" value="Save" name="confirmation" class="btn btn-primary" />
                </div>
            </div>
        </form>
    </div>
</div>

