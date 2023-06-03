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

if (isset($_POST['submit'])) {
    // Pobierz wartości z formularza
    $choroba = $_POST['choroba'];
    $objawy_ogolne = $_POST['objawy_ogolne'];
    $objawy_ju = $_POST['objawy_ju'];
    $rozpoznanie = $_POST['rozpoznanie'];
    $roznicowanie = $_POST['roznicowanie'];
    $id_wirus = $_POST['id_wirus'];

    // Połączenie z bazą danych PostgreSQL
    $conn = pg_connect(get_conn_string());
    
    // Zabezpieczenie przed SQL Injection
    $choroba = pg_escape_string($conn, $choroba);
    $objawy_ogolne = pg_escape_string($conn, $objawy_ogolne);
    $objawy_ju = pg_escape_string($conn, $objawy_ju);
    $rozpoznanie = pg_escape_string($conn, $rozpoznanie);
    $roznicowanie = pg_escape_string($conn, $roznicowanie);
    $id_wirus = pg_escape_string($conn, $id_wirus);

    // Aktualizacja danych choroby w bazie danych
    $query = "UPDATE choroba SET choroba = '$choroba', objawy_ogolne = '$objawy_ogolne', objawy_ju = '$objawy_ju', rozpoznanie = '$rozpoznanie', roznicowanie = '$roznicowanie', id_wirus = $id_wirus WHERE id = $id";
    pg_query($conn, $query);

    // Zamknięcie połączenia z bazą danych
    pg_close($conn);

    // Wyświetlenie messageboxa po aktualizacji bazy wirusów
    echo "<script>alert('Baza wirusów została zaktualizowana');</script>";

    // Przekierowanie użytkownika do listy chorób
//    header("Location: index.php");
//    exit();
}

// Pobranie danych choroby o podanym identyfikatorze

// Połączenie z bazą danych PostgreSQL
$conn = pg_connect(get_conn_string());

$query = "SELECT c.id, c.choroba, w.nazwa, c.objawy_ogolne, c.objawy_ju, c.rozpoznanie, c.roznicowanie, c.id_wirus
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
    <title>Edytuj chorobę wirusową</title>
</head>
<body>
    <h2>Edytuj chorobę wirusową</h2>

    <form action="edytuj_chorobe.php?id=<?php echo $id; ?>" method="POST">
        <label for="choroba">Jednostka chorobowa:</label>
        <input type="text" id="choroba" name="choroba" value="<?php echo $row['choroba']; ?>" required>
        <br><br>

        <label for="id_wirus">Czynnik etiologiczny:</label>
        <select id="id_wirus" name="id_wirus" required>
            <?php
            // Połączenie z bazą danych PostgreSQL
            $conn = pg_connect(get_conn_string());

            // Pobranie wszystkich wirusów
            $query = "SELECT * FROM wirus ORDER BY id";
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

        <label for="objawy_ogolne">Objawy ogólne lub miejscowe poza jamą ustną:</label>
        <textarea id="objawy_ogolne" name="objawy_ogolne" required><?php echo $row['objawy_ogolne']; ?></textarea>
        <br><br>

        <label for="objawy_ju">Objawy miejscowe w jamie ustnej:</label>
        <textarea id="objawy_ju" name="objawy_ju" required><?php echo $row['objawy_ju']; ?></textarea>
        <br><br>

        <label for="rozpoznanie">Rozpoznanie:</label>
        <textarea id="rozpoznanie" name="rozpoznanie" required><?php echo $row['rozpoznanie']; ?></textarea>
        <br><br>

        <label for="roznicowanie">Różnicowanie:</label>
        <textarea id="roznicowanie" name="roznicowanie" required><?php echo $row['roznicowanie']; ?></textarea>
        <br><br>

        <input type="submit" name="submit" value="Zapisz zmiany">
    </form>
</body>
</html>

<br><br>
<a href="index.php">Powrót do strony głównej</a>