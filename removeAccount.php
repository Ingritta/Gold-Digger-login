<?php

session_start();

if (!isset($_SESSION['zalogowany'])) {
  header('Location: index.php');
  exit();
}

$userId = $_SESSION['id'];

if (!isset($_SESSION['logged_id'])) {

  require_once 'database.php';

  mysqli_report(MYSQLI_REPORT_STRICT);

  try {
    $connection = new mysqli($host, $db_user, $db_password, $db_name);

    if ($connection->connect_errno != 0) {
      throw new Exception(mysqli_connect_errno());

    } else {

      if (isset($_POST['yes'])) {

        $db->query("DELETE FROM incomes_category_assigned_to_users WHERE user_id = $userId");
        $db->query("DELETE FROM expenses_category_assigned_to_users WHERE user_id = $userId");
        $db->query("DELETE FROM incomes WHERE user_id = $userId");
        $db->query("DELETE FROM expenses WHERE user_id = $userId");

        if ($db->query("DELETE FROM users WHERE id = $userId")) {
          header('Location: end.php'); {
            $_SESSION['loggedIn'] = false;
            throw new Exception($connection->error);
          }
        }
      }
      $connection->close();
    }
  } catch (Exception $e) {
    echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o wizytę w innym terminie!</span>';
    echo '<br />Informacja developerska: ' . $e;
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Remove account</title>

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

    .error {
      margin-bottom: 1rem !important;
      color: #E6B31E;
    }

    .btn {
      margin-top: 1.5rem;
    }

    .mb-3 {
      margin-top: 18rem;
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
              <li><a class="dropdown-item" href="./editName.php">Zmiana nazwy użytkownika</a></li>
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
    <form method="post">
      <div class="col-12 mb-3">
        <h4 class="mb-3" style="margin-top: 1rem">Czy na pewno chcesz usunąć konto?</h4>
        <h4 class="mb-3" style="margin-top: 1rem">Jeśli to zrobisz Twoje dane zostaną bezpowrotnie utracone :(</h4>
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
          <button type="submit" class="btn btn-primary btn-lg px-4 gap-3" name="yes">Usuń</button></a>
          <a href="./successLogin.php">
            <button type="button" class="btn btn-outline-secondary btn-lg px-4">Anuluj</button></a>
        </div>
    </form>
  </main>
</body>

</html>