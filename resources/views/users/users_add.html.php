<h3><?= ucfirst($activePageLabel) ;?></h3>
<h6>Add user</h6>

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
            <input type="hidden" name="active_page" value="users" />
            <input type="hidden" name="action" value="Add new" />
            <div class="form-group d-flex">
                <label class="control-label col-sm-3 my-auto" for="userName">Username</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="userName" name="username" value="<?= isset($_POST['username']) ? $_POST['name'] : '';?>" placeholder="Username" />
                </div>
            </div>
            <div class="form-group d-flex">
                <label class="control-label col-sm-3 my-auto" for="role">Role</label>
                <div class="col-sm-9">
                    <select class="form-control" id="role" name="role">
                        <option value="admin" <?= (($_POST['role'] == 'admin') ? 'selected': ''); ?>>
                            Admin
                        </option>
                        <option value="keyManager" <?= (($_POST['role'] == 'keyManager') ? 'selected': ''); ?>>
                            Key Manager
                        </option>
                        <option value="keyViewer" <?= (($_POST['role'] == 'keyViewer') ? 'selected': ''); ?>>
                            Key Viewer
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-group d-flex">
                <label class="control-label col-sm-3 my-auto" for="isActive">Is active</label>
                <div class="col-sm-9">
                    <input type="checkbox" class="ml-2 my-auto mr-auto" name="active" <?= ($_POST['active'] == 'on' ? 'checked' : ''); ?> />
                </div>
            </div>
            <div class="form-group d-flex">
                <label class="control-label col-sm-3 my-auto" for="mail">Email</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="mail" name="mail" value="<?= isset($_POST['details']) ? $_POST['details'] : '';?>" placeholder="Email" />
                </div>
            </div>
            <div class="form-group d-flex">
                <label class="control-label col-sm-3 my-auto" for="password">Password</label>
                <div class="col-sm-9">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Is better to choose a strong password" />
                </div>
            </div>
            <div class="form-group d-flex">
                <label class="control-label col-sm-3 my-auto" for="confirmation">Password again</label>
                <div class="col-sm-9">
                    <input type="password" class="form-control" id="confirmation" name="confirmationpassword" placeholder="Type again the same password" />
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

