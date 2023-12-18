<?php
# Initialize session
session_start();

# Check if user is already logged in, If yes then redirect him to index page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == TRUE) {
  echo "<script>" . "window.location.href='index.php'" . "</script>";
  exit;
}

# Include connection
require_once "database/config.php";

# Define variables and initialize with empty values
$username_err = $user_password_err = $login_err = "";
$username = $user_password = "";

# Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty(trim($_POST["username"]))) {
    $username_err = "Veuillez entrer votre nom d'utilisateur ou votre adresse mail.";
  } else {
    $username = trim($_POST["username"]);
  }

  if (empty(trim($_POST["password"]))) {
    $user_password_err = "Veuillez entrer votre mot de passe.";
  } else {
    $user_password = trim($_POST["password"]);
  }

  # Validate credentials 
  if (empty($username_err) && empty($user_password_err)) {
    # Prepare a select statement
    $sql = "SELECT id_user, Pseudo, MDP FROM user WHERE Pseudo = ? OR Mail = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
      # Bind variables to the statement as parameters
      mysqli_stmt_bind_param($stmt, "ss", $param_user_login, $param_user_login);

      # Set parameters
      $param_user_login = $username;

      # Execute the statement
      if (mysqli_stmt_execute($stmt)) {
        # Store result
        mysqli_stmt_store_result($stmt);

        # Check if user exists, If yes then verify password
        if (mysqli_stmt_num_rows($stmt) == 1) {
          # Bind values in result to variables
          mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);

          if (mysqli_stmt_fetch($stmt)) {
            # Check if password is correct
            if (password_verify($user_password, $hashed_password)) {

              # Store data in session variables
              $_SESSION["id_user"] = $id;
              $_SESSION["Pseudo"] = $username;
              $_SESSION["loggedin"] = TRUE;

              # Redirect user to index page
              echo "<script>" . "window.location.href='index.php'" . "</script>";
              exit;
            } else {
              # If password is incorrect show an error message
              $login_err = "L'email ou le mot de passe que vous avez renseigné est invalide.";
            }
          }
        } else {
          # If user doesn't exists show an error message
          $login_err = "Nom d'utilisateur, email ou mot de passe invalide.";
        }
      } else {
        echo "<script>" . "alert('Oops! Il y a eu un problème. Veuillez réessayer plus tard.');" . "</script>";
        echo "<script>" . "window.location.href='login.php'" . "</script>";
        exit;
      }

      # Close statement
      mysqli_stmt_close($stmt);
    }

  # Close connection
  mysqli_close($link);
  }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Connexion - Penver</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
        <link rel="stylesheet" href="styles/login.css">
        <link rel="icon" type="image/x-icon" href="images/favicon.ico">
        <script defer src="js/log.js"></script>
    </head>
    <body>
        <!-- Posts -->
        <article class="login" style="padding-top: 16px;">
            <img src="images/favicon.ico" style="text-align: center" width="64px" height="64px">
            <h1>Connectez-vous sur Penver</h1>
            <?php
            if (!empty($login_err)) {
            echo "<div>" . $login_err . "</div>";
            }
            ?>
            <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" novalidate>
                <label for="username">Nom d'utilisateur ou email: </label><br>
                <input type="text" id="username" name="username" value=<?= $username; ?>><br>
                <small><?= $username_err; ?></small><br>
                
                <label for="password">Mot de passe:</label><br>
                <input type="password" id="password" name="password"><br>
                <small><?= $user_password_err; ?></small>

                <input type="checkbox" id="toggle_password">
                <label for="toggle_password">Afficher MDP</label><br>

                <span style="font-size: 10px; text-align: left;"><a href="reset_password.php">Mot de passe oublié?</a></label><br>
                <span style="font-size: 10px;"><a href="sign.php">Pas de compte ? S'inscrire</a></label><br><br>
                <input type="submit" value="Se connecter" style="text-align: center">
              </form> 
        </article>
    </body>
</html>