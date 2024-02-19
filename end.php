<?php

session_start();
session_unset();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Success Logout</title>

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

    .form-signin {
      max-width: 530px;
      padding: 1rem;
    }

    .d-block {
      margin-top: 5rem !important;
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
    </div>
  </nav>
  <main class="form-signin w-100 m-auto">
    <div class="px-4 py-5 my-5 text-center">
      <img class="d-block mx-auto mb-4" src="./images/coin-flip-4.gif" alt="" width="72" height="72"
        border-radius="80%">
      <div class="col-lg-6 mx-auto">
        <p class="lead mb-4">Dziękujemy. </p>
        <p class="lead mb-4">Zapraszamy ponownie!</p>
      </div>
    </div>
  </main>
</body>

</html>