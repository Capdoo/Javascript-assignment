<?php 

    require_once "pdo_con.php";
    //Set the current user session
    session_start();

        //Indicates the correct direction in our server
        $host = $_SERVER['HTTP_HOST'];
        $rute = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $url = "http://$host$rute";

    //Session validation
    if(!isset($_SESSION["user_id"])){
        die("User not loged");
    }

    if(isset($_POST["cancel"])){
        header("Location: $url/index.php");
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
        
    //Data validation
    if(isset($_POST["add"])){
        error_log("entramos al if ".$_POST['first_name']."\n",3,"logs.log");
        echo "ENTRO AL ISSET :V";

        if(strlen($_POST["first_name"]) < 1 || strlen($_POST["last_name"]) < 1 || strlen($_POST["email"]) < 1 || strlen($_POST["headline"]) < 1 || strlen($_POST["summary"]) < 1){
            $_SESSION["error"] = "All fields are mandatory";
            header("Location: $url/add.php");
            die();
        }
        
        if(strpos($_POST["email"],"@") == false){
            $_SESSION["error"] = "Incorrect email, missing @";
            header("Location: $url/add.php");
            die();
        }elseif(!validatePos()){
            $_SESSION['error'] = validatePos();
        } else{

            $stmt = $conn->prepare(
            'INSERT INTO profile
            (user_id, first_name, last_name, email, headline, summary)
            VALUES ( :uid, :fn, :ln, :em, :he, :su)');
        
            $stmt->execute(array(
                ':uid' => $_SESSION['user_id'],
                ':fn' => $_POST['first_name'],
                ':ln' => $_POST['last_name'],
                ':em' => $_POST['email'],
                ':he' => $_POST['headline'],
                ':su' => $_POST['summary'])
            );
            
            $profile_id = $conn->lastInsertId();

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
            $rank++;

            $_SESSION["sucess"] = "Profile added succesfully";
            header("Location: $url/index.php");
            die();
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Rafael Santiago Ñontol Lozano</title>

        <?php require_once "bootstrap.php"; ?>

        <link rel="stylesheet" 
        href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" 
        integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" 
        crossorigin="anonymous">

        <link rel="stylesheet" 
        href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" 
        integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" 
        crossorigin="anonymous">

        <script type="text/javascript" src="jquery.min.js"></script>

    </head>
    <body>
        <div class="container">
            <h1>Adding Profile for <?php echo htmlentities($_SESSION["name"]); ?></h1>

            <?php
                if(isset($_SESSION["error"])){
                    echo('<p style="color : red;">' . $_SESSION["error"]);
                    unset($_SESSION["error"]);
                }
            ?>

            <form method="post">
                <p>First Name:
                <input type="text" name="first_name" size="60"/></p>

                <p>Last Name:
                <input type="text" name="last_name" size="60"/></p>

                <p>Email:
                <input type="text" name="email" size="30"/></p>

                <p>Headline:<br/>
                <input type="text" name="headline" size="80"/></p>

                <p>Summary:<br/>
                <textarea name="summary" rows="8" cols="80" style="resize:none;"></textarea>

                <p>
                    Position: <input type="submit" id="addPos" value="+">

                    <div id="position_fields">

                        <input type="submit" name="add" value="Add">
                        <input type="submit" name="cancel" value="Cancel">
                </p>
            </form>


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
                        window.console && console.log ("Adding position" + countPos);
                        $('#position_fields').append(
                                '<div id="position' + countPos + '"> \
                                <p>Year: <input type="text" name="year'+countPos+'" value="" /> \
                                <input type="button" value="-" \
                                    onclick="$(\'#position' + countPos + '\').remove();return false;"></p> \
                                    <textarea name="desc' + countPos + '" rows="8" cols="80"></textarea> \
                                </div>');
                    });
                });
            </script>
        </div>
    </body>
</html>