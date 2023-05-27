//to poniżej miało być przekierowaniem po dodaniu rekordu
header("Location: chorobywirusowewstomatologii.php");

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Dodawanie jednostki chorobowej</title>
</head>

<body>
       <?php
    $dbconn = pg_connect("host=localhost dbname=choroby user=moj_uzytkownik password=moje_haslo");
   
    $pole1 = $_POST['pole1'];
    $pole2 = $_POST['pole2'];
    $pole3 = $_POST['pole3'];
    $pole4 = $_POST['pole4'];
    $pole5 = $_POST['pole5'];
    $pole6 = $_POST['pole6'];

    //tu powinny być sprawdzenia walidacyjne?

    $query = "INSERT INTO choroby (jednostka_chorobowa, czynnik_etiologiczny, objawy_ogolne_i_miejscowe_poza_ju, objawy_miejscowe_w_ju, rozpoznanie, roznicowanie) VALUES ('$pole1', '$pole2', '$pole3', '$pole4', '$pole5', '$pole6')";
    
    $result = pg_query($dbconn, $query);

    
    pg_close($dbconn);
    ?>

    //tutaj okienko, że baza wirusów została zaktualizowana?
</body>

</html>

//zakończenie headera?
exit();