<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$description = "";
$description_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
  // Check if username is empty
  if(empty(trim($_POST["description"]))){
    $description_err = "Please enter a question.";
  } else{
    $description = trim($_POST["description"]);
  }

  if(empty($description_err)){
    // Prepare an insert statement
    $sql = "INSERT INTO questions (description, user_id) VALUES (?, ?)";

    if($stmt = mysqli_prepare($link, $sql)){
      // Bind variables to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "si", $param_description, $param_user_id);
      
      // Set parameters
      $param_description = $description;
      $param_user_id = $_SESSION["id"];
      
      // Attempt to execute the prepared statement
      if(mysqli_stmt_execute($stmt)){
          // Redirect to login page
          header("location: questions.php");
      } else{
          echo "Oops! Something went wrong. Please try again later.";
      }

      // Close statement
      mysqli_stmt_close($stmt);
    }

  }

  // Close connection
  mysqli_close($link);
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
      <a role="button" class="btn btn-link" href="welcome.php">Home</a>
      <a role="button" class="btn btn-link" href="questions.php">Answer a Question</a>

      <h1>Type your question</h1>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
          <label for="description">Question</label>
          <textarea name="description" class="form-control <?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>" id="description" rows="3" value="<?php echo $description; ?>"></textarea>
          <span class="invalid-feedback"><?php echo $description_err; ?></span>
        </div>
        <div class="form-group">
          <input type="submit" class="btn btn-primary" value="Submit">
        </div>
      </form>
    </div>
</body>
</html>