<!DOCTYPE html>
<html lang="en">
<head>
    <title>License management</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
</head>
<body>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        } );
    </script>

    <?php 
        include VIEWS_FOLDER . "nav.html.php"; 
    ?>

    <div class="container my-3">
        <h4><?= ucfirst($activePageLabel) ;?></h4>
        
        <?php
        if(file_exists(VIEWS_FOLDER . $pages[$activePage]['content'])) {
            include($activePage['content']);
        } else {
            die('content view does not exist: ' . VIEWS_FOLDER . $activePage['content']);
        }
        ?>
    </div>
    
</body>
</html>