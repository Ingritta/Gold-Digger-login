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

      $date = new DateTime();

      $startDate = date('2000') . "-" . date('01') . "-" . date('01');

      $endDate = date('Y') . "-" . date('m') . "-" . date('d');

      $usersQuery = $db->query
      (
        "SELECT income_id, date, amount, category, comment 
        FROM incomes 
        WHERE incomes.user_id = $userId
        AND incomes.date BETWEEN '$startDate' AND '$endDate' ORDER BY incomes.date ASC"
      );

      $incomes = $usersQuery->fetchAll();

      $usersQuery = $db->query
      (
        "SELECT expense_id, date, amount, category, comment, payment_method 
      FROM expenses 
      WHERE expenses.user_id = $userId
      AND expenses.date BETWEEN '$startDate' AND '$endDate' ORDER BY expenses.date ASC"
      );

      $expenses = $usersQuery->fetchAll();

      $result = $connection->query(
        "SELECT SUM(incomes.amount) AS incomesSum 
      FROM incomes 
      WHERE incomes.user_id = $userId
      AND incomes.date BETWEEN '$startDate' AND '$endDate'"
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
      AND expenses.date BETWEEN '$startDate' AND '$endDate'");

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
  <title>Current Month Balance</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
  <link rel="stylesheet" href="./css/style.css" />

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
    crossorigin="anonymous"></script>

  <style>
    body {
      background-image: url("./images/gold-ring-1.jpg");
      height: 1000px;
    }

    .py-5 {
      padding-top: 1rem !important;
      padding-bottom: 1rem !important;
    }

    .form-signin {
      max-width: 700px;
      padding: 1rem;
    }

    .mb-3 {
      text-align: center;
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
              <li><a class="dropdown-item" href="./balance.php">Podsumowanie</a></li>
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
              <li><a class="dropdown-item" href="./usersDetails.php">Dane użytkownika</a></li>
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
    <div>
      <h2 class="mb-3" style="margin-top: 18rem">W skarbcu zdeponowano:
      <b><span style="color: #CACACA"><?php echo $incomesSum; ?></span></b> <b><span style="color: #E6B31E">zł</span></b>.
      </h2>
    </div>

    <div>
      <h2 class="mb-3" style="margin-top: 3rem">Zasoby skarbca zmniejszyły się o:
      <b><span style="color: #CACACA"><?php echo $expensesSum; ?></span></b> <b><span style="color: #E6B31E">zł</span></b>.
      </h2>
    </div>

    <h2 class="mb-3" style="margin-top: 3rem">Całkowity bilans wynosi:
    <b><span style="color: #CACACA"><?php echo $balance; ?></span></b><b><span style="color: #E6B31E"> zł</span></b>.
    </h2>

  </main>
</body>

</html>