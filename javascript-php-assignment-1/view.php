<?php
    require_once "pdo_con.php";
    session_start();

    if(!isset($_GET['profile_id'])){
        $_SESSION['error'] = "Not found profile_id";
        header("Location: $url/index.php");
        die();
    }

    $sql = "SELECT * FROM profile WHERE profile_id = :profile_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(array(":profile_id" => $_GET['profile_id']));
    $fila = $stmt->fetch(PDO::FETCH_ASSOC);

    if($fila == false){
        $_SESSION['error'] = 'Incorrect value for profile_id';
        header('Location: index.php');
        die();
    }

    $fn = htmlentities($fila["first_name"]);
    $ln = htmlentities($fila["last_name"]);
    $em = htmlentities($fila["email"]);
    $he = htmlentities($fila["headline"]);
    $su = htmlentities($fila["summary"]);

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
        <h1>Profile information</h1>

        <p>First Name:<?php echo $fn?></p>
        <p>Last Name:<?php echo $ln?></p>
        <p>Email:<?php echo $em ?> </p>

        <p>
            Headline:
            <br/>
            <?php echo $he?>
        </p>

        <p>
            Summary:
            <br/>
            <?php echo $su?>
        </p>

        <a href="index.php">Done</a>
    </div>
    <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
</body>
</html>
