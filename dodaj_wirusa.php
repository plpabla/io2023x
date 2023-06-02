<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dodaj wirusa</title>
</head>
<body>
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
                echo "Baza wirusów została zaktualizowana.";
            } else {
                echo "Wystąpił błąd podczas dodawania wirusa.";
            }

            // Zamknięcie połączenia z bazą danych
            pg_close($conn);
        }
    }
    ?>

    <form action="dodaj_wirusa.php" method="POST">
        <label for="nazwa">Nazwa wirusa:</label>
        <input type="text" id="nazwa" name="nazwa" required>
        <br><br>

        <label for="skrot">Skrót:</label>
        <input type="text" id="skrot" name="skrot" required>
        <br><br>

        <label for="genom">Genom:</label>
        <input type="text" id="genom" name="genom" required>
        <br><br>

        <label for="wyleganie">Wylęganie:</label>
        <input type="text" id="wyleganie" name="wyleganie" required>
        <br><br>

        <label for="szczepionka">Szczepionka:</label>
        <input type="text" id="szczepionka" name="szczepionka" required>
        <br><br>

        <label for="droga_zak">Droga zakażenia:</label>
        <input type="text" id="droga_zak" name="droga_zak" required>
        <br><br>

        <input type="submit" value="Dodaj wirusa">
    </form>

    <br><br>
    <a href="wirusy.php">Wróć do listy wirusów</a>
</body>
</html>
