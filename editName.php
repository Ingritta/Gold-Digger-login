<?php

session_start();

if (!isset($_SESSION['zalogowany'])) {
  header('Location: index.php');
  exit();
}

if (!isset($_SESSION['logged_id'])) {

  require_once 'database.php';

  mysqli_report(MYSQLI_REPORT_STRICT);

  try {
    $connection = new mysqli($host, $db_user, $db_password, $db_name);

    if ($connection->connect_errno != 0) {
      throw new Exception(mysqli_connect_errno());

    } else {

      $ok = true;

      $userId = $_SESSION['id'];

      if (isset($_POST['name'])) {

        $name = $_POST['name'];
        $_SESSION['fr_name'] = $name;

        if ((strlen($name) < 3) || (strlen($name) > 20)) {
          $ok = false;
          $_SESSION['e_name'] = "Imię musi posiadać od 3 do 20 znaków!";
        }

        if (ctype_alnum($name) == false) {
          $ok = false;
          $_SESSION['e_name'] = "Imię może składać się wyłącznie z liter i cyfr (bez polskich znaków).";
        }

        if ($ok == true) {
          if ($db->query("UPDATE users SET user='$name' WHERE id = $userId")) {
            header('Location: successDataChange.php'); {
              $_SESSION['fr_name'] = '';
              throw new Exception($connection->error);
            }
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
  <title>Change name</title>

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
      <h1 class="h3 mb-3 fw-normal">Podaj nową nazwę</h1>
      <div class="form-floating">
        <input type="text" class="form-control" id="floatingInput" placeholder="Name"
          data-nlok-ref-guid="b165b106-74fc-4d1d-a447-b71f45cafb0b" autocomplete="off" name="name" autofocus required
          value="<?php
          if (isset($_SESSION['fr_name'])) {
            echo $_SESSION['fr_name'];
            unset($_SESSION['fr_name']);
          }
          ?>">
        <div id="norton-idsafe-field-styling-divId"
          style="height:24px;max-width:24px;vertical-align:top; position:absolute; top:17px;left:264.38709677419354px;cursor:pointer;resize: both;z-index:2147483646;">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person"
            viewBox="0 0 16 16">
            <path
              d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z" />
          </svg>
        </div>
        <label for="floatingInput">Nazwa użytkownika</label>
      </div>
      <?php
      if (isset($_SESSION['e_name'])) {
        echo '<div class="error">' . $_SESSION['e_name'] . '</div>';
        unset($_SESSION['e_name']);
      }
      ?>
      <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
        <button type="submit" class="btn btn-primary btn-lg px-4 gap-3">Zmień</button></a>
        <a href="./successLogin.php">
          <button type="button" class="btn btn-outline-secondary btn-lg px-4">Anuluj</button></a>
      </div>
    </form>
  </main>
</body>

</html>