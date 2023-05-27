<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Choroby wirusowe w stomatologii</title>
</head>

<body>
    wrr brr 
    <?php
    $dbconn = pg_connect("host=localhost dbname=choroby user=moj_uzytkownik password=moje_haslo");
    $query = "SELECT * FROM choroby";
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

//formularz dodawania chorób

<?php
    $dbconn = pg_connect("host=localhost dbname=choroby user=moj_uzytkownik password=moje_haslo");
    $query = "SELECT * FROM choroby";
    $result = pg_query($dbconn, $query);
    //
    <form action="dodaj_chorobe.php" method="POST">
   
<label for="pole1">Jednostka chorobowa:</label>
<input type="text" id="pole1" name="pole1" required>

<label for="pole2">Czynnik etiologiczny:</label>
<input type="text" id="pole2" name="pole2" required>

<label for="pole3">Objawy ogólne i miejscowe poza jamą ustną:</label>
<input type="text" id="pole3" name="pole3" required>

<label for="pole4">Objawy miejscowe w jamie ustnej:</label>
<input type="text" id="pole4" name="pole4" required>

<label for="pole5">Rozpoznanie:</label>
<input type="text" id="pole5" name="pole5" required>

<label for="pole6">Różnicowanie:</label>
<input type="text" id="pole6" name="pole6" required>

    <button type="submit">Dodaj nową jednostkę chorobową</button>
  </form>
      
    pg_close($dbconn);
    ?>
</body>

</html>