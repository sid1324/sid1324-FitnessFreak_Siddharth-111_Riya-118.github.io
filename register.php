<?php

$db_host = 'localhost';
$db_name = 'FitnessFreak';
$db_user = 'root';     
$db_pass = '';    


try {
    $dbh = new PDO("mysql:host=$db_host", $db_user, $db_pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql_create_db = "CREATE DATABASE IF NOT EXISTS $db_name";
    $dbh->exec($sql_create_db);

    $dbh->exec("use $db_name");

    $sql_create_table = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL,
        gender VARCHAR(10) NOT NULL,
        dob DATE NOT NULL,
        phone VARCHAR(15) NOT NULL
    )";
    $dbh->exec($sql_create_table);

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}




$name= $email= $gender= $branch= $dob=$phone= $hons='';
$nameErr= $emailErr= $genderErr= $pass1Err= $dobErr=$phoneErr=$pass2Err='';

function validate($data){
    $data=trim($data);
    $data=stripcslashes($data);
    $data= htmlspecialchars($data);
    return $data;
}

if($_SERVER["REQUEST_METHOD"]=="POST"){

    if(empty($_POST["name"])){
        $nameErr= "Name is required";

    }else{
        $name=validate($_POST["name"]);
        if (!preg_match("/^[a-zA-Z]*$/",$name)){
            $nameErr="Only letters and white spaces allowed";
        }
    }

    if(empty($_POST["email"])){
        $emailErr=" Email is required";
    }else{
        $email= validate($_POST["email"]);
        if (!preg_match("/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/",$email)){
            $emailErr="Invalid email format.";
        }
    }

    if(empty($_POST["gender"])){
        $genderErr=" gender is required";
    }else{
        $gender=validate($_POST["gender"]);
    }


    $today= new DateTime();
    if(empty($_POST["dob"])){
        $dobErr="Date is required";
    }elseif($_POST["dob"]>$today){
        $dobErr="Invalid DOB";
    }else{
        $dob=validate($_POST["dob"]);
    }

    if(empty($_POST["phone"])){
        $phoneErr="Phone number is required";
    }else{
        $phone=validate($_POST["phone"]);
        if(!preg_match("/^[789]{1}[0-9]{9}$/", $phone)){
            $phoneErr="Invalid phone number";
        }
    }

    $password = trim($_POST["password"]);
if (empty($password)) {
$pass1Err = "Password is required.";
}elseif (strlen($password) < 8) {

$pass1Err = "Password must be at least 8 characters long.";
} elseif (!preg_match("/[a-zA-Z]/", $password)) {
$pass1Err = "Password must contain at least one
alphabet character.";
} elseif (!preg_match("/\d/", $password)) {
$pass1Err = "Password must contain at least one digit.";
} elseif (!preg_match("/[!@#$%^&*()\-_=+{}[\]|;:'\",.<>\/?]/", $password)) {
$pass1Err = "Password must contain at least one
special character.";
}

$cpassword = trim($_POST["cpassword"]);
if ($password!=$cpassword) {
$pass2Err = "Both the passwords should be matching.";
}

   

}

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($nameErr) && empty($emailErr) && empty($genderErr) && empty($dobErr) && empty($phoneErr) && empty($branchErr) && empty($honsErr)) {
    $stmt = $dbh->prepare("INSERT INTO users (name, email, password, gender, dob, phone) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT), $gender, $dob, $phone]);

    $_SESSION['fitness'] = [
        'name' => $name,
    ];

    setcookie("gender",$gender,time()+3600);


    header("Location: afterlogin.php");
    exit();
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
    <h2> Registeration Page</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

    <div class="label-input-container">
    Name: <input type="text" name="name">
    <span class="error"><?php echo $nameErr;?></span></div>
    <br>

    <div class="label-input-container">
    Email: <input type="text" name="email">
    <span class="error"><?php echo $emailErr;?></span></div>
    <br>

    <div class="label-input-container">
    Password: <input type="text" name="password">
    <span class="error"><?php echo $pass1Err;?></span></div>
    <br>

    <div class="label-input-container">
    Confirm Password: <input type="text" name="cpassword">
    <span class="error"><?php echo $pass2Err;?></span></div>
    <br>
    
    <div class="label-input-container">
    Gender: 
    <input type="radio" name="gender" value="male">Male
    <input type="radio" name="gender" value="female">Female
    <span class="error"><?php echo $genderErr;?></span></div>
    <br>

    <div class="label-input-container">
    Date of Birth: <input type="date" name="dob">
    <span class="error"><?php echo $dobErr;?></span></div>
    <br>

   
    <div class="label-input-container">
    Phone: <input type="text" name="phone">
    <span class="error"><?php echo $phoneErr;?></span></div>
    <br>
    
    <input type="submit" valur="Submit">
</div>


</form> 



    
</body>
</html>