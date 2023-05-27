<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Choroby wirusowe w stomatologii</title>
</head>

<body>
    wrr brr 
    <?php
    $dbconn = pg_connect("host=localhost dbname=jednostka_chorobowa user=moj_uzytkownik password=moje_haslo");
    $query = "SELECT * FROM jednostka_chorobowa";
    $result = pg_query($dbconn, $query);
    
    echo "<table>";
    while ($row = pg_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['ID'] . "</td>";
        echo "<td>" . $row['Jednostka chorobowa'] . "</td>";
        echo "<td>" . $row['Czynnik etiologiczny'] . "</td>";
        echo "<td>" . $row['Objawy ogólne i miejscowe poza jamą ustną'] . "</td>";
        echo "<td>" . $row['Objawy miejscowe w jamie ustnej'] . "</td>";
        echo "<td>" . $row['Rozpoznanie'] . "</td>";
        echo "<td>" . $row['Różnicowanie'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    pg_close($dbconn);
    ?>
</body>

</html>