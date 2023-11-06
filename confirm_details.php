<?php
session_start();
if (!isset($_SESSION["userdata"])) {
header("Location: form.php"); // Redirect back to form if session data is not available
exit;
}
$userdata = $_SESSION["userdata"];
?>
<!DOCTYPE html>
<html>
<head>
<title>User Details</title>
</head>
<body>
<h2>User Details:</h2>
<p>User ID: <?php echo $userdata["userid"]; ?></p>
<p>Email: <?php echo $userdata["email"]; ?></p>
<p>Date of Birth: <?php echo $userdata["dob"]; ?></p>
<p>Phone Number: <?php echo $userdata["phone"]; ?></p>
<p>Password: <?php echo $userdata["password"]; ?></p>
<p>Subscription: <?php echo $userdata["subscribe"] ? "Subscribed"
: "Not Subscribed"; ?></p>
<p>Gender: <?php echo $userdata["gender"]; ?></p>
<p>Batch: <?php echo $userdata["batch"]; ?></p>
<p>Comments: <?php echo $userdata["comments"]; ?></p>

    <form action="your_target_page.html" method="post">
        <input type="submit" value="Submit">
    </form>


</body>
</html>