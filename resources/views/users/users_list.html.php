<?php
    $db = DB::getInstance();
    $records = $db->getAllRecords('users_list');
?>

<form method="post" class="form-horizontal px-3" role="form">
    <div class="form-group row">
        <input type="hidden" name ="active_page" value ="users" />
        <h3><?= ucfirst($activePageLabel) ;?></h3>
        <input type="submit" class="ml-auto" name ="action" value="Add new" />
    </div>
</form>

<table id="navtable" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>Username</th>
            <th>Is active</th>
            <th>Role</th>
            <th>Email</th>
            <th width="80em"></th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach($records as $record) {
            ?>
            <tr>
                <td><?= $record['user_name']; ?></td>
                <td><?= $record['user_is_active']; ?></td>
                <td><?= $record['role']; ?></td>
                <td><?= $record['mail']; ?></td>
                <td>
                    <form method="post" class="form-horizontal" role="form">
                        <div class="row justify-content-center">
                            <input type="hidden" name ="active_page" value ="users" />
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
            <th>Username</th>
            <th>Is active</th>
            <th>Role</th>
            <th>Email</th>
            <th></th>
        </tr>
    </tfoot>
</table>
