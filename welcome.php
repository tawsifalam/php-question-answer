<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
      <div class="jumbotron">
        <h1 class="display-4">Hi, <b><?php echo htmlspecialchars($_SESSION["full_name"]); ?></b></h1>
        <p class="lead">Ask questions if you are confused about any topic or start answering existing questions if you think you know the answer.</p>
        <hr class="my-4">
        <p class="lead">
          <a href="logout.php" class="btn btn-danger btn-lg" role="button">Sign Out</a>
        </p>
        <a role="button" class="btn btn-link" href="question-form.php">Ask Question</a>
        <a role="button" class="btn btn-link" href="questions.php">Answer a Question</a>
      </div>
    </div>
</body>
</html>