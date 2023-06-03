<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dodaj chorobę</title>

    <script>
        function showMessageBox() {
            alert("Baza wirusów została zaktualizowana");
        }
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
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

    //////////////////////////////////////////////////////////////////////
    //////////////// wrócić!! 
    //////////////// $_SERVER['REQUEST_METHOD']
    //////////////////////////////////////////////////////////////////////
    if ('' === 'POST') {
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
//                echo "Baza chorób została zaktualizowana.";
                echo "<script>showMessageBox();</script>";
            } else {
                echo "Wystąpił błąd podczas dodawania choroby.";
            }

            // Zamknięcie połączenia z bazą danych
            pg_close($conn);
        }
    }
    ?>

    <form action="dodaj_chorobe.php" method="POST">
        <div class="form-floating mb-3">
            <!-- <span class="input-group-text" id="basic-addon1">Jednostka chorobowa</span> -->
            <input type="text" id="choroba" name="choroba" required class="form-control">
            <label for="choroba" class="form-label">Jednostka chorobowa:</label>
        </div>

        <label for="id_wirus">Wybierz wirusa:</label>  
        <div class="row">
            <div class="col">
                <div class="form mb-3">
                
                <select class="form-select" id="id_wirus" name="id_wirus">
                    <?php
                    if (!empty($wirusy)) {
                        foreach ($wirusy as $wirus) {
                            echo "<option value='" . $wirus['id'] . "'>" . $wirus['nazwa'] . "</option>";
                        }
                    }
                    ?>
                </select>
                </div>
            </div>

            <div class="col">
                <div class="form mb-3">
                    <button type="button" class="btn btn-secondary" onclick="location.href='dodaj_wirusa.php';">Dodaj nowego wirusa</button>
                </div>
            </div>
        </div>
        

        <div class="form-floating mb-3">
            <input type="text" id="objawy_ogolne" name="objawy_ogolne" required class="form-control">
            <label for="objawy_ogolne" class="form-label">Objawy ogólne/miejscowe:</label>
        </div>

        <div class="form-floating mb-3">
            <input type="text" id="objawy_ju" name="objawy_ju" required class="form-control">
            <label for="objawy_ju" class="form-label">Objawy miejscowe/ju:</label>
        </div>

        <div class="form-floating mb-3">
            <input type="text" id="rozpoznanie" name="rozpoznanie" required class="form-control">
            <label for="rozpoznanie" class="form-label">Rozpoznanie:</label>
        </div>

        <div class="form-floating mb-3">
            <input type="text" id="roznicowanie" name="roznicowanie" required class="form-control">
            <label for="roznicowanie" class="form-label">Różnicowanie:</label>
        </div>

        <div class="col-12">
            <button class="btn btn-primary" type="submit">Dodaj chorobę</button>
        </div>
    </form>

    <br><br>
    <button type="button" class="btn btn-secondary" onclick="location.href='index.php';">Wróć na stronę główną</button>

</body>
</html>
