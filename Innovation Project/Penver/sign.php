<?php
# Include connection
require_once "database/config.php";

# Define variables and initialize with empty values
$username_err = $email_err = $password_err = $password_confirm_err = "";
$username = $email = $password = $password_confirm = "";

# Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
  # Validate username
  if (empty(trim($_POST["username"]))) 
  {
    $username_err = "Veuillez entrer un nom d'utilisateur.";
  } 
  else 
  {
    $username = trim($_POST["username"]);
    if (!ctype_alnum(str_replace(array("@", "-", "_"), "", $username))) 
	{
      $username_err = "Le nom d'utilisateur ne peut contenir que des lettres, des nombres ou des symboles tels que '@', '_', or '-'.";
    } 
	else 
	{
      # Prepare a select statement
      $sql = "SELECT id_user FROM user WHERE Pseudo = ?";

      if ($stmt = mysqli_prepare($link, $sql)) 
	  {
        # Bind variables to the statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_username);

        # Set parameters
        $param_username = $username;

        # Execute the prepared statement 
        if (mysqli_stmt_execute($stmt)) 
		{
          # Store result
          mysqli_stmt_store_result($stmt);

          # Check if username is already registered
          if (mysqli_stmt_num_rows($stmt) == 1) 
		  {
            $username_err = "Cet utilisateur a déjà été choisi.";
          }
        } 
		else 
		{
          echo "<script>" . "alert('Oops! Il y a eu un problème. Veuillez réessayer plus tard.')" . "</script>";
        }

        # Close statement 
        mysqli_stmt_close($stmt);
      }
    }
  }

  # Validate email 
  if (empty(trim($_POST["email"]))) 
  {
    $email_err = "Veuillez entrer une adresse mail valide";
  } 
  else 
  {
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
	{
      $email_err = "Veuillez entrer une adresse mail valide.";
    } 
	else 
	{
      # Prepare a select statement
      $sql = "SELECT id_user FROM user WHERE Mail = ?";

      if ($stmt = mysqli_prepare($link, $sql)) 
	  {
        # Bind variables to the statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_email);

        # Set parameters
        $param_email = $email;

        # Execute the prepared statement 
        if (mysqli_stmt_execute($stmt)) 
		{
          # Store result
          mysqli_stmt_store_result($stmt);

          # Check if email is already registered
          if (mysqli_stmt_num_rows($stmt) == 1) 
		  {
            $email_err = "Cet adresse mail a déjà été utilisée.";
          }
        } else 
		{
          echo "<script>" . "alert('Oops! Il y a eu un problème. Veuillez réessayer plus tard.');" . "</script>";
        }

        # Close statement
        mysqli_stmt_close($stmt);
      }
    }
  }

  # Validate password
  if (empty(trim($_POST["password"]))) 
  {
    $password_err = "Veuillez entrer un mot de passe.";
  } else {
    $password = trim($_POST["password"]);
    if (strlen($password) < 8) {
      $password_err = "Le mot de passe doit contenir au moins 8 caractères.";
    }
  }

  if (empty(trim($_POST["password_confirm"])))
  {
    $password_confirm_err = "Veuillez confirmer votre mot de passe.";
  } else {
    $password_confirm = trim($_POST["password_confirm"]);
    if (strlen($password_confirm) < 8) {
      $password_confirm_err = "Le mot de passe doit être le même.";
    }
  }

  # Check input errors before inserting data into database
  if (empty($username_err) && empty($email_err) && empty($password_err) && empty($password_confirm_err)) {
    # Prepare an insert statement
    $sql = "INSERT INTO user(Pseudo, Mail, MDP) VALUES (?, ?, ?)";

    if ($stmt = mysqli_prepare($link, $sql)) {
      # Bind varibales to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_email, $param_password);

      # Set parameters
      $param_username = $username;
      $param_email = $email;
      $param_password = password_hash($password, PASSWORD_DEFAULT);

      # Execute the prepared statement
      if (mysqli_stmt_execute($stmt)) {
        echo "<script>" . "window.location.href='login.php';" . "</script>";
        exit;
      } else {
        echo "<script>" . "alert('Oops! Il y a eu un problème. Veuillez réessayer plus tard.');" . "</script>";
      }

      # Close statement
      mysqli_stmt_close($stmt);
    }
  }

  # Close connection
  mysqli_close($link);
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Inscription - Penver</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
        <link rel="stylesheet" href="styles/login.css">
        <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    </head>
    <body>
        <!-- Posts -->
        <article class="sign">
            <img src="images/favicon.ico" style="text-align: center" width="64px" height="64px">
            <h1>Inscription</h1>
            <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" novalidate>
                <label for="email">Adresse mail</label><br>
                <input type="email" id="email" name="email"><br><br>
                <small><?= $email_err; ?></small>
                <label for="username">Nom d'utilisateur: </label><br>
                <input type="text" id="username" name="username"><br><br>
                <small><?= $username_err; ?></small>
                <label for="password">Mot de passe:</label><br>
                <input type="password" id="password" name="password"><br>
                <small><?= $password_err; ?></small><br>
                <label for="password_confirm">Confirmation du mot de passe:</label><br>
                <input type="password" id="password_confirm" name="password_confirm"><br>
                <small><?= $password_confirm_err; ?></small><br>
                <span style="font-size: 10px; text-align: left;"><a href="reset_password.html">Mot de passe oublié?</a></label><br>
                <input type="submit" value="Inscription" style="text-align: center">
              </form> 
        </article>
    </body>
</html>