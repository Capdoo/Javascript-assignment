<?php

    session_start();
    require_once "pdo_con.php";

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

    function validatePos(){
        for($i=1; $i <= 9; $i++){
            if(!isset($_POST['year'.$i])) continue;
            if(!isset($_POST['year'.$i])) continue;
            
            $year = $_POST['year'.$i];
            $desc = $_POST['desc'.$i];

            if(strlen($year) == 0 || strlen($desc) == 0){
                return "All fields are required";
            }

            if(!is_numeric($year)){
                return "Position year must be numeric";
            }
        }
        return true;
    }


    //SQL SENTENCE FOR UPDATE BY 'PROFILE ID' FORM POST METHOD
    if(isset($_POST["first_name"]) && isset($_POST["last_name"]) && isset($_POST["email"])){
        if(strlen($_POST["first_name"])<1 || strlen($_POST["last_name"])<1 || strlen($_POST["email"])<1 || strlen($_POST["headline"])<1 || strlen($_POST["summary"])<1){
            
            $_SESSION["error"] = "All fields are required";
            header("Location: $url/edit.php?profile_id=". $_POST["profile_id"]);
            die();

        }elseif(strpos($_POST["email"],"@") == false){

            $_SESSION["error"] = "Email must have an @";
            header("Location: $url/edit.php?profile_id=". $_POST["profile_id"]);

        }elseif(!validatePos()){
            $_SESSION['error'] = validatePos();
            header("Location: $url/edit.php?profile_id=". $_POST["profile_id"]);
            die();

        }else{
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
                    ':profile_id' => $_GET['profile_id'],
                    ':fn' => $_POST['first_name'],
                    ':ln' => $_POST['last_name'],
                    ':em' => $_POST['email'],
                    ':he' => $_POST['headline'],
                    ':su' => $_POST['summary'])
                );

                $_SESSION["success"] = "Profile updated successfully";

                $stmt = $conn->prepare('DELETE FROM Position WHERE profile_id =:pid');
                $stmt->execute(array(':pid' => $_REQUEST['profile_id']));

                $rank = 1;
                for($i=1; $i<=9; $i++){
                    if( !isset($_POST['year'.$i])) continue;
                    if( !isset($_POST['desc'.$i])) continue;

                    $year = $_POST['year'.$i];
                    $desc = $_POST['desc'.$i];

                    $stmt = $conn->prepare('INSERT INTO Position 
                    (profile_id, rank, year, description)
                    VALUES(:pid, :rank, :year, :desc)');

                    $stmt->execute(array(
                        ':pid' => $_REQUEST['profile_id'],
                        ':rank' => $rank,
                        ':year' => $year,
                        ':desc' => $desc)
                    );
                    $rank++;
                }

                $_SESSION['success'] = 'Record updated';
                header("Location: $url/index.php");
                return;
        }
    }

    if (!isset($_GET['profile_id'])) {
        $_SESSION['error'] = "Missing profile_id";
        header('Location: index.php');
        return;
    }
  

    $sql = "SELECT * FROM profile WHERE profile_id = :xyz";
    $stmt = $conn->prepare($sql);
    $stmt->execute(array(":xyz" => $_GET['profile_id']));
    $filas = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM Position WHERE profile_id = :xyz");
    $stmt->execute(array(":xyz" => $_GET['profile_id']));
    $rowOfPosition = $stmt->fetchAll();

    if($filas == false){
        $_SESSION["error"] = "Incorrect profile_id value";
        error_log("Este es SESSION,user _ id ".$_SESSION["user_id"]."$check"."\n",3,"logs.log");
        error_log("Este es POST profile _ id ".$_POST["profile_id"]."$check"."\n",3,"logs.log");
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
    <script type="text/javascript" src="jquery.min.js"></script>
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
            </p>

            <p>
                <!--Position: <input type="submit" id="addPos" value="+" href="edit.php?profile_id='<?php echo $profile_id ?>'">  -->
                Position: <input type="submit" id="addPos" value="+" >

                <?php error_log("Este es el GET profile id  del HTML: ".$_GET['profile_id']."\n",3,"logs.log"); ?>
                <div id="position_fields">
                    <?php
                        $rank = 1;
                        foreach($rowOfPosition as $row){
                            echo "<div id=\"position" . $rank . "\" >
                            <p>Year: <input type=\"text\" name=\"year1\" value=\"".$row['year']."\">
                            <input type=\"button\" value=\"-\" onclick=\"$('#position". $rank ."').remove();return false;\"></p>
                            <textarea name=\"desc".$rank. "\"').\" rows=\"8\" cols=\"80\">".$row['description']."</textarea>
                            </div>";

                            $rank++;
                        }?>
                </div>
                
                <input type="submit" name = "save" value="Save">
                <input type="submit" name="cancel" value="Cancel">
            </p>
        </form>

        <p>

        <script>
            countPos = 0;
            $(document).ready(function(){
                window.console && console.log('Document ready called');
                $('#addPos').click(function(event){
                    event.preventDefault();
                    if(countPos >= 9){
                        alert("Maximum of nine position entries exceeded");
                        return;
                    }
                    countPos++;
                    window.console && console.log ("Adding position process" + countPos);
                    $('#position_fields').append(
                            '<div id="position' + countPos + '"> \
                            <p>Year: <input type="text" name="year'+countPos+'" value="" /> \
                            <input type="button" value="-" \
                                onclick="$(\'#position' + countPos + '\').remove();return false;"></p> \
                                <textarea name="desc' + countPos + '" rows="8" cols="80"></textarea> \
                            </div>');
                });
            })
        </script>

    </div>
</body>
</html>
