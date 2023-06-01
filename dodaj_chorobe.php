<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dodaj chorobę</title>
</head>
<body>
    <h1>Dodaj chorobę</h1>

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
        $jednostka_chorobowa = $_POST['jednostka_chorobowa'];
        $objawy_ogolne_miejscowe = $_POST['objawy_ogolne_miejscowe'];
        $objawy_miejscowe_ju = $_POST['objawy_miejscowe_ju'];
        $rozpoznanie = $_POST['rozpoznanie'];
        $roznicowanie = $_POST['roznicowanie'];
        $id_wirus = $_POST['id_wirus'];

        // Sprawdzenie, czy wszystkie pola formularza są wypełnione
        if (empty($jednostka_chorobowa) || empty($objawy_ogolne_miejscowe) || empty($objawy_miejscowe_ju) || empty($rozpoznanie) || empty($roznicowanie)) {
            echo "Wypełnij wszystkie pola formularza.";
        } else {
            // Połączenie z bazą danych PostgreSQL
            $conn = pg_connect(get_conn_string());

            // Zabezpieczenie przed SQL Injection
            $jednostka_chorobowa = pg_escape_string($jednostka_chorobowa);
            $objawy_ogolne_miejscowe = pg_escape_string($objawy_ogolne_miejscowe);
            $objawy_miejscowe_ju = pg_escape_string($objawy_miejscowe_ju);
            $rozpoznanie = pg_escape_string($rozpoznanie);
            $roznicowanie = pg_escape_string($roznicowanie);

            // Wstawienie danych choroby do bazy danych
            $query = "INSERT INTO choroba (id_wirus, jednostka_chorobowa, objawy_ogolne_miejscowe, objawy_miejscowe_ju, rozpoznanie, roznicowanie) 
                      VALUES ($id_wirus, '$jednostka_chorobowa', '$objawy_ogolne_miejscowe', '$objawy_miejscowe_ju', '$rozpoznanie', '$roznicowanie')";
            $result = pg_query($conn, $query);

            if ($result) {
                echo "Baza chorób została zaktualizowana.";
            } else {
                echo "Wystąpił błąd podczas dodawania choroby.";
            }

            // Zamknięcie połączenia z bazą danych
            pg_close($conn);
        }
    }
    ?>

    <form action="dodaj_chorobe.php" method="POST">
        <label for="jednostka_chorobowa">Jednostka chorobowa:</label>
        <input type="text" id="jednostka_chorobowa" name="jednostka_chorobowa" required>
        <br><br>

        <label for="id_wirus">Wybierz wirusa:</label>
        <select id="id_wirus" name="id_wirus">
            <?php
            foreach ($wirusy as $wirus) {
                echo "<option value='" . $wirus['id'] . "'>" . $wirus['nazwa'] . "</option>";
            }
            ?>
        </select>
        <br><br>

        <label for="objawy_ogolne_miejscowe">Objawy ogólne/miejscowe:</label>
        <input type="text" id="objawy_ogolne_miejscowe" name="objawy_ogolne_miejscowe" required>
        <br><br>

        <label for="objawy_miejscowe_ju">Objawy miejscowe/ju:</label>
        <input type="text" id="objawy_miejscowe_ju" name="objawy_miejscowe_ju" required>
        <br><br>

        <label for="rozpoznanie">Rozpoznanie:</label>
        <input type="text" id="rozpoznanie" name="rozpoznanie" required>
        <br><br>

        <label for="roznicowanie">Różnicowanie:</label>
        <input type="text" id="roznicowanie" name="roznicowanie" required>
        <br><br>

        <input type="submit" value="Dodaj chorobę">
    </form>

    <br><br>
    <a href="index.php">Wróć na stronę główną</a>
</body>
</html>
