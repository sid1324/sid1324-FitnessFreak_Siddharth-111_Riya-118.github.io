<?php
$email = $pass = '';
$emailErr = $passErr = '';

function validate($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    // Database credentials
    $db_host = 'localhost';
    $db_name = 'FitnessFreak';
    $db_user = 'root';
    $db_pass = '';

    try {
        $dbh = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (empty($_POST["email"])){
            $emailErr = " Email is required";
        } else {
            $email = validate($_POST["email"]);
            if (!preg_match("/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/",$email)){
                $emailErr = "Invalid email format.";
            }
        }

        $password = trim($_POST["password"]);
        if (empty($password)) {
            $passErr = "Password is required.";
        } else {
            // Hash the password before comparing with the database
            $password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $dbh->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($_POST["password"], $user["password"])) {
                if (empty($emailErr) && empty($passErr)) {
                    $stmt = $dbh->prepare("SELECT name, gender FROM users WHERE email = ?");
                    $stmt->execute([$email]);
                    $user = $stmt->fetch();

                session_start();
                $_SESSION['fitness'] = [
                    'name' => $user['name'],
                ];
            
                setcookie("gender",$user['gender'],time()+3600);

                // Authentication successful
                // Redirect to a different page or perform any other actions
                header("Location: afterlogin.php");
                exit();
            }
         } else {
                $passErr = "Invalid username or password.";
            }
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <title>Registeration Page</title>
    <link rel="stylesheet" type="text/css" href="registerstyle.css">
<style>
    body{
        background-image: url("./assets/images/bg_register_login.jpg");
    }
</style>
</head>

<body>
    
<div class="form-box">
    <h2> Login Page</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <div class="label-input-container">
    <label for="email">Email:       </label><input type="text" name="email">
    <span class="error"><?php echo $emailErr;?></span></div>
    <br><br>

    <div class="label-input-container">
    <label for="password">Password: </label><input type="text" name="password">
    <span class="error"><?php echo $passErr;?></span></div>
    <br><br>
    <a href="register.php" class="registerbutton">New User</a>
    <br><br>

    
    <input type="submit" valur="Submit">
</div>


</form> 



    
</body>
</html>