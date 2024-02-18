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

    if (isset($_GET['expense_id'])) {

      $ok = true;

      $userId = $_SESSION['id'];

      $expenseId = mysqli_real_escape_string($connection, $_GET['expense_id']);
      $query = "SELECT date, amount, comment FROM expenses WHERE expense_id = '$expenseId'";
      $query_run = mysqli_query($connection, $query);

      if (mysqli_num_rows($query_run) > 0) {
        $expense = mysqli_fetch_array($query_run);
        $date = $expense['date'];
        $amount = $expense['amount'];
        $comment = $expense['comment'];
      }

      $_SESSION['fr_date'] = $date;
      $_SESSION['fr_amount'] = $amount;
      $_SESSION['fr_comment'] = $comment;

      if (isset($_POST['date'])) {

        $date = $_POST['date'];

        $date = new DateTime();

        $currentDate = date('Y') . "-" . date('m') . "-" . date('d');

        $date = $_POST['date'];

        if ($date > $currentDate) {
          $ok = false;
          $_SESSION['e_date'] = "Data znajduje się poza zakresem!";
        }


        if ($date < '2000-01-01') {
          $ok = false;
          $_SESSION['e_date'] = "Podano niprawidłową datę!";
        }
      }

      if (isset($_POST['amount'])) {
        $amount = $_POST['amount'];
        if ($amount == '') {
          $ok = false;
          $_SESSION['e_amount'] = "Proszę wpisać ilość!";
        }

        if ($amount <= 0) {
          $ok = false;
          $_SESSION['e_amount'] = "Proszę wpisać liczbę większą od zera!";
        }
      }

      if (isset($_POST['category'])) {
        $choise = $_POST['category'];
      }

      if (isset($_POST['comment'])) {

        $comment = $_POST['comment'];
        $pattern = '/[^\wżźćńółęąśŻŹĆĄŚĘŁÓŃ ]/i';
        $result = preg_match($pattern, $comment);

        if ($result == 1) {
          $ok = false;
          $_SESSION['e_comment'] = "Proszę używać wyłącznie liter i cyfr i spacji!";
        }
      }

      if (isset($_POST['paymentMethod'])) {
        $paymentMethod = $_POST['paymentMethod'];
      }

      if ($ok == true) {

        if (isset($_POST['editExpense'])) {

          $expenseId = $_GET['expense_id'];

          $categoryId = mysqli_real_escape_string($connection, $_GET['expense_id']);
          $query = "UPDATE expenses SET date = '$date', amount = '$amount', category = '$choise', comment = '$comment', payment_method = '$paymentMethod' WHERE expense_id = '$expenseId'";
          $query_run = mysqli_query($connection, $query);

          if ($query_run) {

            header('Location: successDataChange.php'); {
              $_SESSION['fr_date'] = '';
              $_SESSION['fr_amount'] = '';
              $_SESSION['fr_comment'] = '';
              throw new Exception($connection->error);
            }
          }
        }
      }
    }
  }

  //$connection
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
  <title>Edit Income</title>
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

    .form-signin {
      max-width: 330px;
      padding: 1rem;
    }

    .my-5 {
      margin-top: 0rem !important;
    }

    .mb-3 {
      margin-top: 5rem;
      text-align: center;
    }

    .error {
      color: #E6B31E;
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
    <h4 class="mb-3">Wprowadź zmiany</h4>
    <div class="col-md-7 col-lg-8">
      <form method="post">
        <input type="hidden" class="form-control" name="expense_id" value="<?php $expenseId ?>">
        <div class="row g-3">
          <div class="col-12">
            <label for="goal" class="form-label">Data</label>
            <div class="input-group has-validation">
              <span class="input-group-text">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen"
                  viewBox="0 0 16 16">
                  <path
                    d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z" />
                </svg>
              </span>
              <input type="date" class="form-control" id="goal" placeholder="Data" name="date" required="" value="<?php
              if (isset($_SESSION['fr_date'])) {
                echo $_SESSION['fr_date'];
                unset($_SESSION['fr_date']);
              }
              ?>">
            </div>
            <?php
            if (isset($_SESSION['e_date'])) {
              echo '<div class="error">' . $_SESSION['e_date'] . '</div>';
              unset($_SESSION['e_date']);
            }
            ?>
          </div>
          <div class="col-12">
            <label for="goal" class="form-label">Kwota</label>
            <div class="input-group has-validation">
              <span class="input-group-text">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen"
                  viewBox="0 0 16 16">
                  <path
                    d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z" />
                </svg>
              </span>
              <input type="number" class="form-control" id="goal" placeholder="Kwota" name="amount" required="" value="<?php
              if (isset($_SESSION['fr_amount'])) {
                echo $_SESSION['fr_amount'];
                unset($_SESSION['fr_amount']);
              }
              ?>">
            </div>
            <?php
            if (isset($_SESSION['e_amount'])) {
              echo '<div class="error">' . $_SESSION['e_amount'] . '</div>';
              unset($_SESSION['e_amount']);
            }
            ?>
          </div>
          <div class="col-12">
            <label for="category" class="form-label">Kategoria</label>
            <select id="category" class="form-select" name="category" required="">
              <?php
              $result = $connection->query("SELECT name FROM expenses_category_assigned_to_users WHERE user_id = '$userId'");

              while ($categories = $result->fetch_assoc()) { ?>

                <option value="<?php echo $categories['name'] ?>">
                  <?php echo "{$categories['name']}" ?>
                </option>;
              <?php } ?>
            </select>
            <?php
            if (isset($_SESSION['e_choise'])) {
              echo '<div class="error">' . $_SESSION['e_choise'] . '</div>';
              unset($_SESSION['e_choise']);
            }
            ?>
          </div>
          <div class="col-12">
            <label for="comment" class="form-label">Komentarz</label>
            <div class="input-group has-validation">
              <span class="input-group-text">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen"
                  viewBox="0 0 16 16">
                  <path
                    d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z" />
                </svg>
              </span>
              <input type="text" class="form-control" id="goal" placeholder="Komentarz" name="comment" 
                value="<?php
                if (isset($_SESSION['fr_comment'])) {
                  echo $_SESSION['fr_comment'];
                  unset($_SESSION['fr_comment']);
                }
                ?>">
            </div>
            <?php
            if (isset($_SESSION['e_comment'])) {
              echo '<div class="error">' . $_SESSION['e_comment'] . '</div>';
              unset($_SESSION['e_comment']);
            }
            ?>
          </div>
          <h4 class="mb-3" style="margin-top: 2rem">Płatność</h4>
          <div class="my-3">
            <div class="form-check">
              <input id="cash" name="paymentMethod" type="radio" class="form-check-input" checked="" required=""
                value="Gotówka">
              <label class="form-check-label" for="cash">Gotówka</label>
            </div>
            <div class="form-check">
              <input id="debit" name="paymentMethod" type="radio" class="form-check-input" required="" value="Karta">
              <label class="form-check-label" for="debit">Karta</label>
            </div>
            <div class="form-check">
              <input id="other" name="paymentMethod" type="radio" class="form-check-input" required="" value="Inna">
              <label class="form-check-label" for="other">Inna forma płatności: przelew/ paypal/ blik</label>
            </div>
          </div>
          <div class="px-4 py-5 my-5 text-center">
            <button type="submit" name="editExpense" class="btn btn-primary btn-lg px-4 gap-3">Zmień</button></a>
            <a href="./successLogin.php">
              <button type="button" class="btn btn-outline-secondary btn-lg px-4">Wyjdź</button></a>
          </div>
        </div>
      </form>
    </div>
  </main>
</body>

</html>