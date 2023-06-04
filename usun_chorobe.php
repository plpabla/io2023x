<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Usuń chorobę</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>
<body>
    <div class="container">
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

        // Połączenie z bazą danych PostgreSQL
        $conn = pg_connect(get_conn_string());

        // Usunięcie choroby o podanym identyfikatorze
        $query = "DELETE FROM choroba WHERE id = $id";
        $result = pg_query($conn, $query);

        if ($result) {
            echo '<div class="alert alert-success" role="alert">
                    Jednostka chorobowa została usunięta
                  </div>';
        } else {
            echo '<div class="alert alert-danger" role="alert">
                    Wystąpił błąd podczas usuwania choroby
                  </div>';
        }

        // Zamknięcie połączenia z bazą danych
        pg_close($conn);
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

    <br><br>
    <button type="button" class="btn btn-secondary" onclick="location.href='index.php';">Wróć na stronę główną</button>

</body>
</html>
