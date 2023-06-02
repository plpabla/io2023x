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

// Pobierz identyfikator wirusa z parametru URL
$id = $_GET['id'];

if (isset($_POST['submit'])) {
    // Pobierz wartości z formularza
    $nazwa = $_POST['nazwa'];
    $skrot = $_POST['skrot'];
    $genom = $_POST['genom'];
    $wyleganie = $_POST['wyleganie'];
    $szczepionka = $_POST['szczepionka'];
    $droga_zak = $_POST['droga_zak'];

    // Połączenie z bazą danych PostgreSQL
    $conn = pg_connect(get_conn_string());

    // Aktualizacja danych wirusa w bazie danych
    $query = "UPDATE wirus SET nazwa = '$nazwa', skrot = '$skrot', genom = '$genom', wyleganie = '$wyleganie', szczepionka = '$szczepionka', droga_zak = '$droga_zak' WHERE id = $id";
    pg_query($conn, $query);

    // Zamknięcie połączenia z bazą danych
    pg_close($conn);

     // Wyświetlenie messageboxa po aktualizacji bazy wirusów
     echo "<script>alert('Baza wirusów została zaktualizowana');</script>";

    // Przekierowanie użytkownika do listy wirusów
//    header("Location: wirusy.php");
//   exit();
}

// Pobranie danych wirusa o podanym identyfikatorze

// Połączenie z bazą danych PostgreSQL
$conn = pg_connect(get_conn_string());

$query = "SELECT * FROM wirus WHERE id = $id";
$result = pg_query($conn, $query);
$row = pg_fetch_assoc($result);

// Zamknięcie połączenia z bazą danych
pg_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edytuj Wirusa</title>
</head>
<body>
    <h2>Edytuj Wirusa</h2>

    <form action="edytuj_wirusa.php?id=<?php echo $id; ?>" method="POST">
        <label for="nazwa">Nazwa wirusa:</label>
        <input type="text" id="nazwa" name="nazwa" value="<?php echo $row['nazwa']; ?>" required>
        <br><br>

        <label for="skrot">Skrót:</label>
        <input type="text" id="skrot" name="skrot" value="<?php echo $row['skrot']; ?>" required>
        <br><br>

        <label for="genom">Genom:</label>
        <input type="text" id="genom" name="genom" value="<?php echo $row['genom']; ?>" required>
        <br><br>

        <label for="wyleganie">Wyleganie:</label>
        <input type="text" id="wyleganie" name="wyleganie" value="<?php echo $row['wyleganie']; ?>" required>
        <br><br>

        <label for="szczepionka">Szczepionka:</label>
        <input type="text" id="szczepionka" name="szczepionka" value="<?php echo $row['szczepionka']; ?>" required>
        <br><br>

        <label for="droga_zak">Droga zakażenia:</label>
        <input type="text" id="droga_zak" name="droga_zak" value="<?php echo $row['droga_zak']; ?>" required>
        <br><br>

        <input type="submit" name="submit" value="Zapisz zmiany">
    </form>
</body>
</html>

<br><br>
<a href="wirusy.php">Powrót do charakterystyki wirusów</a>