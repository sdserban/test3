<h3><?= ucfirst($activePageLabel) ;?></h3>
<h6>Add license</h6>

<?php
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
        $record = $db->getById($_POST['id'], 'licenses');
        $rows = $db->getByField('license_id', $record['id'], 'license_for_modules');
        $modules = array();
        foreach($rows as $row) {
            array_push($modules, $row['modules_id']);
        }
        $_POST['license'] = $record['license'];
        $_POST['clientId'] = $record['client_id'];
        $_POST['active'] = $record['active'] ? 'on' : 'off';
        $_POST['dtl'] = $record['dtl'];
        $_POST['valid_to_date'] = $record['valid_to_date'];
        $_POST['modules'] = $modules;
        $_POST['status'] = is_null($record['uik']) ? "ready to link" : "linked to " . $record['uik'];
        $_POST['last_activation_date'] = $record['last_activation_date'];
    }
}

$db = DB::getInstance();

if(!isset($_POST['license'])) {
    $_POST['license'] = $db->getNewLicenseId();
}

$client = $db->getById($_POST['clientId'], 'clients_list');

$modules = $db->getAllRecords('modules_list');
$names = array();
foreach ($modules as $key => $module)
{
    $names[$key] = $module['name'];
}
array_multisort($names, SORT_ASC, $modules);
?>

<div class="form_main">
    <div class="form py-4">
        <form method="post" class="form-horizontal">
            <input type="hidden" name="active_page" value="licenses" />
            <input type="hidden" name="action" value="Edit" />
            <input type="hidden" name="id" value="<?= $_POST['id']; ?>" />
            <div class="form-group d-flex">
                <label class="control-label col-sm-3 my-auto" for="license">License</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="license" name="license" value="<?= $_POST['license'];?>" readonly />
                </div>
            </div>
            <div class="form-group d-flex">
                <label class="control-label col-sm-3 my-auto" for="role">Client</label>
                <div class="col-sm-9">
                    <input type="hidden" name="clientId" value="<?= $_POST['clientId'];?>" />
                    <input type="text" class="form-control" id="clientId" value="<?= $client['client_name'];?>" readonly />
                </div>
            </div>
            <?php
            if(!$showUik) {
                ?>
                <div class="form-group d-flex">
                    <label class="control-label col-sm-3 my-auto" for="isActive">Is active</label>
                    <div class="col-sm-9">
                        <input type="checkbox" class="ml-2 my-auto mr-auto" name="active" <?= ($_POST['active'] == 'on' ? 'checked' : ''); ?> />
                    </div>
                </div>   
                <?php
            }
            ?>
            <div class="form-group d-flex">
                <label class="control-label col-sm-3 my-auto" for="dtl">Days to live offline</label>
                <div class="col-sm-9">
                    <input type="number" class="form-control" id="dtl" name="dtl" value="<?= isset($_POST['dtl']) ? $_POST['dtl'] : '';?>" placeholder="0 means 'trust till end of license life'" />
                </div>
            </div>
            <div class="form-group d-flex">
                <label class="control-label col-sm-3 my-auto" for="valid_to_date">Valid to</label>
                <div class="col-sm-9">
                    <input type="date" class="form-control" id="valid_to_date" name="valid_to_date" value="<?= isset($_POST['valid_to_date']) ? $_POST['valid_to_date'] : '';?>" />
                </div>
            </div>
            <div class="form-group d-flex">
                <label class="control-label col-sm-3 my-auto">Licensed modules</label>
                <div class="col-sm-9">
                    <?php
                    foreach($modules as $module) {
                        ?>
                        <label>
                            <input type="checkbox" class="ml-2 my-auto mr-auto" name="modules[]" value="<?= $module['id'];?>" 
                                <?= in_array($module['id'], $_POST['modules']) ? " checked" : ""; ?> />
                            <?= $module['name']; ?>
                        </label><br />
                        <?php
                    }
                    ?>
                </div>
            </div>
            <?php
            if($showUik) {
                ?>
                <div class="form-group d-flex">
                    <label class="control-label col-sm-3 my-auto" for="uik">Installation key</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="uik" name="uik" placeholder="Copy here the instalation key received from client" />
                    </div>
                </div>
                <div class="form-group d-flex">
                    <label class="control-label col-sm-3 my-auto" for="key">Activation key</label>
                    <div class="col-sm-9">
                        <textarea class="form-control p-0" rows="6" readonly>
                            <?= $activationKey; ?>
                        </textarea>
                    </div>
                </div>
                <?php
            } else {
                ?>
                <div class="form-group d-flex">
                    <label class="control-label col-sm-3 my-auto" for="status">Status</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="status" name="status" value="<?= $_POST['status'];?>" readonly />
                    </div>
                </div>
                <div class="form-group d-flex">
                    <label class="control-label col-sm-3 my-auto" for="last_activation_date">Last activation</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="last_activation_date" name="last_activation_date" value="<?= $_POST['last_activation_date']; ?>" disabled />
                    </div>
                </div>
                <?php
            }
            ?>
            <div class="form-group d-flex">
                <div class="col-sm-3">
                </div>
                <div class="col-sm-8">
                    <?php
                    if(!$showUik) {
                        ?>
                        <button type="submit" value="List" name="action" class="btn btn-default">Cancel</button>
                        <input type="submit" value="Unlink" name="confirmation" class="btn btn-default" <?= is_null($record['uik']) ? "disabled" : ""?> />
                        <input type="submit" value="Offline activation" name="confirmation" class="btn btn-default" <?= is_null($record['uik']) ? "" : "disabled"?> />
                        <?php
                    } else {
                        ?>
                        <button type="submit" value="Edit" name="action" class="btn btn-default">Back</button>
                        <?php
                    }
                    ?>
                </div>
                <div class="col-sm-1">
                    <?php
                    if($showUik) {
                        ?>
                        <input type="submit" value="Activate" name="confirmation" class="btn btn-primary" <?= is_null($record['uik']) ? "" : "disabled"?> />
                        <?php
                    } else {
                        ?>
                        <input type="submit" value="Update" name="confirmation" class="btn btn-primary" />
                        <?php
                    }
                    ?>
                </div>
            </div>
        </form>
    </div>
</div>

