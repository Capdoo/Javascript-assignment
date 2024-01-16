<?php
    require_once "pdo_con.php";
    session_start();

        //Indicates the correct direction in our server
        $host = $_SERVER['HTTP_HOST'];
        $rute = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $url = "http://$host$rute";


    if(isset($_POST["cancel"])){
        header("Location: $url/index.php");
        die();
    }

    if(isset($_POST['delete']) && isset($_POST['profile_id'])){
        //Query itself
        $sql = "DELETE FROM profile WHERE profile_id = :zip";

        //Execution of the query
        $stmt = $conn->prepare($sql);
        $stmt->execute(array(':zip' => $_POST['profile_id']));

        //For the results
        $_SESSION['success'] = 'Register deleted';
        header("Location: $url/index.php");
        die();
    }

    if(! isset($_GET['profile_id'])){
        $_SESSION['error'] = "Profile id not found";
        header("Location: $url/index.php");
        die();
    }

    //CHECKING IF PROFILE_ID EXISTS
    $stmt = $conn->prepare("SELECT first_name, last_name FROM profile WHERE profile_id = :xyz");
    $stmt->execute(array(':xyz' => $_GET['profile_id']));
    $filas = $stmt->fetch(PDO::FETCH_ASSOC);

    if($filas == false){
        $_SESSION['error'] = 'Wrong value to profile_id';
        header("Location: $url/index.php");
        die();
    }

    $fn = htmlentities($filas["first_name"]);
    $ln = htmlentities($filas["last_name"]);

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
    <div class="container">
    <h1>Deleteing Profile - Rafael Ñontol</h1>

        <p>First Name:
        <?php echo $fn?></p>

        <p>Last Name:
        <?php echo $ln?></p>

        <form method="post">
            <input type="hidden" name="profile_id"
            value="<?php echo $_GET['profile_id']?>"
            />

                <input type="submit" name="delete" value="Delete">
                <input type="submit" name="cancel" value="Cancel">
            </p>
        </form>
    </div>
</body>
</html>
