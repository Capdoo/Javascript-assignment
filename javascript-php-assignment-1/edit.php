<?php
    require_once "pdo_con.php";
    session_start();

        //Indicates the correct direction in our server
        $host = $_SERVER['HTTP_HOST'];
        $rute = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $url = "http://$host$rute";
    
    if(!isset($_SESSION["user_id"])){
        die("User not loged yet");
    }

    if(isset($_POST["cancel"])){
        header("Location $url/index.php");
        die();
    }

    //SQL SENTENCE FOR UPDATE BY 'PROFILE ID' FORM POST METHOD
    if(isset($_POST["save"])){
        if(strlen($_POST["first_name"])<1 || strlen($_POST["last_name"])<1 || strlen($_POST["email"])<1 || strlen($_POST["headline"])<1 || strlen($_POST["summary"])<1){
            $_SESSION["error"] = "All fields are required";
            header("Location: $url/edit.php?profile_id=". $_POST["profile_id"]);
            die();
        }

        if(strpos($_POST["email"],"@") == false){
            $_SESSION["error"] = "Email must have an @";
            header("Location: $url/edit.php?profile_id=". $_POST["profile_id"]);
            die();
        }

        $sql = "UPDATE profile SET  first_name = :fn,
                                    last_name = :ln,
                                    email = :em,
                                    headline = :he,
                                    summary = :su
                                    
                                    WHERE
                                    profile_id = :profile_id";

        $stmt = $conn->prepare($sql);
        $stmt->execute(
            array(
                ':profile_id' => $_POST['profile_id'],
                ':fn' => $_POST['first_name'],
                ':ln' => $_POST['last_name'],
                ':em' => $_POST['email'],
                ':he' => $_POST['headline'],
                ':su' => $_POST['summary'])
        );
        $_SESSION["success"] = "Profile updated successfully";
        header("Location: $url/index.php");
        die();
    }

    //CHECKING IF THE PROFILE IS VALID
    $sql = "SELECT * FROM profile WHERE profile_id = :profile_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(array(":profile_id" => $_GET['profile_id']));
    $filas = $stmt->fetch(PDO::FETCH_ASSOC);

    if($filas == false){
        $_SESSION['error'] = "Incorrect profile_id value";
        header("Location: index.php");
        die();
    }

    $fn = htmlentities($filas["first_name"]);
    $ln = htmlentities($filas["last_name"]);
    $em = htmlentities($filas["email"]);
    $he = htmlentities($filas["headline"]);
    $su = htmlentities($filas["summary"]);
    $profile_id = $filas["profile_id"];
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
        <h1>Editing Profile for UMSI</h1>

        <?php
            if(isset($_SESSION["error"])){
                echo('<p style="color:red;">'.$_SESSION["error"]);
                unset($_SESSION["error"]);
            }
        ?>

        <form method="post" action="edit.php">
            <p>First Name:
            <input type="text" name="first_name" size="60"
            value="<?php echo $fn?>"
            /></p>

            <p>Last Name:
            <input type="text" name="last_name" size="60"
            value="<?php echo $ln?>"
            /></p>

            <p>Email:
            <input type="text" name="email" size="30"
            value="<?php echo $em?>"
            /></p>

            <p>Headline:<br/>
            <input type="text" name="headline" size="80"
            value="<?php echo $he?>"
            /></p>

            <p>Summary:<br/>
            <textarea name="summary" rows="8" cols="80" rows="20" style="resize:none;"><?php echo $su?></textarea>

            <p>
            <input type="hidden" name="profile_id"
            value="<?php echo $profile_id?>"
            />

            <input type="submit" name = "save" value="Save">
            <input type="submit" name="cancel" value="Cancel">

            </p>
        </form>
    </div>
</body>
</html>
