<?php

session_start();

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
            height: 850px;
        }

        .error {
            margin-bottom: 1rem !important;
            color: #E6B31E;
        }

        .mb-3 {
            margin-top: 11rem;
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
              <li><a class="dropdown-item" href="./balance.php">Bieżący miesiąc</a></li>
              <li><a class="dropdown-item" href="./balance.php">Poprzedni miesiąc</a></li>
              <li><a class="dropdown-item" href="./balance.php">Bieżący rok</a></li>
              <li><a class="dropdown-item" href="./choosePeriod.php">Wybór ręczny dat</a></li>
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
        <form method="post">
            <h1 class="h3 mb-3 fw-normal">Podaj nowe imię</h1>

            <div class="form-floating">
                <input type="text" class="form-control" id="floatingInput" placeholder="Name"
                    data-nlok-ref-guid="b165b106-74fc-4d1d-a447-b71f45cafb0b" autocomplete="off" name="nick" autofocus
                    required value="">
                <div id="norton-idsafe-field-styling-divId"
                    style="height:24px;max-width:24px;vertical-align:top; position:absolute; top:17px;left:264.38709677419354px;cursor:pointer;resize: both;z-index:2147483646;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-person" viewBox="0 0 16 16">
                        <path
                            d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z" />
                    </svg>
                </div>
                <label for="floatingInput">Imię</label>
            </div>
           
            <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
        <a href="./successDataChange.php">
          <button type="submit" class="btn btn-primary btn-lg px-4 gap-3">Zmień</button></a>
        <a href="./successLogin.php">
          <button type="button" class="btn btn-outline-secondary btn-lg px-4">Anuluj</button></a>
      </div>

        </form>
    </main>

</body>

</html>