<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Wirusy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

</head>

<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <h1>Baza wirusów</h1>
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

    $selected_id = -1;
    if (isset($_GET["nazwa"]))
    {
        $selected_str = urldecode($_GET["nazwa"]);
        echo "poszukiwanie ${selected_str}<br>";
        $selected_str = pg_escape_string($conn, $selected_str);
        echo "poszukiwanie ${selected_str}<br>";
        $query = "SELECT id FROM wirus WHERE nazwa=${selected_str}";
        echo "zapytanie: ${query}";
        $result = pg_query($conn, $query);
        if($result)
        {
            echo "cos jest";
            $row = pg_fetch_assoc($result);
            print_r($row);
            $selected_id = $row['id'];
        } else
        {
            echo "nie ma";
            $selected_id = -1;
        }
    };
    echo $selected_id;

    // Pobranie danych wirusów z bazy danych
    $query = "SELECT id, nazwa, skrot, genom, wyleganie, szczepionka, droga_zak FROM wirus ORDER BY id";
    $result = pg_query($conn, $query);

    if (pg_num_rows($result) > 0) {
        echo '<table class="table table-striped">
                <thead class="table-dark">
                  <tr>
                    <th>ID</th>
                    <th>Nazwa wirusa lub rodziny wirusów</th>
                    <th>Skrót</th>
                    <th>Genom</th>
                    <th>Okres wylęgania [dni]</th>
                    <th>Szczepionka</th>
                    <th>Droga zakażenia</th>
                    <th></th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>';
    /*if (!$result) {
        echo "Wystąpił błąd podczas pobierania danych wirusów: " . pg_last_error();
        exit();
    }

    echo "<table>";*/

    while ($row = pg_fetch_assoc($result)) {
        if($selected_id == $row['id'])
            echo "<tr class='table-danger'>";
        else
            echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['nazwa'] . "</td>";
        echo "<td>" . $row['skrot'] . "</td>";
        echo "<td>" . $row['genom'] . "</td>";
        echo "<td>" . $row['wyleganie'] . "</td>";
        echo "<td>" . $row['szczepionka'] . "</td>";
        echo "<td>" . $row['droga_zak'] . "</td>";
        echo "<td><button type='button' class='btn btn-primary' onclick='location.href=\"edytuj_wirusa.php?id={$row['id']}\"'>Edytuj</button></td>";
        echo "<td><button type='button' class='btn btn-danger' onclick='location.href=\"usun_wirusa.php?id={$row['id']}\"'>Usuń</button></td>";
        echo "</tr>";
    }
//    echo "</table>";

    echo '</tbody>
          </table>';
    } else {
      echo "Brak dostępnych danych.";
    }

    pg_close($conn);
    ?>
</body>
</html>

<br>
<button type="button" class="btn btn-secondary" onclick="location.href='index.php';">Wróć na stronę główną</button>
