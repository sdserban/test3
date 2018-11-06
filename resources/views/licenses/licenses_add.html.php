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
}

$db = DB::getInstance();

if(!isset($_POST['license'])) {
    $_POST['license'] = $db->getNewLicenseId();
}

$records = $db->getAllRecords('clients_list');
$clients = array();
$names = array();
foreach ($records as $key => $record)
{
    if($record['client_name'] != 'Demo') {
        array_push($clients, $record);
        $names[$key] = $record['client_name'];
    }
}
array_multisort($names, SORT_ASC, $clients);

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
            <input type="hidden" name="action" value="Add new" />
            <div class="form-group d-flex">
                <label class="control-label col-sm-3 my-auto" for="license">License</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="license" name="license" value="<?= $_POST['license'];?>" readonly />
                </div>
            </div>
            <div class="form-group d-flex">
                <label class="control-label col-sm-3 my-auto" for="role">Client</label>
                <div class="col-sm-9">
                    <select class="form-control" id="clientId" name="clientId">
                        <?php
                        foreach($clients as $client) {
                            ?>
                            <option value="<?= $client['id']; ?>" <?= (($_POST['clientId'] == $client['id']) ? 'selected': ''); ?>>
                                <?= $client['client_name']; ?>
                            </option>
                            <?php
                        }
                        ?>
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
                <label class="control-label col-sm-3 my-auto" for="valid_to_date">Licensed modules</label>
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

