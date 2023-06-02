<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Wirusy</title>
</head>

<body>
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
    $query = "SELECT id, nazwa, skrot, genom, wyleganie, szczepionka, droga_zak FROM wirus ORDER BY id";";
    $result = pg_query($conn, $query);

    if (pg_num_rows($result) > 0) {
        echo '<table>
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Nazwa wirusa lub rodziny wirusów</th>
                    <th>Skrót</th>
                    <th>Genom</th>
                    <th>Okres wylęgania [dni]</th>
                    <th>Szczepionka</th>
                    <th>Droga zakażenia</th>
                  </tr>
                </thead>
                <tbody>';
    /*if (!$result) {
        echo "Wystąpił błąd podczas pobierania danych wirusów: " . pg_last_error();
        exit();
    }

    echo "<table>";*/
    while ($row = pg_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['nazwa'] . "</td>";
        echo "<td>" . $row['skrot'] . "</td>";
        echo "<td>" . $row['genom'] . "</td>";
        echo "<td>" . $row['wyleganie'] . "</td>";
        echo "<td>" . $row['szczepionka'] . "</td>";
        echo "<td>" . $row['droga_zak'] . "</td>";
        echo "<td><a href='edytuj_wirusa.php?id={$row['id']}'>Edytuj</a></td>";
        echo "<td><a href='usun_chorobe.php?id={$row['id']}'>Usuń</a></td>";
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
