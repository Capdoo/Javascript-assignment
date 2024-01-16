<?php 
    require_once "pdo_con.php";
    session_start();
    $stmt = $conn->query("SELECT profile_id, first_name, last_name, headline FROM profile");

    $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
<title>Rafael Santiago Ñontol Lozano</title>
<!-- bootstrap.php - this is HTML -->

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" 
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" 
    integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" 
    crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" 
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" 
    integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" 
    crossorigin="anonymous">

</head>
<body>
    <div>
        <h1>Resume Registry</h1>

        <?php
        //Sesion validator
        if(isset($_SESSION['success'])){
            echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
            unset($_SESSION['succes']);
        }

        if(isset($_SESSION['error'])){
            echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
            unset($_SESSION['error']);
        }
        
        //Redirection from user_id
        if(!isset($_SESSION['user_id'])){
            echo '<a href="login.php">Please login</a>';
        }else{
            echo '<a href="logout.php">Logout</a>';
        }
        
        if(count($filas) > 0){
            echo '<table border="1">'.'\n';
            echo '<tr><td>Name</td><td>Headline</td>';
            if(isset($_SESSION["user_id"])){
                echo "<td>Action</td>";
            }
            echo '</tr>\n';
        }
        
        //LOOP FOR EACH ROW
        foreach($filas as $row){

            $nombre = $row['first_name'];

            echo "<tr><td>";
            echo
                "<a href='view.php?profile_id=".$row["profile_id"]."'>".
                htmlentities($row['first_name']." ".$row['last_name'])."</a>";
            
            echo("</td><td>");
            echo(htmlentities($row['headline']));
            echo("</td>");

            //OPTIONS FOR EACH REGISTER
            if(isset($_SESSION["user_id"])){
                echo 
                    '<td><a href="edit.php?profile_id=' .
                    $row['profile_id'].' ">Edit</a>';  
                echo
                    '<a href="delete.php?profile_id='.
                    $row['profile_id'].'">Delete</a></td>';
            }
            echo("</tr>\n");
        }
        echo("</table>");

        if(isset($_SESSION["user_id"])){
            echo '<p><a href="add.php">Añadir nueva entrada</a></p>';
            echo ''.$_SESSION['user_id'].'  UMSI';
        }
        ?>
<!--
-->
    </div>
</body>