<?php

session_start();

if ((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany'] == true)) {
  header('Location: successLogin.php');
  exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign in</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
  <link rel="stylesheet" href="./css/style.css" />
  <style>
    body {
      background-image: url("./images/gold-ring-1.jpg");
      height: 1000px;
    }

    .mb-3 {
      margin-top: 15rem;
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
          <li class="nav-item">
            <a class="nav-link" href="./registration.php">Rejestracja</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <main class="form-signin w-100 m-auto">
    <form action="login.php" method="post">
      <h1 class="h3 mb-3 fw-normal">Podaj dane logowania</h1>
      <div class="form-floating">
        <input type="text" class="form-control" id="floatingInput" name="user" placeholder="Name" autofocus
          data-nlok-ref-guid="b165b106-74fc-4d1d-a447-b71f45cafb0b" autocomplete="off">
        <div id="norton-idsafe-field-styling-divId"
          style="height:24px;max-width:24px;vertical-align:top; position:absolute; top:17px;left:264.38709677419354px;cursor:pointer;resize: both;z-index:2147483646;">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-at"
            viewBox="0 0 16 16">
            <path
              d="M13.106 7.222c0-2.967-2.249-5.032-5.482-5.032-3.35 0-5.646 2.318-5.646 5.702 0 3.493 2.235 5.708 5.762 5.708.862 0 1.689-.123 2.304-.335v-.862c-.43.199-1.354.328-2.29.328-2.926 0-4.813-1.88-4.813-4.798 0-2.844 1.921-4.881 4.594-4.881 2.735 0 4.608 1.688 4.608 4.156 0 1.682-.554 2.769-1.416 2.769-.492 0-.772-.28-.772-.76V5.206H8.923v.834h-.11c-.266-.595-.881-.964-1.6-.964-1.4 0-2.378 1.162-2.378 2.823 0 1.737.957 2.906 2.379 2.906.8 0 1.415-.39 1.709-1.087h.11c.081.67.703 1.148 1.503 1.148 1.572 0 2.57-1.415 2.57-3.643zm-7.177.704c0-1.197.54-1.907 1.456-1.907.93 0 1.524.738 1.524 1.907S8.308 9.84 7.371 9.84c-.895 0-1.442-.725-1.442-1.914z" />
          </svg>
        </div>
        <label for="floatingInput">Nazwa użytkownika</label>
      </div>
      <div class="form-floating">
        <input type="password" class="form-control" id="floatingPassword" placeholder="password"
          data-nlok-ref-guid="ffb8d5ff-837f-4317-da36-63839eb09644" autocomplete="off" name="haslo">
        <div id="norton-idsafe-field-styling-divId"
          style="height:24px;max-width:24px;vertical-align:top; position:absolute; top:17px;left:264.38709677419354px;cursor:pointer;resize: both;z-index:2147483646;">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-key"
            viewBox="0 0 16 16">
            <path
              d="M0 8a4 4 0 0 1 7.465-2H14a.5.5 0 0 1 .354.146l1.5 1.5a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0L13 9.207l-.646.647a.5.5 0 0 1-.708 0L11 9.207l-.646.647a.5.5 0 0 1-.708 0L9 9.207l-.646.647A.5.5 0 0 1 8 10h-.535A4 4 0 0 1 0 8zm4-3a3 3 0 1 0 2.712 4.285A.5.5 0 0 1 7.163 9h.63l.853-.854a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.793-.793-1-1h-6.63a.5.5 0 0 1-.451-.285A3 3 0 0 0 4 5z" />
            <path d="M4 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0z" />
          </svg>
        </div>
        <label for="floatingPassword">Hasło</label>
      </div>
      <button class="btn btn-primary w-100 py-2" type="submit"
        data-nlok-ref-guid="aeaf789e-b250-4308-f552-159fdf94865d">Zaloguj się</button>
      <!-- <a href="./password/forgot">Zapomniałem hasła</a> -->
    </form>
  </main>
</body>

</html>