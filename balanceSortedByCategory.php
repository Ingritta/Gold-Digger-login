<?php

session_start();

require_once 'database.php';

$userId = $_SESSION['id'];

$usersQuery = $db->query ("SELECT incomes_category_assigned_to_users.name, 
SUM(incomes.amount) AS incomesSum 
FROM incomes_category_assigned_to_users, incomes 
WHERE incomes.user_id = $userId
AND incomes_category_assigned_to_users.name = incomes.category
-- AND incomes.date BETWEEN :startDate AND :endDate
GROUP BY incomes_category_assigned_to_users.name 
ORDER BY incomesSum DESC");

$incomes = $usersQuery->fetchAll();

$usersQuery = $db->query
("SELECT expenses_category_assigned_to_users.name, 
SUM(expenses.amount) AS expensesSum 
FROM expenses_category_assigned_to_users, expenses 
WHERE expenses.user_id = $userId
AND expenses_category_assigned_to_users.name = expenses.category
-- AND incomes.date BETWEEN :startDate AND :endDate
GROUP BY expenses_category_assigned_to_users.name 
ORDER BY expensesSum DESC");

$expenses = $usersQuery->fetchAll();

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
      <a class="navbar-brand" href="#">Gold Digger</a>
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
    </div>
    <h4 class="mb-3">Podsumowanie</h4>
    <div>
      <p class="lead mb-4">W twoim skarbcu aktualnie znajduje się: <b><span style="color: #E6B31E"> zł</span></b>.</p>
    </div>
  </main>
</body>

</html>