<?php
    $db = DB::getInstance();
    if(isset($_POST['id'])) {
        $record = $db->getById($_POST['id'], 'modules_list');
    }
?>

<h3><?= ucfirst($activePageLabel) ;?></h3>
<h6>Delete module</h6>

<div class="form_main">
    <div class="form py-4">
        <form method="post" class="form-horizontal">
            <input type="hidden" name="active_page" value="modules" />
            <input type="hidden" name="action" value="List" />
            <div class="form-group d-flex">
                <label class="control-label col-sm-3 my-auto" for="moduleId">Id</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="id" id="moduleId" value="<?= $record['id'] ;?>" readonly />
                </div>
            </div>
            <div class="form-group d-flex">
                <label class="control-label col-sm-3 my-auto" for="code">Code</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="code" value="<?= $record['code'] ;?>" readonly />
                </div>
            </div>
            <div class="form-group d-flex">
                <label class="control-label col-sm-3 my-auto" for="name">Name</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="name" value="<?= $record['name'] ;?>" readonly />
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