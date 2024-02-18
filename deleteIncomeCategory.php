<?php

session_start();

if (!isset($_SESSION['zalogowany'])) {
  header('Location: index.php');
  exit();
}

require_once 'database.php';

mysqli_report(MYSQLI_REPORT_STRICT);

$userId = $_SESSION['id'];

try {
  $connection = new mysqli($host, $db_user, $db_password, $db_name);

  if ($connection->connect_errno != 0) {
    throw new Exception(mysqli_connect_errno());

  } else {

    $ok = true;

    $categoryId = $_GET['id'];

    mysqli_real_escape_string($connection, $_GET['id']);
    $query = "SELECT name FROM incomes_category_assigned_to_users WHERE id = '$categoryId'";
    $query_run = mysqli_query($connection, $query);

    if (mysqli_num_rows($query_run) > 0) {
      $category = mysqli_fetch_array($query_run);

      $categoryName = $category['name'];

      if (isset($_POST['delete_income_category'])) {

        $result = $connection->query("SELECT income_id 
      FROM incomes 
      WHERE user_id = $userId AND category = '$categoryName'
     ");

        $howManyExist = $result->num_rows;

        if ($howManyExist > 0) {
          $ok = false;
          $_SESSION['e_category'] = "Nie możesz usunąć tej kategorii, ponieważ jest do niej przypisana transakcja! </br> Jeśli chcesz, możesz ją edytować";
        }

        if ($ok == true) {

          $categoryId = $_GET['id'];

          $categoryId = mysqli_real_escape_string($connection, $_GET['id']);
          $query = "DELETE FROM incomes_category_assigned_to_users WHERE id = '$categoryId'";
          $query_run = mysqli_query($connection, $query);

          if ($query_run) {

            header('Location: incomesCategories.php');
          }
        }
      }
    }
  }
  $connection->close();
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
  <title>Delete Income Category</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
  <link rel="stylesheet" href="./css/style.css" />

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
    crossorigin="anonymous"></script>

  <style>
    body {
      background-image: url("images/gold-ring-1.jpg");
      height: 850px;
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

    .mb-3 {
      margin-top: 4rem;
      text-align: center;
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
    <h4 class="mb-3" style="margin-top: 12rem">Czy na pewno chcesz usunąć tę kategorię?</h4>
    <form class="col-12 mb-3" method="post">
      <div>
        <table class="table table-dark table-hover">
          <tr>
            <th><span style="color: #E6B31E">Nazwa kategorii</span></th>
          </tr>
          <tr>
            <?php
            echo "<td>{$category['name']}</td>";
            ?>
          </tr>
        </table>

        <?php
        if (isset($_SESSION['e_category'])) {
          echo '<div class="error">' . $_SESSION['e_category'] . '</div>';
          unset($_SESSION['e_category']);
        }
        ?>
      </div>
      <div class="px-4 py-5 my-5 text-center">
        <a href="./choosenPeriodBalance.php">
          <button type="submit" name="delete_income_category"
            class="btn btn-primary btn-lg px-4 gap-3">Usuń</button></a>
        <a href="./incomesCategories.php">
          <button type="button" class="btn btn-outline-secondary btn-lg px-4">Wróć</button></a>
      </div>
    </form>
  </main>
</body>

</html>