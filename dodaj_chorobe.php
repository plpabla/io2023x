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
    $query = "SELECT * FROM wirus ORDER BY id";
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
        $choroba = $_POST['choroba'];
        $objawy_ogolne = $_POST['objawy_ogolne'];
        $objawy_ju = $_POST['objawy_ju'];
        $rozpoznanie = $_POST['rozpoznanie'];
        $roznicowanie = $_POST['roznicowanie'];
        $id_wirus = $_POST['id_wirus'];

        // Sprawdzenie, czy wszystkie pola formularza są wypełnione
        if (empty($choroba) || empty($objawy_ogolne) || empty($objawy_ju) || empty($rozpoznanie) || empty($roznicowanie)) {
            echo "Wypełnij wszystkie pola formularza.";
        } else {
            // Połączenie z bazą danych PostgreSQL
            $conn = pg_connect(get_conn_string());

            // Zabezpieczenie przed SQL Injection
            $choroba = pg_escape_string($conn, $choroba);
            $objawy_ogolne = pg_escape_string($conn, $objawy_ogolne);
            $objawy_ju = pg_escape_string($conn, $objawy_ju);
            $rozpoznanie = pg_escape_string($conn, $rozpoznanie);
            $roznicowanie = pg_escape_string($conn, $roznicowanie);

            // Wstawienie danych choroby do bazy danych
            $query = "INSERT INTO choroba (id_wirus, choroba, objawy_ogolne, objawy_ju, rozpoznanie, roznicowanie) 
                      VALUES ($id_wirus, '$choroba', '$objawy_ogolne', '$objawy_ju', '$rozpoznanie', '$roznicowanie')";
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
        <label for="choroba">Jednostka chorobowa:</label>
        <input type="text" id="choroba" name="choroba" required>
        <br><br>

        <label for="id_wirus">Wybierz wirusa:</label>
        <select id="id_wirus" name="id_wirus">
            <?php
            if (!empty($wirusy)) {
                foreach ($wirusy as $wirus) {
                    echo "<option value='" . $wirus['id'] . "'>" . $wirus['nazwa'] . "</option>";
                }
            }
            ?>
        </select>
        <br><br>

        <label for="objawy_ogolne">Objawy ogólne/miejscowe:</label>
        <input type="text" id="objawy_ogolne" name="objawy_ogolne" required>
        <br><br>

        <label for="objawy_ju">Objawy miejscowe/ju:</label>
        <input type="text" id="objawy_ju" name="objawy_ju" required>
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
