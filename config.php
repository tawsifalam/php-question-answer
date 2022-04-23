<?php
  /* Database credentials. Assuming you are running MySQL
  server with default setting (user 'root' with no password) */

  define('DB_SERVER', 'localhost');
  define('DB_USERNAME', 'root');
  define('DB_PASSWORD', '');
  define('DB_NAME', 'qa');

  /* Attempt to connect to MySQL database */
  $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

  // Check connection
  if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
  }

  $user_query = 'show tables like "users"';
  $user_result = mysqli_query($link, $user_query);


  $user_query = "CREATE TABLE IF NOT EXISTS users (
            id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
            full_name VARCHAR(255) NOT NULL, 
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
          )";
  $user_result = mysqli_query($link, $user_query);

  $question_query = "CREATE TABLE IF NOT EXISTS questions (
            id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
            description VARCHAR(255) NOT NULL,
            user_id INT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id)
              REFERENCES users(id)
          )";
  $question_result = mysqli_query($link, $question_query);

  $answer_query = "CREATE TABLE IF NOT EXISTS answers (
            id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
            description VARCHAR(255) NOT NULL,
            user_id INT NOT NULL,
            question_id INT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id)
              REFERENCES users(id),
            FOREIGN KEY (question_id)
              REFERENCES questions(id)
          )";
  $answer_result = mysqli_query($link, $answer_query);


?>