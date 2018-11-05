<?php
    $db = DB::getInstance();
    $records = $db->getAllRecords('licenses_list');
?>

<form method="post" class="form-horizontal px-3" role="form">
    <div class="form-group row">
        <input type="hidden" name ="active_page" value ="licenses" />
        <h3><?= ucfirst($activePageLabel) ;?></h3>
        <input type="submit" class="ml-auto" name ="action" value="Add new" />
    </div>
</form>

<table id="navtable" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>License</th>
            <th width="65em">License is active</th>
            <th width="50em">License is used</th>
            <th>Client</th>
            <th width="65em">Client is active</th>
            <th width="65em">Valid to date</th>
            <th>Modules</th>
            <th width="80em"></th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach($records as $record) {
            ?>
            <tr>
                <td><?= $record['license']; ?></td>
                <td><?= $record['license_is_active']; ?></td>
                <td><?= $record['license_is_used']; ?></td>
                <td><?= $record['client_name']; ?></td>
                <td><?= $record['client_is_active']; ?></td>
                <td><?= $record['valid_to_date']; ?></td>
                <td><?= $record['modules']; ?></td>
                <td>
                    <form method="post" class="form-horizontal" role="form">
                        <div class="row justify-content-center">
                            <input type="hidden" name ="active_page" value ="licenses" />
                            <input type="submit" name ="action" value="Edit" class="mx-1" />
                            <input type="submit" name ="action" value="Delete" class="mx-1" />
                            <input type="hidden" name ="id" value ="<?= $record['id']; ?>" />
                        </div>
                    </form>
                </td>
            </tr>
            <?php
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th>License</th>
            <th>License is active</th>
            <th>License is used</th>
            <th>Client</th>
            <th>Client is active</th>
            <th>Valid to date</th>
            <th>Modules</th>
            <th></th>
        </tr>
    </tfoot>
</table>
