<?php
    $db = DB::getInstance();
    if(isset($_POST['id'])) {
        $record = $db->getById($_POST['id'], 'licenses_list');
    }
?>

<h3><?= ucfirst($activePageLabel) ;?></h3>
<h6>Delete license</h6>

<div class="form_main">
    <div class="form py-4">
        <form method="post" class="form-horizontal">
            <input type="hidden" name="active_page" value="licenses" />
            <input type="hidden" name="action" value="List" />
            <div class="form-group d-flex">
                <label class="control-label col-sm-3 my-auto" for="licenseId">Id</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="id" id="licenseId" value="<?= $record['id'] ;?>" readonly />
                </div>
            </div>
            <div class="form-group d-flex">
                <label class="control-label col-sm-3 my-auto" for="license">License</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="license" value="<?= $record['license'] ;?>" readonly />
                </div>
            </div>
            <div class="form-group d-flex">
                <label class="control-label col-sm-3 my-auto" for="licenseIsActive">Is active</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="licenseIsActive" value="<?= $record['license_is_active'] ;?>" readonly />
                </div>
            </div>
            <div class="form-group d-flex">
                <label class="control-label col-sm-3 my-auto" for="licenseIsUsed">Is used</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="licenseIsUsed" value="<?= $record['license_is_used'] ;?>" readonly />
                </div>
            </div>
            <div class="form-group d-flex">
                <label class="control-label col-sm-3 my-auto" for="clientName">Client</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="clientName" value="<?= $record['client_name'] ;?>" readonly />
                </div>
            </div>
            <div class="form-group d-flex">
                <label class="control-label col-sm-3 my-auto" for="clientIsActive">Client is active</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="clientIsActive" value="<?= $record['license_is_used'] ;?>" readonly />
                </div>
            </div>
            <div class="form-group d-flex">
                <label class="control-label col-sm-3 my-auto" for="validToDate">Valid to date</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="validToDate" value="<?= $record['valid_to_date'] ;?>" readonly />
                </div>
            </div>
            <div class="form-group d-flex">
                <label class="control-label col-sm-3 my-auto" for="modules">Module(s)</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="modules" value="<?= $record['modules'] ;?>" readonly />
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