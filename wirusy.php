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
    $query = "SELECT * FROM wirus";
    $result = pg_query($conn, $query);

    if (!$result) {
        echo "Wystąpił błąd podczas pobierania danych wirusów: " . pg_last_error();
        exit();
    }

    echo "<table>";
    while ($row = pg_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['ID'] . "</td>";
        echo "<td>" . $row['Nazwa wirusa lub rodziny wirusów'] . "</td>";
        echo "<td>" . $row['Skrót'] . "</td>";
        echo "<td>" . $row['Genom'] . "</td>";
        echo "<td>" . $row['Okres wylęgania [dni]'] . "</td>";
        echo "<td>" . $row['Szczepionka'] . "</td>";
        echo "<td>" . $row['Droga zakażenia'] . "</td>";
        echo "<td>" . $row['Jednostki chorobowe'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";

    pg_close($conn);
    ?>
</body>

</html>
