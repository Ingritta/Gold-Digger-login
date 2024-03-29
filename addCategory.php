<?php

session_start();

if (!isset($_SESSION['zalogowany'])) {
  header('Location: index.php');
  exit();
}

$userId = $_SESSION['id'];

require_once 'database.php';

mysqli_report(MYSQLI_REPORT_STRICT);

try {
  $connection = new mysqli($host, $db_user, $db_password, $db_name);
  if ($connection->connect_errno != 0) {
    throw new Exception(mysqli_connect_errno());

  } else {

    $ok = true;

    if (isset($_POST['description'])) {

      $description = $_POST['description'];

      if ((strlen($description) < 3) || (strlen($description) > 20)) {
        $ok = false;
        $_SESSION['e_description'] = "Opis musi posiadać od 3 do 20 znaków!";
      }

      $choise = $_POST['name'];

      if ($choise == "incomes_categories") {

        $result = $connection->query("SELECT id 
          FROM incomes_category_assigned_to_users 
          WHERE user_id = $userId AND name= '$description'
         ");

        $howManyExist = $result->num_rows;

        if ($howManyExist > 0) {
          $ok = false;
          $_SESSION['e_description'] = "Istnieje już taka kategoria!";
        }

        $_SESSION['fr_description'] = $description;

        if ($ok == true) {
          if (
            $db->query("INSERT INTO incomes_category_assigned_to_users VALUES (
            NULL,
            '$userId',
            '$description'
)")
          ) {
            header('Location: incomesCategories.php'); {
              $_SESSION['fr_description'] = '';
              throw new Exception($connection->error);
            }
          }
        }

      } else if ($choise == "expenses_categories") {

        $result = $connection->query("SELECT id 
          FROM expenses_category_assigned_to_users 
          WHERE user_id = $userId AND name = '$description'
         ");

        $howManyExist = $result->num_rows;

        if ($howManyExist > 0) {
          $ok = false;
          $_SESSION['e_description'] = "Istnieje już taka kategoria!";
        }
        $_SESSION['fr_description'] = $description;

        if ($ok == true) {

          if (
            $db->query("INSERT INTO expenses_category_assigned_to_users VALUES (
            NULL,
            '$userId',
            '$description'
)")
          ) {
            header('Location: expensesCategories.php'); {
              $_SESSION['fr_description'] = '';
              throw new Exception($connection->error);
            }
          }
        }
      }
    }
    $connection->close();
  }
 } catch (Exception $e) {
  echo '<span style="color:red">Błąd serwera. Przepraszamy. </span>';
  echo '<br />Iformacja developerska: ' . $e;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add category</title>

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

    .mb-3 {
      margin-top: 14rem;
      text-align: center;
    }

    .btn {
      margin-top: 1.5rem;
    }

    .col-12 {
      margin-bottom: 2.5rem !important;
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
      <h1 class="h3 mb-3 fw-normal">Wybierz</h1>
      <div class="col-12">
        <label for="category" class="form-label">którą kategorię chcesz dodać: </label>
        <select id="category" name="name" class="form-select" required="">
          <option value="incomes_categories">Kategorie przychodów</option>
          <option value="expenses_categories">Kategorie wydatków</option>
        </select>
        <div class="invalid-feedback">
          Wybierz kateorię.
        </div>
      </div>
      <div class="form-floating">
        <div class="input-group has-validation">
          <span class="input-group-text">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="43" fill="currentColor" class="bi bi-pen"
              viewBox="0 0 16 16">
              <path
                d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z" />
            </svg>
          </span>
          <input type="text" class="form-control" id="goal" placeholder="Nazwa kategorii" required="" name="description"
            value="<?php
            if (isset($_SESSION['fr_description'])) {
              echo $_SESSION['fr_description'];
              unset($_SESSION['fr_description']);
            }
            ?>">
        </div>
        <?php
        if (isset($_SESSION['e_description'])) {
          echo '<div class="error">' . $_SESSION['e_description'] . '</div>';
          unset($_SESSION['e_description']);
        }
        ?>
      </div>
      <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
        <button type="submit" class="btn btn-primary btn-lg px-4 gap-3">Dodaj</button></a>
        <a href="./successLogin.php">
          <button type="button" class="btn btn-outline-secondary btn-lg px-4">Anuluj</button></a>
      </div>
    </form>
  </main>
</body>

</html>