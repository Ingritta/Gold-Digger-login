<?php

session_start();

if (!isset($_SESSION['zalogowany'])) {
  header('Location: index.php');
  exit();
}

require_once 'database.php';

mysqli_report(MYSQLI_REPORT_STRICT);

try {
  $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
  if ($polaczenie->connect_errno != 0) {
    throw new Exception(mysqli_connect_errno());
  } else {

    $wszystko_OK = true;

    $userId = $_SESSION['id'];

    $date = $_POST['date'];
    if ($date == '') {
      $wszystko_OK = false;
      $_SESSION['e_date'] = "Proszę wybrać datę!";
    }

    if ($date < '2000-01-01') {
      $wszystko_OK = false;
      $_SESSION['e_date'] = "Podano niprawidłową datę!";
    }

    if ($startDate > $date) {
      $wszystko_OK = false;
      $_SESSION['e_date'] = "Data znajduje się poza zakresem!";
    }

    $amount = $_POST['amount'];
    if ($amount == '') {
      $wszystko_OK = false;
      $_SESSION['e_amount'] = "Proszę wpisać ilość!";
    }

    $choise = $_POST['category'];

    if ($amount <= 0) {
      $wszystko_OK = false;
      $_SESSION['e_amount'] = "Proszę wpisać liczbę większą od zera!";
    }

    $comment = $_POST['comment'];
    $pattern = '/[^\wżźćńółęąśŻŹĆĄŚĘŁÓŃ ]/i';
    $result = preg_match($pattern, $comment);

    if ($result == 1) {
      $wszystko_OK = false;
      $_SESSION['e_income_comment'] = "Proszę używać wyłącznie liter i cyfr i spacji!";
    }

    $_SESSION['fr_date'] = $date;
    $_SESSION['fr_amount'] = $amount;
    $_SESSION['fr_comment'] = $comment;

    $usersQuery = $db->query('SELECT name FROM incomes_category_assigned_to_users');
    $category = $usersQuery->fetchAll();

    if ($wszystko_OK == true) {
      if (
        $db->query("INSERT INTO incomes VALUES (
        NULL,
        '$userId',
        '$date',
        '$amount',
        '$choise',
        '$comment'
        )")
      ) {
        header('Location: successDataChange.php'); {
          $_SESSION['fr_date'] = '';
          $_SESSION['fr_amount'] = '';
          $_SESSION['fr_comment'] = '';
          throw new Exception($polaczenie->error);
        }
      }
    } else {
      $_SESSION['blad'] = '<span style="color:red">Proszę podać wszystkie dane</span>';
      header('Location: addIncome.php');
    }
  }
  $polaczenie->close();

} catch (Exception $e) {
  echo '<span style="color:red">Błąd serwera. Przepraszamy. </span>';
  echo '<br />Iformacja developerska: ' . $e;
}

