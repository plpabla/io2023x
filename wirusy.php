<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Wirusy</title>
</head>

<body>
    <?php
    $dbconn = pg_connect("host=localhost dbname=wirusy user=moj_uzytkownik password=moje_haslo");
    $query = "SELECT * FROM wirusy";
    $result = pg_query($dbconn, $query);
    
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
    
    pg_close($dbconn);
    ?>
</body>

</html>