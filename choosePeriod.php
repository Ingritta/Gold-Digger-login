<?php

session_start();

if (!isset($_SESSION['zalogowany'])) {
  header('Location: index.php');
  exit();
}

if (!isset($_SESSION['logged_id'])) {

  $userId = $_SESSION['id'];

  require_once 'database.php';

  mysqli_report(MYSQLI_REPORT_STRICT);

  try {
    $connection = new mysqli($host, $db_user, $db_password, $db_name);
    if ($connection->connect_errno != 0) {
      throw new Exception(mysqli_connect_errno());
    } else {

      $wszystko_OK = true;

      $date = new DateTime();

      $date = date('Y') . "-" . date('m') . "-" . date('d');

      $startDate = $_POST['startDate'];
      $endDate = $_POST['endDate'];

      $_SESSION['startDate'] = $startDate;
      $_SESSION['endDate'] = $endDate;

      if ($startDate == '') {
        $wszystko_OK = false;
        $_SESSION['e_date'] = "Proszę wybrać datę!";
      }

      if ($endDate == '') {
        $wszystko_OK = false;
        $_SESSION['e_endDate'] = "Proszę wybrać datę!";
      }

      if ($startDate < '2000-01-01') {
        $wszystko_OK = false;
        $_SESSION['e_date'] = "Podano nieprawidłową datę!";
      }

      if ($endDate < '2000-01-01') {
        $wszystko_OK = false;
        $_SESSION['e_endDate'] = "Podano nieprawidłową datę!";
      }

      if ($startDate > $endDate) {
        $wszystko_OK = false;
        $_SESSION['e_endDate'] = "Data końcowa powinna być późniejsza od daty początkowej!";
      }

      if ($startDate > $date) {
        $wszystko_OK = false;
        $_SESSION['e_date'] = "Data znajduje się poza zakresem!";
      }

      if ($endDate > $date) {
        $wszystko_OK = false;
        $_SESSION['e_endDate'] = "Data znajduje się poza zakresem!";
      }
    }

    header('Location: choosenPeriodBalance.php'); {
      throw new Exception($polaczenie->error);
    }

  } catch (Exception $e) {
    echo '<span style="color:red">Błąd serwera. Przepraszamy. </span>';
    echo '<br />Iformacja developerska: ' . $e;
  }
}

