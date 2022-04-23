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

// Prepare a select statement
$sql = "SELECT id, description, user_id FROM questions ORDER BY id DESC";

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


      <h1>All Questions</h1>
        <?php if ($stmt = mysqli_prepare($link, $sql)){ ?>
            <?php if(mysqli_stmt_execute($stmt)){ ?>
                <div class="list-group">
                    <?php
                        mysqli_stmt_bind_result($stmt, $col1, $col2, $col3);
                        while (mysqli_stmt_fetch($stmt)) { ?>
                        <a class="list-group-item list-group-item-action" href="<?php echo 'question.php?id=' . $col1; ?>"><?php echo $col2; ?></a>
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