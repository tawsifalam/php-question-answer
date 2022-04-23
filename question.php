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

$id = $_GET['id'];

// Define variables and initialize with empty values
$description = "";
$description_err = "";


// Prepare a select statement
$sql = "SELECT id, description, user_id FROM questions WHERE id = ?";
$answers_sql  = "SELECT id, description, user_id FROM answers WHERE question_id = ?";

if ($stmt = mysqli_prepare($link, $sql)){
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "i", $param_id);

    // Set parameters
    $param_id = $id;

    // Attempt to execute the prepared statement
    if(mysqli_stmt_execute($stmt)){
        // Store result
        mysqli_stmt_store_result($stmt);

        mysqli_stmt_bind_result($stmt, $q_id, $q_description, $q_u_id);
        mysqli_stmt_fetch($stmt);
    }
}

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Check if username is empty
    if(empty(trim($_POST["description"]))){
      $description_err = "Please enter a answer.";
    } else{
      $description = trim($_POST["description"]);
    }
  
    if(empty($description_err)){
      // Prepare an insert statement
      $sql = "INSERT INTO answers (description, user_id,  question_id) VALUES (?, ?, ?)";
  
      if($answer_post_stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($answer_post_stmt, "sii", $param_description, $param_user_id, $param_question_id);
        
        // Set parameters
        $param_description = $description;
        $param_user_id = $_SESSION["id"];
        $param_question_id = $_GET['id'];
        
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($answer_post_stmt)) {
            // Redirect to login page
            header("Refresh:0: url=question.php?id=" . $id);
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
  
        // Close statement
        mysqli_stmt_close($answer_post_stmt);
      }
  
    }
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
        <a role="button" class="btn btn-link" href="question-form.php">Ask Question</a>
        
        <?php if(!empty($id)){?>
            <h1><?php  echo $q_description; ?> </h1>
        <?php } else { ?>
            <p> No question found  with id <?php echo $id; ?></p>
        <?php } ?>
        <form method="post">
            <div class="form-group">
                <label for="description">Answer</label>
                <textarea name="description" class="form-control <?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>" id="description" rows="3" value="<?php echo $description; ?>"></textarea>
                <span class="invalid-feedback"><?php echo $description_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit Your Answer">
            </div>
        </form>

        <h1>All Answers</h1>
        <?php 
            if ($answers_stmt = mysqli_prepare($link, $answers_sql)) {
            mysqli_stmt_bind_param($answers_stmt, "i", $param_q_id);
            // Set parameters
            $param_q_id = $id;
            
        ?>
            <?php if (mysqli_stmt_execute($answers_stmt)){ ?>
                <div class="list-group">
                    <?php
                        mysqli_stmt_bind_result($answers_stmt, $col1, $ll, $col3);
                        while (mysqli_stmt_fetch($answers_stmt)) { ?>
                        <button class="list-group-item list-group-item-action"><?php echo $ll; ?></button>
                    <?php } ?>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</body>
</html>

<?php
    mysqli_close($link);
?>