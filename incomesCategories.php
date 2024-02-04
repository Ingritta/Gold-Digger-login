<?php

session_start();

if (!isset($_SESSION['zalogowany'])) {
  header('Location: index.php');
  exit();
}

require_once 'database.php';

mysqli_report(MYSQLI_REPORT_STRICT);

try {
  $connection = new mysqli($host, $db_user, $db_password, $db_name);

  if ($connection->connect_errno != 0) {
    throw new Exception(mysqli_connect_errno());
  } else {
    $userId = $_SESSION['id'];
  }
} catch (Exception $e) {
  echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o wizytę w innym terminie!</span>';
  echo '<br />Informacja developerska: ' . $e;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Incomes categories</title>
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

    .btn-lg {
      padding-top: : 0.3rem;
      padding-bottom: 0.3rem;
      font-size: 0.65rem;
    }

    .table {
      margin-top: 1rem;
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
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">Kategorie</a>
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
    <h4 class="mb-3">Kategorie przychodów</h4>
    <div>
      <div>
        <a href="./addCategory.php">
          <button class="btn btn-primary w-100 py-2" type="submit"
            data-nlok-ref-guid="aeaf789e-b250-4308-f552-159fdf94865d">Dodaj nową kategorię przychodu</button></a>
      </div>
      <table class="table table-dark table-hover">
        <tr>
          <th><span style="color: #E6B31E">Kategoria</span></th>
          <th></th>
          <th></th>
        </tr>
        <tr>
          <?php
          $result = $connection->query("SELECT name FROM incomes_category_assigned_to_users WHERE user_id = $userId");

          while ($categories = $result->fetch_assoc()) {
            echo "<td> {$categories['name']}</td>" ?>
            <td>
              <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                <a href="./editIncomeCategory.php">
                  <button type="submit" class="btn btn-primary btn-lg px-4 gap-3" name="categoryId">Edytuj</button></a>
              </div>
            </td>
            <td>
              <div class="d-grid gap-2 d-sm-flex justify-content-sm-center"><a href="./incomesCategories.php">
                  <button type="submit" class="btn btn-outline-secondary btn-lg px-4">Usuń</button></a>
              </div>
            </td>
          </tr>
          <?php
          ;
          } ?>
      </table>
    </div>
    </table>
    </div>
  </main>
</body>

</html>
