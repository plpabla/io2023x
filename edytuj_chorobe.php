<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edytuj chorobę wirusową</title>

    <script>
        function showMessageBox() {
            alert("Baza wirusów została zaktualizowana");
        }
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <h1>Edytuj chorobę wirusową</h1>

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

    // Aktualizacja danych choroby w bazie danych
    $query = "UPDATE choroba SET choroba = '$choroba', objawy_ogolne = '$objawy_ogolne', objawy_ju = '$objawy_ju', rozpoznanie = '$rozpoznanie', roznicowanie = '$roznicowanie', id_wirus = $id_wirus WHERE id = $id";
    $result = pg_query($conn, $query);

    // Zamknięcie połączenia z bazą danych
    pg_close($conn);

    if ($result) {
        echo "<script>showMessageBox();</script>";
    } else {
        echo "Wystąpił błąd podczas edycji choroby.";
    }
}

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
    <form action="edytuj_chorobe.php?id=<?php echo $id; ?>" method="POST">

    <div class="form-floating mb-3">
        <input type="text" id="choroba" name="choroba" value="<?php echo $row['choroba']; ?>" required class="form-control">
        <label for="choroba" class="form-label">Jednostka chorobowa:</label>
    </div>

      
        <div class="form-floating mb-3">
            <input type="text" id="objawy_ogolne" name="objawy_ogolne" value="<?php echo $row['objawy_ogolne']; ?>" required class="form-control">
            <label for="objawy_ogolne" class="form-label">Objawy ogólne i miejscowe poza jamą ustną:</label>
        </div>

        <div class="form-floating mb-3">
            <input type="text" id="objawy_ju" name="objawy_ju" value="<?php echo $row['objawy_ju']; ?>" required class="form-control">
            <label for="objawy_ju" class="form-label">Objawy miejscowe w jamie ustnej:</label>
        </div>

        <div class="form-floating mb-3">
            <input type="text" id="rozpoznanie" name="rozpoznanie" value="<?php echo $row['rozpoznanie']; ?>" required class="form-control">
            <label for="rozpoznanie" class="form-label">Rozpoznanie:</label>
        </div>

        <div class="form-floating mb-3">
            <input type="text" id="roznicowanie" name="roznicowanie" value="<?php echo $row['roznicowanie']; ?>" required class="form-control">
            <label for="roznicowanie" class="form-label">Różnicowanie:</label>
        </div>
        
        <div>
        <label for="id_wirus" >Wybierz wirusa:</label>
    </div>  

        <div class="row">
            <div class="col">
                <div class="form mb-3">
                    <select class="form-select" id="id_wirus" name="id_wirus">
                <?php
            $conn = pg_connect(get_conn_string());

            $query = "SELECT * FROM wirus ORDER BY id";
            $result = pg_query($conn, $query);

            while ($virus = pg_fetch_assoc($result)) {
                $selected = ($virus['id'] == $row['id_wirus']) ? "selected" : "";
                echo "<option value='{$virus['id']}' $selected>{$virus['nazwa']}</option>";
            }

            pg_close($conn);
            ?>               
                </select>
                </div>
            </div>

        <div class="col-12">
            <button class="btn btn-primary" type="submit" name="submit">Zapisz zmiany</button>
        </div>

        
    </form>

    </div>

    <br><br>
    <button type="button" class="btn btn-secondary" onclick="location.href='index.php';">Wróć na stronę główną</button>
          
</body>
</html>