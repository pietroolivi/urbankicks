<?php
require_once("bootstrap.php");
?>
<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="CSS/style.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Barrio&display=swap" rel="stylesheet">
        <title>UrbanKicks Admin - <?php echo $templateParams["title"]; ?></title>
        <?php 
        if(isset($templateParams["js"])):
            foreach($templateParams["js"] as $script): 
        ?>
            <script src="<?php echo $script; ?>" defer></script>
        <?php 
            endforeach;
        endif;
        ?>
    </head>
    <body>
        <header>
            <h1><a href="admin_home.php">URBANKICKS</a></h1>
        </header>
        <main>
            <?php require($templateParams["name"]); ?>
        </main>
    </body>
</html>