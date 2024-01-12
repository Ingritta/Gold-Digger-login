<?php

session_start();

if (!isset($_SESSION['zalogowany'])) {
    header('Location: index.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Success Login</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
  <link rel="stylesheet" href="./css/style.css" />

  <style>
    body {
      background-image: url("./images/gold-ring-1.jpg");
      height: 850px;
    }

    .form-signin {
      max-width: 530px;
      padding: 1rem;
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
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">Operacje</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#">Dodaj wpływ</a></li>
              <li><a class="dropdown-item" href="#">Dodaj wydatek</a></li>
              <li><a class="dropdown-item" href="#">Usuń wpływ</a></li>
              <li><a class="dropdown-item" href="#">Usuń wydatek</a></li>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">Przeglądaj
              bilans</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#">Bieżący miesiąc</a></li>
              <li><a class="dropdown-item" href="#">Poprzedni miesiąc</a></li>
              <li><a class="dropdown-item" href="#">Bieżący rok</a></li>
              <li><a class="dropdown-item" href="#">Wybór ręczny dat</a></li>
              <li><a class="dropdown-item" href="#">Według kategorii</a></li>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">Ustawienia</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#">Dodaj kategorię</a></li>
              <li><a class="dropdown-item" href="#">Edytuj kategorię</a></li>
              <li><a class="dropdown-item" href="#">Usuń kategorię</a></li>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">Użytkownik</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#">Zmiana adresu e-mail</a></li>
              <li><a class="dropdown-item" href="#">Zmiana imienia</a></li>
              <li><a class="dropdown-item" href="#">Zmiana hasła</a></li>
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
      <p class="lead mb-4">Jesteś zalogowany!</h1>
        <img class="d-block mx-auto mb-4" src="./images/coin-flip-4.gif" alt="" width="72" height="72"
          border-radius="80%">
      <div class="col-lg-6 mx-auto">
        <p class="lead mb-4">Wybierz dostępną opcję i dowiedz się ile <b><span style="color: #E6B31E">zł</span></b>(ota)
          jest na Twoim koncie!</p>
      </div>
    </div>
  </main>

</body>

</html>