<?php
    $db = DB::getInstance();
    if(isset($_POST['id'])) {
        $record = $db->getById($_POST['id'], 'clients_list');
    }
?>

<h3><?= ucfirst($activePageLabel) ;?></h3>
<h6>Delete client</h6>

<div class="form_main">
    <div class="form py-4">
        <form method="post" class="form-horizontal">
            <input type="hidden" name="active_page" value="clients" />
            <input type="hidden" name="action" value="List" />
            <div class="form-group d-flex">
                <label class="control-label col-sm-3 my-auto" for="clientId">Id</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="id" id="clientId" value="<?= $record['id'] ;?>" readonly />
                </div>
            </div>
            <div class="form-group d-flex">
                <label class="control-label col-sm-3 my-auto" for="name">Name</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="name" value="<?= $record['client_name'] ;?>" readonly />
                </div>
            </div>
            <div class="form-group d-flex">
                <label class="control-label col-sm-3 my-auto" for="clientIsActive">Is active</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="clientIsActive" value="<?= $record['client_is_active'] ;?>" readonly />
                </div>
            </div>
            <div class="form-group d-flex">
                <label class="control-label col-sm-3 my-auto" for="details">Details</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="details" value="<?= $record['details'] ;?>" readonly />
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