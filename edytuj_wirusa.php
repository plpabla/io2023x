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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

    <h2>Edytuj Wirusa</h2>

    <form action="edytuj_wirusa.php?id=<?php echo $id; ?>" method="POST">
        <div class="form-floating mb-3">    
            <input class="form-control" type="text" id="nazwa" name="nazwa" value="<?php echo $row['nazwa']; ?>" required>
            <label class="form-label" for="nazwa">Nazwa wirusa:</label>
        </div>
    
        <div class="form-floating mb-3">  
            <input class="form-control" type="text" id="skrot" name="skrot" value="<?php echo $row['skrot']; ?>" required>
            <label class="form-label" for="skrot">Skrót:</label>
        </div>

        <div class="form-floating mb-3"> 
            <input class="form-control" type="text" id="genom" name="genom" value="<?php echo $row['genom']; ?>" required>
            <label class="form-label" for="genom">Genom:</label>
        </div>

        <div class="form-floating mb-3"> 
            <input class="form-control" type="text" id="wyleganie" name="wyleganie" value="<?php echo $row['wyleganie']; ?>" required>
            <label class="form-label" for="wyleganie">Wyleganie:</label>
        </div>

        <div class="form-floating mb-3"> 
            <input class="form-control" type="text" id="szczepionka" name="szczepionka" value="<?php echo $row['szczepionka']; ?>" required>
            <label class="form-label" for="szczepionka">Szczepionka:</label>
        </div>

        <div class="form-floating mb-3">
            <input class="form-control" type="text" id="droga_zak" name="droga_zak" value="<?php echo $row['droga_zak']; ?>" required>
            <label class="form-label" for="droga_zak">Droga zakażenia:</label>
        </div>

        <div class="col-12">
            <button class="btn btn-primary" type="submit">Zapisz zmiany</button>
        </div>
    </form>

    <br>
    <button type="button" class="btn btn-secondary" onclick="location.href='wirusy.php';">Powrót do charakterystyki wirusów</button>
    <br><br>
    <button type="button" class="btn btn-secondary" onclick="location.href='index.php';">Wróć na stronę główną</button>
</body>
</html>

<br><br>
