<!DOCTYPE html>
<html lang="en">
<head>
      <title>License management</title>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
</head>
<body>
    <?php include $viewsFolder . "nav.html.php"; ?>

    
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