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

      if ($startDate == '' || $endDate == '') {
        $wszystko_OK = false;
        $_SESSION['e_date'] = "Proszę wybrać datę!";
      }

      if ($startDate < '2000-01-01' || $endDate < '2000-01-01') {
        $wszystko_OK = false;
        $_SESSION['e_date'] = "Podano nieprawidłową datę!";
      }

      if ($startDate > $date || $endDate > $date) {
        $wszystko_OK = false;
        $_SESSION['e_date'] = "Data znajduje się poza zakresem!";
      }

      $usersQuery = $db->query
      (
        "SELECT incomes_category_assigned_to_users.name, 
        SUM(incomes.amount) AS incomesSum 
        FROM incomes_category_assigned_to_users, incomes 
        WHERE incomes.user_id = 2
        AND incomes_category_assigned_to_users.name = incomes.category
        AND incomes.date BETWEEN '$startDate' AND '$endDate' 
        GROUP BY incomes_category_assigned_to_users.name
        ORDER BY incomesSum DESC"
      );

      $incomes = $usersQuery->fetchAll();

      $usersQuery = $db->query
      (
        "SELECT expenses_category_assigned_to_users.name, 
        SUM(expenses.amount) AS expensesSum 
        FROM expenses_category_assigned_to_users, expenses 
        WHERE expenses.user_id = 2
        AND expenses_category_assigned_to_users.name = expenses.category
        AND expenses.date BETWEEN '$startDate' AND '$endDate'
        GROUP BY expenses_category_assigned_to_users.name 
        ORDER BY expensesSum DESC"
      );

      $expenses = $usersQuery->fetchAll();

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

    .btn {
      margin-top: 1.5rem;
      margin-bottom: 1.5rem;
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
    <form class="col-12 mb-3" method="post">
      <h4 class="mb-3" style="margin-top: 1rem">Wybierz daty</h4>
      <div class="col-12 mb-3">
        <label for="goal" class="form-label">Data początkowa</label>
        <div class="input-group has-validation">
          <span class="input-group-text">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen"
              viewBox="0 0 16 16">
              <path
                d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z" />
            </svg>
          </span>
          <input type="date" class="form-control" id="goal" placeholder="Data" required="" name="startDate">
        </div>
        <?php
        if (isset($_SESSION['e_date'])) {
          echo '<div class="error">' . $_SESSION['e_date'] . '</div>';
          unset($_SESSION['e_date']);
        }
        ?>
      </div>
      <div class="col-12">
        <label for="goal" class="form-label">Data końcowa</label>
        <div class="input-group has-validation">
          <span class="input-group-text">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen"
              viewBox="0 0 16 16">
              <path
                d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z" />
            </svg>
          </span>
          <input type="date" class="form-control" id="goal" placeholder="Data" required="" name="endDate">
        </div>
        <?php
        if (isset($_SESSION['e_date'])) {
          echo '<div class="error">' . $_SESSION['e_date'] . '</div>';
          unset($_SESSION['e_date']);
        }
        ?>
      </div>
      <button type="submit" class="btn btn-primary w-100 py-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sort-down"
          viewBox="0 0 16 16">
        </svg>
        Akceptuj
      </button>
      <h4 class="mb-3" style="margin-top: 1rem">Przychody</h4>
      <div>
        <table class="table table-dark table-hover">
          <tr>
            <th><span style="color: #E6B31E">Suma</span></th>
            <th><span style="color: #E6B31E">Kategoria</span></th>
          </tr>
          <tr>
            <?php
            foreach ($incomes as $user) {
              echo "<td>{$user['incomesSum']}</td>
              <td>{$user['name']}
            " ?>
            </tr>
            <?php
            ;
            } ?>
        </table>
        <div>
        </div>
      </div>
      <h4 class="mb-3">Wydatki</h4>
      <div>
        <table class="table table-dark table-hover">
          <tr>
            <th><span style="color: #E6B31E">Suma</span></th>
            <th><span style="color: #E6B31E">Kategoria</span></th>
          </tr>
          <tr>
            <?php
            foreach ($expenses as $user) {
              echo "<td>{$user['expensesSum']}</td>
              <td>{$user['name']}
            " ?>
            </tr>
            <?php
            ;
            } ?>
        </table>
  </main>
</body>

</html>