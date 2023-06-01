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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pobierz identyfikator choroby z parametru URL
    $id = $_GET['id'];

    // Pobranie danych z formularza
    $jednostka_chorobowa = $_POST['jednostka_chorobowa'];
    $objawy_ogolne_miejscowe = $_POST['objawy_ogolne_miejscowe'];
    $objawy_miejscowe_ju = $_POST['objawy_miejscowe_ju'];
    $rozpoznanie = $_POST['rozpoznanie'];
    $roznicowanie = $_POST['roznicowanie'];
    $id_wirus = $_POST['wirus'];

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

        // Aktualizacja danych choroby w bazie danych
        $query = "UPDATE choroba SET jednostka_chorobowa = '$jednostka_chorobowa', objawy_ogolne_miejscowe = '$objawy_ogolne_miejscowe', objawy_miejscowe_ju = '$objawy_miejscowe_ju', rozpoznanie = '$rozpoznanie', roznicowanie = '$roznicowanie', id_wirus = $id_wirus WHERE id = $id";
        $result = pg_query($conn, $query);

        if ($result) {
            echo "Dane choroby zostały zaktualizowane.";
        } else {
            echo "Wystąpił błąd podczas aktualizacji danych choroby.";
        }

        // Zamknięcie połączenia z bazą danych
        pg_close($conn);
    }
} else {
    // Pobierz identyfikator choroby z parametru URL
    $id = $_GET['id'];

    // Połączenie z bazą danych PostgreSQL
    $conn = pg_connect(get_conn_string());

    // Pobranie danych choroby o podanym identyfikatorze
    $query = "SELECT id, jednostka_chorobowa, objawy_ogolne_miejscowe, objawy_miejscowe_ju, rozpoznanie, roznicowanie, id_wirus FROM choroba WHERE id = $id";
    $result = pg_query($conn, $query);
    $row = pg_fetch_assoc($result);

    // Zamknięcie połączenia z bazą danych
    pg_close($conn);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edytuj Chorobę</title>
</head>
<body>
    <h2>Edytuj Chorobę</h2>

    <form action="edytuj_chorobe.php?id=<?php echo $id; ?>" method="POST">
        <label for="jednostka_chorobowa">Jednostka chorobowa:</label>
        <input type="text" id="jednostka_chorobowa" name="jednostka_chorobowa" value="<?php echo $row['jednostka_chorobowa']; ?>" required>
        <br><br>

        <label for="objawy_ogolne_miejscowe">Objawy ogólne/miejscowe:</label>
        <input type="text" id="objawy_ogolne_miejscowe" name="objawy_ogolne_miejscowe" value="<?php echo $row['objawy_ogolne_miejscowe']; ?>" required>
        <br><br>

        <label for="objawy_miejscowe_ju">Objawy miejscowe/ju:</label>
        <input type="text" id="objawy_miejscowe_ju" name="objawy_miejscowe_ju" value="<?php echo $row['objawy_miejscowe_ju']; ?>" required>
        <br><br>

        <label for="rozpoznanie">Rozpoznanie:</label>
        <input type="text" id="rozpoznanie" name="rozpoznanie" value="<?php echo $row['rozpoznanie']; ?>" required>
        <br><br>

        <label for="roznicowanie">Różnicowanie:</label>
        <input type="text" id="roznicowanie" name="roznicowanie" value="<?php echo $row['roznicowanie']; ?>" required>
        <br><br>

        <label for="wirus">Wybierz wirusa:</label>
        <select id="wirus" name="wirus">
            <?php
            // Połączenie z bazą danych PostgreSQL
            $conn = pg_connect(get_conn_string());

            // Pobranie wszystkich wirusów
            $query = "SELECT id, nazwa FROM wirus";
            $result = pg_query($conn, $query);

            // Generowanie opcji dla listy rozwijanej
            while ($wirus = pg_fetch_assoc($result)) {
                $selected = ($wirus['id'] == $row['id_wirus']) ? 'selected' : '';
                echo "<option value='" . $wirus['id'] . "' $selected>" . $wirus['nazwa'] . "</option>";
            }

            // Zamknięcie połączenia z bazą danych
            pg_close($conn);
            ?>
        </select>
        <br><br>

        <input type="submit" value="Zapisz zmiany">
    </form>
</body>
</html>
