<?php
    require_once "src/middleware/session.php";
    require_once "src/repositories/manager.php";
    require_once "src/repositories/user.php";
    require_once "src/template/lateral_menu.php";
?>

<HTML>
    <head>
        <title>Showcase</title>

        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="main.css">
    </head>
    <body>

    <?php
        if(/*is_logged()*/true){
            echo show_lateral_menu();
        }
        else{
            header("login.php");
        }
    ?>

    </body>
</HTML>
