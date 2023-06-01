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

// Pobierz identyfikator choroby z parametru URL
$id = $_GET['id'];

// Pobranie danych choroby o podanym identyfikatorze

// Połączenie z bazą danych PostgreSQL
$conn = pg_connect(get_conn_string());

$query = "SELECT c.id, c.jednostka_chorobowa, w.nazwa, c.objawy_ogolne_miejscowe, c.objawy_miejscowe_ju, c.rozpoznanie, c.roznicowanie, c.id_wirus
          FROM choroba c
          JOIN wirus w ON c.id_wirus = w.id
          WHERE c.id = $id";
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

        <label for="id_wirus">Czynnik etiologiczny (wirus):</label>
        <select id="id_wirus" name="id_wirus" required>
            <?php
            // Połączenie z bazą danych PostgreSQL
            $conn = pg_connect(get_conn_string());

            // Pobranie wszystkich wirusów
            $query = "SELECT * FROM wirus";
            $result = pg_query($conn, $query);

            // Iterujesz przez wyniki zapytania i generujesz opcje w formularzu
            while ($virus = pg_fetch_assoc($result)) {
                $selected = ($virus['id'] == $row['id_wirus']) ? "selected" : "";
                echo "<option value='{$virus['id']}' $selected>{$virus['nazwa']}</option>";
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
