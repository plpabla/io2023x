<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dodaj wirusa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script>
        function showMessageBox() {
            alert("Baza wirusów została zaktualizowana");
        }
    </script>
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <h1>Dodaj wirusa</h1>

    <?php
    function get_conn_string()
    {
        $ini = parse_ini_file("php.ini");
        $host = $ini["dbhost"];
        $db = $ini["dbname"];
        $usr = $ini["dbuser"];
        $pass = $ini["dbpass"];
        $conn_string = "host=$host port=5432 dbname=$db user=$usr password=$pass";
        return $conn_string;
    }

    $conn = pg_connect(get_conn_string());

    // Pobranie danych wirusów z bazy danych
    $query = "SELECT * FROM wirus";
    $result = pg_query($conn, $query);

    if (!$result) {
        echo "Wystąpił błąd podczas pobierania danych wirusów: " . pg_last_error();
        exit();
    }
    
    $wirusy = pg_fetch_all($result);

    // Zamknięcie połączenia z bazą danych
    pg_close($conn);


     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 //   if ('' === 'POST') {
        // Pobranie danych z formularza
        $nazwa = $_POST['nazwa'];
        $skrot = $_POST['skrot'];
        $genom = $_POST['genom'];
        $wyleganie = $_POST['wyleganie'];
        $szczepionka = $_POST['szczepionka'];
        $droga_zak = $_POST['droga_zak'];

        // Sprawdzenie, czy wszystkie pola formularza są wypełnione
        if (empty($nazwa) || empty($skrot) || empty($genom) || empty($wyleganie) || empty($szczepionka) || empty($droga_zak)) {
            echo "Wypełnij wszystkie pola formularza.";
        } else {
            // Połączenie z bazą danych PostgreSQL
            $conn = pg_connect(get_conn_string());

            // Zabezpieczenie przed SQL Injection
            $nazwa = pg_escape_string($conn, $nazwa);
            $skrot = pg_escape_string($conn, $skrot);
            $genom = pg_escape_string($conn, $genom);
            $wyleganie = pg_escape_string($conn, $wyleganie);
            $szczepionka = pg_escape_string($conn, $szczepionka);
            $droga_zak = pg_escape_string($conn, $droga_zak);

            // Wstawienie danych wirusa do bazy danych
            $query = "INSERT INTO wirus (nazwa, skrot, genom, wyleganie, szczepionka, droga_zak) 
                      VALUES ('$nazwa', '$skrot', '$genom', '$wyleganie', '$szczepionka', '$droga_zak')";
            $result = pg_query($conn, $query);

            if ($result) {
          //      echo "Baza wirusów została zaktualizowana.";
                echo "<script>showMessageBox();</script>";
            } else {
                echo "Wystąpił błąd podczas dodawania wirusa.";
            }

            // Zamknięcie połączenia z bazą danych
            pg_close($conn);
        }
    }
    ?>

    <form action="dodaj_wirusa.php" method="POST">
        <div class="form-floating mb-3">
            <input type="text" id="nazwa" name="nazwa" required class="form-control">
            <label for="nazwa" class="form-label">Nazwa wirusa:</label>
        </div>
        
        <div class="form-floating mb-3">
            <input type="text" id="skrot" name="skrot" required class="form-control">
            <label for="skrot" class="form-label">Skrót:</label>
        </div>

        <div class="form-floating mb-3">
            <input type="text" id="genom" name="genom" required class="form-control">
            <label for="genom" class="form-label">Genom:</label>
        </div>

        <div class="form-floating mb-3">
            <input type="text" id="wyleganie" name="wyleganie" required class="form-control">
            <label for="wyleganie" class="form-label">Wylęganie:</label>
        </div>

        <div class="form-floating mb-3">
            <input type="text" id="szczepionka" name="szczepionka" required class="form-control">
            <label for="szczepionka" class="form-label">Szczepionka:</label>
        </div>

        <div class="form-floating mb-3">
            <input type="text" id="droga_zak" name="droga_zak" required class="form-control">
            <label for="droga_zak" class="form-label">Droga zakażenia:</label>
        </div>

        <div class="col-12">
            <button class="btn btn-primary" type="submit">Dodaj wirusa</button>
        </div>
    </form>

    <br><br>
    <button type="button" class="btn btn-secondary" onclick="location.href='dodaj_chorobe.php';">Wróć do dodawania choroby</button>
    <br>
    <button type="button" class="btn btn-secondary" onclick="location.href='wirusy.php';">Idź do listy wirusów</button>

</body>
</html>

