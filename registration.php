<?php

session_start();

if (isset($_POST['email'])) {

    $wszystko_OK = true;

    $nick = $_POST['nick'];
    if ((strlen($nick) < 3) || (strlen($nick) > 20)) {
        $wszystko_OK = false;
        $_SESSION['e_nick'] = "Nick musi posiadać od 3 do 20 znaków!";
    }

    if (ctype_alnum($nick) == false) {
        $wszystko_OK = false;
        $_SESSION['e_nick'] = "Nick może składać się wyłącznie z liter i cyfr (bez polskich znaków).";
    }

    $email = $_POST['email'];
    $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);

    if ((filter_var($emailB, FILTER_VALIDATE_EMAIL) == false) || ($emailB != $email)) {
        $wszystko_OK = false;
        $_SESSION['e_email'] = "Podaj poprawny email!";
    }

    $haslo1 = $_POST['haslo1'];
    $haslo2 = $_POST['haslo2'];

    if ((strlen($haslo1) < 8) || (strlen($haslo1) > 20)) {
        $wszystko_OK = false;
        $_SESSION['e_haslo'] = "Hasło musi posiadać od 8 do 20 znaków!";
    }

    if ($haslo1 != $haslo2) {
        $wszystko_OK = false;
        $_SESSION['e_haslo'] = "Podane hasła nie są identyczne!";
    }

    $haslo_hash = password_hash($haslo1, PASSWORD_DEFAULT);

    if (!isset($_POST['regulamin'])) {
        $wszystko_OK = false;
        $_SESSION['e_regulamin'] = "Proszę potwierdzić zapoznanie się z regulaminem.";
    }

    $sekret = "6LcdF08pAAAAAGlmAZ_hMCyW8TmUWGEUrI5Gb6T5";
    $sprawdz = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $sekret . '&response=' . $_POST['g-recaptcha-response']);

    $odpowiedz = json_decode($sprawdz);
    if ($odpowiedz->success == false) {
        $wszystko_OK = false;
        $_SESSION['e_odpowiedz'] = "Proszę zaznaczyć pole: Nie jestem robotem.";
    }

    $_SESSION['fr_nick'] = $nick;
    $_SESSION['fr_email'] = $email;
    $_SESSION['fr_haslo1'] = $haslo1;
    $_SESSION['fr_haslo2'] = $haslo2;
    if (isset($_POST['regulamin']))
        $_SESSION['fr_regulamin'] = true;

    require_once 'database.php';

    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
        $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
        if ($polaczenie->connect_errno != 0) {
            throw new Exception(mysqli_connect_errno());
        } else {

            $rezultat = $polaczenie->query("SELECT id FROM users WHERE email='$email'");

            if (!$rezultat)
                throw new Exception($polaczenie->error);

            $ile_takich_maili = $rezultat->num_rows;

            if ($ile_takich_maili > 0) {
                $wszystko_OK = false;
                $_SESSION['e_email'] = "Podany e-mail juz istnieje w bazie.";
            }

            $rezultat = $polaczenie->query("SELECT id FROM users WHERE user='$nick'");

            if (!$rezultat)
                throw new Exception($polaczenie->error);

            $ile_takich_nickow = $rezultat->num_rows;

            if ($ile_takich_nickow > 0) {
                $wszystko_OK = false;
                $_SESSION['e_nick'] = "Isnieje już taki nick.";
            }

            if ($wszystko_OK == true) {
                if (
                    $polaczenie->query("INSERT INTO users VALUES (
                    NULL,
                    '$nick',
                    '$haslo_hash',
                    '$email'
                    )")
                ) {
                    //pobieranie Id nowego uzytkownika
                    $userId = $polaczenie->query("SELECT id FROM users WHERE user='$nick'");

                    // $userId = mysqli_query($polaczenie, "SELECT id FROM users WHERE user='$nick'");
                    // if (!$userId) {
                    //     die(mysqli_error($polaczenie));
                    // }
                    // echo $userId;


                    //kopiowanie kategorii
                    $usersQuery = $db->query(
                        "INSERT INTO incomes_category_assigned_to_users (name) 
                        SELECT (name) FROM incomes_category_default"
                    );

                    $numOfusers = $rezultat->num_rows;

                    if ($numOfusers == 1) {
                        $numOfusers = $rezultat->fetch_assoc();
                        $userId = implode($numOfusers);
                    }

                    $usersQuery = $db->query("UPDATE incomes_category_assigned_to_users 
                        SET user_id = $userId 
                        WHERE user_id = 0");

                    $usersQuery->fetchAll();

                    $_SESSION['udanarejestracja'] = true;
                    header('Location: successRegistration.php'); {
                        throw new Exception($polaczenie->error);
                    }
                }
            }
            $polaczenie->close();
        }

    } catch (Exception $e) {
        echo '<span style="color:red">Błąd serwera. Przepraszamy. </span>';
        echo '<br />Iformacja developerska: ' . $e;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/style.css" />
    <script src="https://www.google.com/recaptcha/api.js"></script>

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
            margin-top: 5rem;
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
                        <a class="nav-link" href="./login.php">Logowanie</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="form-signin w-100 m-auto">
        <form method="post">
            <h1 class="h3 mb-3 fw-normal">Podaj dane użytkownika</h1>
            <div class="form-floating">
                <input type="text" class="form-control" id="floatingInput" placeholder="Name"
                    data-nlok-ref-guid="b165b106-74fc-4d1d-a447-b71f45cafb0b" autocomplete="off" name="nick" autofocus
                    required="" value="<?php
                    if (isset($_SESSION['fr_nick'])) {
                        echo $_SESSION['fr_nick'];
                        unset($_SESSION['fr_nick']);
                    }
                    ?>">
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
            <?php
            if (isset($_SESSION['e_nick'])) {
                echo '<div class="error">' . $_SESSION['e_nick'] . '</div>';
                unset($_SESSION['e_nick']);
            }
            ?>
            <div class="form-floating">
                <input type="text" class="form-control" id="floatingInput" placeholder="email address"
                    data-nlok-ref-guid="b165b106-74fc-4d1d-a447-b71f45cafb0b" autocomplete="off" name="email" required
                    type="email" value="<?php
                    if (isset($_SESSION['fr_email'])) {
                        echo $_SESSION['fr_email'];
                        unset($_SESSION['fr_email']);
                    }
                    ?>">
                <div id="norton-idsafe-field-styling-divId"
                    style="height:24px;max-width:24px;vertical-align:top; position:absolute; top:17px;left:264.38709677419354px;cursor:pointer;resize: both;z-index:2147483646;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-at"
                        viewBox="0 0 16 16">
                        <path
                            d="M13.106 7.222c0-2.967-2.249-5.032-5.482-5.032-3.35 0-5.646 2.318-5.646 5.702 0 3.493 2.235 5.708 5.762 5.708.862 0 1.689-.123 2.304-.335v-.862c-.43.199-1.354.328-2.29.328-2.926 0-4.813-1.88-4.813-4.798 0-2.844 1.921-4.881 4.594-4.881 2.735 0 4.608 1.688 4.608 4.156 0 1.682-.554 2.769-1.416 2.769-.492 0-.772-.28-.772-.76V5.206H8.923v.834h-.11c-.266-.595-.881-.964-1.6-.964-1.4 0-2.378 1.162-2.378 2.823 0 1.737.957 2.906 2.379 2.906.8 0 1.415-.39 1.709-1.087h.11c.081.67.703 1.148 1.503 1.148 1.572 0 2.57-1.415 2.57-3.643zm-7.177.704c0-1.197.54-1.907 1.456-1.907.93 0 1.524.738 1.524 1.907S8.308 9.84 7.371 9.84c-.895 0-1.442-.725-1.442-1.914z" />
                    </svg>
                </div>
                <label for="floatingInput">Adres e-mail</label>
            </div>
            <?php
            if (isset($_SESSION['e_email'])) {
                echo '<div class="error">' . $_SESSION['e_email'] . '</div>';
                unset($_SESSION['e_email']);
            }
            ?>
            <div class="form-floating">
                <input type="password" class="form-control" id="inputPassword" placeholder="Password"
                    data-nlok-ref-guid="ffb8d5ff-837f-4317-da36-63839eb09644" autocomplete="off" name="haslo1" required
                    value="<?php
                    if (isset($_SESSION['fr_haslo1'])) {
                        echo $_SESSION['fr_haslo1'];
                        unset($_SESSION['fr_haslo1']);
                    }
                    ?>">
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
            <?php
            if (isset($_SESSION['e_haslo'])) {
                echo '<div class="error">' . $_SESSION['e_haslo'] . '</div>';
                unset($_SESSION['e_haslo']);
            }
            ?>
            <div class="form-floating">
                <input type="password" class="form-control" id="inputPasswordConfirmation" name="haslo2"
                    placeholder="Repeat password" data-nlok-ref-guid="ffb8d5ff-837f-4317-da36-63839eb09644"
                    autocomplete="off" value="<?php
                    if (isset($_SESSION['fr_haslo2'])) {
                        echo $_SESSION['fr_haslo2'];
                        unset($_SESSION['fr_haslo2']);
                    }
                    ?>">
                <div id="norton-idsafe-field-styling-divId"
                    style="height:24px;max-width:24px;vertical-align:top; position:absolute; top:17px;left:264.38709677419354px;cursor:pointer;resize: both;z-index:2147483646;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-key"
                        viewBox="0 0 16 16">
                        <path
                            d="M0 8a4 4 0 0 1 7.465-2H14a.5.5 0 0 1 .354.146l1.5 1.5a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0L13 9.207l-.646.647a.5.5 0 0 1-.708 0L11 9.207l-.646.647a.5.5 0 0 1-.708 0L9 9.207l-.646.647A.5.5 0 0 1 8 10h-.535A4 4 0 0 1 0 8zm4-3a3 3 0 1 0 2.712 4.285A.5.5 0 0 1 7.163 9h.63l.853-.854a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.793-.793-1-1h-6.63a.5.5 0 0 1-.451-.285A3 3 0 0 0 4 5z" />
                        <path d="M4 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0z" />
                    </svg>
                </div>
                <label for="floatingPassword">Powtórz hasło</label>
            </div>
            <label>
                <input type="checkbox" name="regulamin" /><span style="color: #CACACA">Akceptuję
                    regulamin</span><br /><br />
                <?php
                if (isset($_SESSION['fr_regulamin'])) {
                    unset($_SESSION['fr_regulamin']);
                }
                ?>
            </label>
            <?php
            if (isset($_SESSION['e_regulamin'])) {
                echo '<div class="error">' . $_SESSION['e_regulamin'] . '</div>';
                unset($_SESSION['e_regulamin']);
            }
            ?>
            <div class="g-recaptcha" data-theme="dark" data-sitekey="6LcdF08pAAAAABxZ1HYzQn0WhGAR9DY5Rw8FgXjS"></div>
            <br />

            <?php
            if (isset($_SESSION['e_odpowiedz'])) {
                echo '<div class="error">' . $_SESSION['e_odpowiedz'] . '</div>';
                unset($_SESSION['e_odpowiedz']);
            }
            ?>
            <button class="btn btn-primary w-100 py-2" type="submit"
                data-nlok-ref-guid="aeaf789e-b250-4308-f552-159fdf94865d">Zarejestruj się</button>
        </form>
    </main>
</body>

</html>