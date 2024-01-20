<?php

session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Balance choisen period</title>
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
              <li><a class="dropdown-item" href="./balance.php">Bieżący miesiąc</a></li>
              <li><a class="dropdown-item" href="./balance.php">Poprzedni miesiąc</a></li>
              <li><a class="dropdown-item" href="./balance.php">Bieżący rok</a></li>
              <li><a class="dropdown-item" href="./balanceChoosenPeriod.php">Wybór ręczny dat</a></li>
              <li><a class="dropdown-item" href="./balance.php">Według kategorii</a></li>
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
              <li><a class="dropdown-item" href="./changeEmail.php">Zmiana adresu e-mail</a></li>
              <li><a class="dropdown-item" href="./changeName.php">Zmiana imienia</a></li>
              <li><a class="dropdown-item" href="./changePassword.php">Zmiana hasła</a></li>
              <li><a class="dropdown-item" href="#">Usuń konto</a></li>
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

    <div class="px-4 py-5 my-5 text-center">
      <button type="button" class="btn btn-primary">Wybierz daty</button>
    </div>

    <div>
      <p class="head-of-table">Przychody</p>
      <table class="table table-dark table-hover">
        <tr>
          <th>Id</th>
          <th>Data</th>
          <th>Kwota</th>
          <th>Kategoria</th>
          <th>Komentarz</th>
        </tr>
        <tr>
          <td>1</td>
          <td>01.01.2023</td>
          <td>100.56</td>
          <td>Brak</td>
          <td>Brak</td>
        </tr>
        <tr>
          <td>2</td>
          <td>02.01.2023</td>
          <td>200.56</td>
          <td>Brak</td>
          <td>Brak</td>
        </tr>
        <tr>
          <td>3</td>
          <td>03.01.2023</td>
          <td>300.56</td>
          <td>Brak</td>
          <td>Brak</td>
        </tr>
      </table>
      <div class="my-pie-chart"></div>
    </div>

    <div>
      <p class="head-of-table">Wydatki</p>
      <table class="table table-dark table-hover">
        <tr>
          <th>Id</th>
          <th>Data</th>
          <th>Kwota</th>
          <th>Kategoria</th>
          <th>Komentarz</th>
          <th>Metoda płatności</th>
        </tr>
        <tr>
          <td>1</td>
          <td>01.01.2023</td>
          <td>100.56</td>
          <td>Brak</td>
          <td>Brak jakiegokolwiek komentarza</td>
          <td>gotówka</td>
        </tr>
        <tr>
          <td>2</td>
          <td>02.01.2023</td>
          <td>200.56</td>
          <td>Brak</td>
          <td>Jeden bardzo długi, szczegółowy komentarz</td>
          <td>Karta</td>
        </tr>
        <tr>
          <td>3</td>
          <td>03.01.2023</td>
          <td>300.56</td>
          <td>Brak</td>
          <td>Brak</td>
          <td>Przelew</td>
        </tr>
      </table>
      <div class="my-pie-chart"></div>
    </div>

  </main>
</body>

</html>