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

      //$wszystko_OK = true;

      $date = new DateTime();

      $startDate = date('Y') . "-" . "%" . "-" . "%";

      $usersQuery = $db->query
      (
        "SELECT date, amount, category, comment 
        FROM incomes 
        WHERE incomes.user_id = '$userId'
        AND incomes.date LIKE '$startDate' ORDER BY incomes.date ASC"
      );

      $incomes = $usersQuery->fetchAll();

      $usersQuery = $db->query
      (
        "SELECT date, amount, category, comment, 'payment_method' 
      FROM expenses 
      WHERE expenses.user_id = '$userId'
      AND expenses.date LIKE '$startDate' ORDER BY expenses.date ASC"
      );

      $expenses = $usersQuery->fetchAll();

      $result = $connection->query(
        "SELECT SUM(incomes.amount) AS incomesSum 
      FROM incomes 
      WHERE incomes.user_id = $userId
      AND incomes.date >= '$startDate'"
      );

      $sum = $result->num_rows;
      if ($sum > 0) {
        $row = $result->fetch_assoc();
        $incomesSum = implode($row);
      }

      $result = $connection->query("SELECT
      SUM(expenses.amount) AS expensesSum 
      FROM expenses 
      WHERE expenses.user_id = $userId
      AND expenses.date >= '$startDate'");

      $sum = $result->num_rows;
      if ($sum > 0) {
        $row = $result->fetch_assoc();
        $expensesSum = implode($row);
      }

      if ($incomesSum > 0 && $expensesSum > 0) {
        $balance = $incomesSum - $expensesSum;
      } else if ($incomesSum <= 0 && $expensesSum >= 0) {
        $incomesSum = 0;
        $balance = 0 - $expensesSum;
      } else if ($incomesSum >= 0 && $expensesSum <= 0) {
        $expensesSum = 0;
        $balance = $incomesSum;
      } else if ($incomesSum <= 0 && $expensesSum <= 0) {
        $incomesSum = 0;
        $expensesSum = 0;
        $balance = 0;
      }
      $connection->close();
    }

  } catch (Exception $e) {
    echo '<span style="color:red">Błąd serwera. Przepraszamy. </span>';
    echo '<br />Iformacja developerska: ' . $e;
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Balance</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
  <link rel="stylesheet" href="./css/style.css" />

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
    crossorigin="anonymous"></script>

  <style>
    body {
      background-image: url("images/front-3.jpg");
      height: 1300px;
    }

    .py-5 {
      padding-top: 1rem !important;
      padding-bottom: 1rem !important;
    }

    .form-signin {
      max-width: 700px;
      padding: 1rem;
    }

    .my-5 {
      margin-top: 3rem !important;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-sm navbar-dark bg-dark" aria-label="Third navbar example">
    <div class="container-fluid">
      <a class="navbar-brand" href="./index.php">Gold Digger</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample03"
        aria-controls="navbarsExample03" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarsExample03">
        <ul class="navbar-nav me-auto mb-2 mb-sm-0">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">Dodaj</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="./addIncome.php">Przychód</a></li>
              <li><a class="dropdown-item" href="./addExpense.php">Wydatek</a></li>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">Przeglądaj
              bilans</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="./currentMonthBalance.php">Bieżący miesiąc</a></li>
              <li><a class="dropdown-item" href="./lastMonthBalance.php">Poprzedni miesiąc</a></li>
              <li><a class="dropdown-item" href="./currentYearBalance.php">Bieżący rok</a></li>
              <li><a class="dropdown-item" href="./choosenPeriodBalance.php">Wybór ręczny dat</a></li>
              <li><a class="dropdown-item" href="./balanceSortedByCategory.php">Według kategorii</a></li>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">Kategoria</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="./incomesCategories.php">Przychodów</a></li>
              <li><a class="dropdown-item" href="./expensesCategories.php">Wydatków</a></li>
              <li><a class="dropdown-item" href="./addCategory.php">Dodaj kategorię</a></li>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">Użytkownik</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="./editEmail.php">Zmiana adresu e-mail</a></li>
              <li><a class="dropdown-item" href="./editName.php">Zmiana imienia</a></li>
              <li><a class="dropdown-item" href="./editPassword.php">Zmiana hasła</a></li>
              <li><a class="dropdown-item" href="./removeAccount.php">Usuń konto</a></li>
            </ul>
          </li>
          <ul class="navbar-nav me-auto mb-2 mb-sm-0">
            <li class="nav-item">
              <a class="nav-link" href="./end.php">Wyloguj się</a>
            </li>
          </ul>
        </ul>
      </div>
    </div>
  </nav>

  <main class="form-signin w-100 m-auto">
    <h4 class="mb-3" style="margin-top: 1rem">Przychody</h4>
    <div>
      <table class="table table-dark table-hover">
        <tr>
          <th><span style="color: #E6B31E">Data</span></th>
          <th><span style="color: #E6B31E">Kwota</span></th>
          <th><span style="color: #E6B31E">Kategoria</span></th>
          <th><span style="color: #E6B31E">Komentarz</span></th>
          <th></th>
          <th></th>
        </tr>
        <tr>
          <?php
          foreach ($incomes as $user) {
            echo "<td>{$user['date']}</td>
              <td>{$user['amount']}</td>
              <td>{$user['category']}
              <td>{$user['comment']}</td>" ?>
            <td>
              <a href="./editIncome.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                  class="bi bi-pencil-fill" viewBox="0 0 16 16" style="color: #E6B31E">
                  <path
                    d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z" />
                </svg>
            </td>
            <td>
              <a href="./successDataChange.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                  class="bi bi-trash3-fill" viewBox="0 0 16 16" style="color: #E6B31E">
                  <path
                    d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5" />
                </svg>
            </td>
          </tr>
          <?php
          ;
          } ?>
      </table>
      <div>
        <p class="lead mb-4">W badanym okresie w skarbcu zdeponowano:
          <?php echo $incomesSum; ?> <b><span style="color: #E6B31E">zł</span></b>.
        </p>
      </div>
    </div>
    <h4 class="mb-3">Wydatki</h4>
    <div>
      <table class="table table-dark table-hover">
        <tr>
          <th><span style="color: #E6B31E">Data</span></th>
          <th><span style="color: #E6B31E">Kwota</span></th>
          <th><span style="color: #E6B31E">Kategoria</span></th>
          <th><span style="color: #E6B31E">Komentarz</span></th>
          <th><span style="color: #E6B31E">Metoda płatności</span></th>
          <th></th>
          <th></th>
        </tr>
        <tr>
          <?php
          foreach ($expenses as $user) {
            echo "<td>{$user['date']}</td>
              <td>{$user['amount']}</td>
              <td>{$user['category']}
              <td>{$user['comment']}</td>
              <td>{$user['payment_method']}</td>" ?>
            <td>
              <a href="./editIncome.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                  class="bi bi-pencil-fill" viewBox="0 0 16 16" style="color: #E6B31E">
                  <path
                    d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z" />
                </svg>
            </td>
            <td>
              <a href="./successDataChange.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                  class="bi bi-trash3-fill" viewBox="0 0 16 16" style="color: #E6B31E">
                  <path
                    d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5" />
                </svg>
            </td>
          </tr>
          <?php
          ;
          } ?>
      </table>
      <div>
        <p class="lead mb-4">W badanym okresie zasoby skarbca zmniejszyły się o:
          <?php echo $expensesSum; ?> <b><span style="color: #E6B31E">zł</span></b>.
        </p>
      </div>
    </div>
    <h4 class="mb-3">Bilans</h4>
    <div>
      <p class="lead mb-4">W danym okresie bilans wynosił:
        <?php echo $incomesSum - $expensesSum; ?> <b><span style="color: #E6B31E"> zł</span></b>.
      </p>
    </div>
  </main>
</body>

</html>