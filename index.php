<!DOCTYPE html>
<html>
<head>
    <title>Tabela Chorób</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

    <script>
    $(document).ready(function () {
        $('#search').autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: "search.php",
                    method: "GET",
                    data: { search: request.term },
                    dataType: "json",
                    success: function (data) {
                        var suggestions = [];
                        $.each(data, function (index, item) {                            
                            suggestions.push(item.choroba);
                            suggestions.push(item.nazwa_wirusa);
                            suggestions.push(item.objawy_ogolne);
                            suggestions.push(item.objawy_ju);
                            suggestions.push(item.rozpoznanie);
                            suggestions.push(item.roznicowanie);
                        });
                        response(suggestions);
                    }
                });
            },
            minLength: 2
        });
    });
    </script>

</head>

<body>
 <!--   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
-->
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
    ?>

      <div class="container">

        <h1>Wybrane choroby wirusowe manifestujące objawy w jamie ustnej</h1>

        <button type="button" class="btn btn-primary" onclick="location.href='dodaj_chorobe.php';">Dodaj chorobę</button>
        <button type="button" class="btn btn-primary" onclick="location.href='wirusy.php';">Wyświetl wirusy</button>
        <button type="button" class="btn btn-secondary" onclick="location.href='info.html';">Info o bazie</button>
        <br>

        <form method="GET">
            <div class="form-group">
                <label for="search">Wyszukaj:</label>
                <input type="text" class="form-control" id="search" name="search" >
            </div>
            <button type="submit" class="btn btn-primary">Szukaj</button>
        </form>
        <br>

<?php

// sprawdzenie czy php.ini jest poprawnie odczytywany
  $ini = parse_ini_file("php.ini");
  // var_dump($ini);

  // Połączenie z bazą danych PostgreSQL
$conn = pg_connect(get_conn_string());

// Pobranie wartości wyszukiwania z pola tekstowego
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Zaktualizuj zapytanie SQL z warunkiem WHERE
$query = "SELECT c.id, c.choroba, w.nazwa AS nazwa_wirusa, c.objawy_ogolne, c.objawy_ju, c.rozpoznanie, c.roznicowanie
          FROM choroba c
          JOIN wirus w ON c.id_wirus = w.id";

if (!empty($search)) {
    // Dodaj warunek WHERE, jeśli podano wartość wyszukiwania
    $query .= " WHERE c.choroba ILIKE '%" . pg_escape_string($search) . "%'
                OR w.nazwa ILIKE '%" . pg_escape_string($search) . "%'
                OR c.objawy_ogolne ILIKE '%" . pg_escape_string($search) . "%'
                OR c.objawy_ju ILIKE '%" . pg_escape_string($search) . "%'
                OR c.rozpoznanie ILIKE '%" . pg_escape_string($search) . "%'
                OR c.roznicowanie ILIKE '%" . pg_escape_string($search) . "%'";
}

$query .= " ORDER BY c.id";

// Pobranie danych z tabeli choroba, wraz z nazwą wirusa
$result = pg_query($conn, $query);

// Sprawdzenie, czy przesłano wartość wyszukiwania
if (!empty($search)) {
    echo "<h3>Wyniki wyszukiwania dla: " . htmlentities($search) . "</h3>";
}

// Sprawdzenie, czy są dostępne dane
if (pg_num_rows($result) > 0) {
    echo '<table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Lp.</th>
                    <th scope="col">Jednostka chorobowa</th>
                    <th scope="col">Czynnik etiologiczny</th>
                    <th scope="col">Objawy ogólne lub miejscowe poza jamą ustną</th>
                    <th scope="col">Objawy miejscowe w jamie ustnej</th>
                    <th scope="col">Rozpoznanie</th>
                    <th scope="col">Różnicowanie</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>';

    $lp = 1; // Zmienna licznikowa

    // Iteracja przez wyniki zapytania i generowanie wierszy tabeli HTML
    while ($row = pg_fetch_assoc($result)) {
        echo "<tr>";
        echo "<th scope='row'>" . $lp . "</td>";
        echo "<td>" . $row['choroba'] . "</td>";
        echo "<td><a href='wirusy.php?nazwa=" . urlencode($row['nazwa_wirusa']) . "'>" . $row['nazwa_wirusa'] . "</a></td>";
        echo "<td>" . $row['objawy_ogolne'] . "</td>";
        echo "<td>" . $row['objawy_ju'] . "</td>";
        echo "<td>" . $row['rozpoznanie'] . "</td>";
        echo "<td>" . $row['roznicowanie'] . "</td>";
        echo "<td><button type='button' class='btn btn-primary' onclick='location.href=\"edytuj_chorobe.php?id={$row['id']}\"'>Edytuj</button></td>";
        echo "<td><button type='button' class='btn btn-danger' onclick='location.href=\"usun_chorobe.php?id={$row['id']}\"'>Usuń</button></td>";
        echo "</tr>";

        $lp++; // Inkrementacja zmiennej licznikowej
    }
   
    echo '</tbody>
        </table>';
} else {
    echo "Brak dostępnych danych.";
}

// Zamknięcie połączenia z bazą danych
pg_close($conn);
?>

</div>

</body>

</html>