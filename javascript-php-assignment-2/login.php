<?php
    require_once("pdo_con.php");
    session_start();

    //Indicates the correct re-direction in our server
    $host = $_SERVER['HTTP_HOST'];
    $rute = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $url = "http://$host$rute";

    if(isset($_POST["cancel"])){
        header("Location: $url/index.php");
        die();
    }

    //Fields completed correctly and data verification
    if(isset($_POST["email"]) && isset($_POST["pass"])){
        unset($_SESSION["name"]);
        unset($_SESSION["user_id"]);

        //Password concatenation
        $salt = "XyZzy12*_";
        $check = hash("md5", $salt.$_POST["pass"]);
        
        $stmt = $conn->prepare(
            "SELECT user_id, name FROM users WHERE email = :em AND password =:pw"
        );
        $stmt->execute(array(':em' => $_POST['email'], ':pw' => $check));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if(row !== false){
            error_log("new Loged succesfully ".$_POST['email']."\n",3,"logs.log");
            $_SESSION["user_id"] = $row["user_id"];
            $_SESSION["name"] = $row["name"];
            header("Location: $url/index.php");
            die();
        }else{
            $_SESSION["error"] = "Incorrect email or password";
            error_log("Login fail ".$_POST['email']."$check"."\n",3,"logs.log");
            header("Location: $url/login.php");
            die();
        }

    }


?>

<!DOCTYPE html>
<html>
<head>
    <title>Rafael Santiago Ñontol Lozano</title>

    <link rel="stylesheet" 
        href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" 
        integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" 
        crossorigin="anonymous">

    <link rel="stylesheet" 
        href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" 
        integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" 
        crossorigin="anonymous">

</head>
<body>
    <div class="container">
        <h1>Please Log In</h1>

        <?php
            if(isset($_SESSION["error"])){
                echo('<p style="color : red;">' . $_SESSION["error"]);
                unset($_SESSION["error"]);
            }
        ?>

        <form method="POST" action="login.php">
            <label for="email">Email</label>
            <input type="text" name="email" id="email"><br/>
            <label for="id_1723">Password</label>
            <input type="password" name="pass" id="id_1723"><br/>

            <input type="submit" onclick="return doValidate();" value="Log In">
            <input type="submit" name="cancel" value="Cancel">
        </form>
        <p>

        <!-- Hint: 
        The account is umsi@umich.edu
        The password is the three character name of the 
        programming language used in this class (all lower case) 
        followed by 123. -->

        </p>
        <script>
        function doValidate() {
            console.log('Validating...');
            try {
                addr = document.getElementById('email').value;
                pw = document.getElementById('id_1723').value;
                console.log("Validating addr="+addr+" pw="+pw);
                if (addr == null || addr == "" || pw == null || pw == "") {
                    alert("Both fields must be filled out");
                    return false;
                }
                if ( addr.indexOf('@') == -1 ) {
                    alert("Invalid email address");
                    return false;
                }
                return true;
            } catch(e) {
                return false;
            }
            return false;
        }
        </script>

    </div>
</body>
