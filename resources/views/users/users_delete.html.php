<?php
    $db = DB::getInstance();
    if(isset($_POST['id'])) {
        $record = $db->getById($_POST['id'], 'users_list');
    }
?>

<h3><?= ucfirst($activePageLabel) ;?></h3>
<h6>Delete user</h6>

<div class="form_main">
    <div class="form py-4">
        <form method="post" class="form-horizontal">
            <input type="hidden" name="active_page" value="users" />
            <input type="hidden" name="action" value="List" />
            <div class="form-group d-flex">
                <label class="control-label col-sm-3 my-auto" for="userId">Id</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="id" id="userId" value="<?= $record['id'] ;?>" readonly />
                </div>
            </div>
            <div class="form-group d-flex">
                <label class="control-label col-sm-3 my-auto" for="userName">Username</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="userName" value="<?= $record['user_name'] ;?>" readonly />
                </div>
            </div>
            <div class="form-group d-flex">
                <label class="control-label col-sm-3 my-auto" for="licenseIsActive">Is active</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="licenseIsActive" value="<?= $record['user_is_active'] ;?>" readonly />
                </div>
            </div>
            <div class="form-group d-flex">
                <label class="control-label col-sm-3 my-auto" for="role">Role</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="role" value="<?= $record['role'] ;?>" readonly />
                </div>
            </div>
            <div class="form-group d-flex">
                <label class="control-label col-sm-3 my-auto" for="mail">Email</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="mail" value="<?= $record['mail'] ;?>" readonly />
                </div>
            </div>
            <div class="form-group d-flex">
                <div class="col-sm-3">
                </div>
                <div class="col-sm-8">
                    <button type="submit" value="List" name="action" class="btn btn-default">Cancel</button>
                </div>
                <div class="col-sm-1">
                    <input type="submit" value="Delete" name="confirmation" class="btn btn-danger" />
                </div>
            </div>
        </form>
    </div>
</div>