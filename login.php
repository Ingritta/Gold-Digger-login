<?php

session_start();

if ((!isset($_POST['user'])) || (!isset($_POST['haslo']))) {
  header('Location: signin.php');
  unset($_SESSION['udanarejestracja']);
  exit();
}

require_once 'database.php';

mysqli_report(MYSQLI_REPORT_STRICT);

try {
  $connection = new mysqli($host, $db_user, $db_password, $db_name);

  if ($connection->connect_errno != 0) {
    throw new Exception(mysqli_connect_errno());

  } else {
       
    // $currantDate = new DateTime();
    // $currantDate->format('Y-m-d');

    $login = $_POST['user'];
    $password = $_POST['haslo'];

    $login = htmlentities($login, ENT_QUOTES, "UTF-8");

    if (
      $result = $connection->query(
        sprintf(
          "SELECT * FROM users WHERE user='%s'",
          mysqli_real_escape_string($connection, $login)
        )
      )
    ) {
      $num_users = $result->num_rows;

      if ($num_users > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['pass'])) {
          $_SESSION['zalogowany'] = true;
          $_SESSION['id'] = $row['id'];
          $_SESSION['user'] = $row['user'];
          $_SESSION['email'] = $row['email'];

          unset($_SESSION['blad']);
          $result->close();

          header('Location: successLogin.php');

        } else {
          $_SESSION['blad'] = '<span style="color:red">Nieprawidłowy login lub hasło</span>';
          header('Location: signin.php');
        }

      } else {
        $_SESSION['blad'] = '<span style="color:red">Nieprawidłowy login lub hasło</span>';
        header('Location: signin.php');
      }

    } else {
      throw new Exception($connection->error);
    }
    $connection->close();
  }

} catch (Exception $e) {
  echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o wizytę w innym terminie!</span>';
  echo '<br />Informacja developerska: ' . $e;
}