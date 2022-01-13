<?php
require 'config.php';
require 'deleteBook.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteka</title>
</head>
<body>

<?php

    // Kolumny tabeli
    $columns = array('ID_Ksiazka','Tytul','Jezyk');

    // Sprawdz czy istnieje w tabeli, jezeli nie sortuj po pierwszym elemencie w tablicy.
    $column = isset($_GET['column']) && in_array($_GET['column'], $columns) ? $_GET['column'] : $columns[0];

    // Sortowanie
    $sortOrder = isset($_GET['order']) && strtolower($_GET['order']) == 'desc' ? 'DESC' : 'ASC';

	$sortowanie = $sortOrder == 'ASC' ? 'desc' : 'asc';

    $searchBoxText = isset($_GET['wyszukaj']) ? $_GET['wyszukaj'] : false;

    $searchBox = htmlspecialchars($searchBoxText);


    $selectCondition = isset($_GET['wyszukaj']) ? 
    "SELECT ksiazki.Tytul, ksiazki.Jezyk, ksiazki.ID_Ksiazka, GROUP_CONCAT(kategorie.ID_Kategoria ORDER BY kategorie.ID_Kategoria $sortOrder SEPARATOR ', ') AS ID_Kategoria
        FROM ksiazki 
        INNER JOIN kategorie_ksiazek
                ON ksiazki.ID_Ksiazka = kategorie_ksiazek.ID_Ksiazka
        INNER JOIN kategorie 
                ON kategorie_ksiazek.ID_Kategoria = kategorie.U_ID 
        GROUP BY ksiazki.ID_Ksiazka
        HAVING ksiazki.Tytul LIKE '%$searchBox%' OR ID_Kategoria LIKE '%$searchBox%' OR ksiazki.ID_Ksiazka = '$searchBox'
        ORDER BY $column $sortOrder
        ":

        "SELECT ksiazki.Tytul, ksiazki.Jezyk, ksiazki.ID_Ksiazka, GROUP_CONCAT(kategorie.ID_Kategoria ORDER BY kategorie.ID_Kategoria $sortOrder SEPARATOR ', ') AS ID_Kategoria
        FROM ksiazki 
        INNER JOIN kategorie_ksiazek
        ON ksiazki.ID_Ksiazka = kategorie_ksiazek.ID_Ksiazka
        INNER JOIN kategorie 
        ON kategorie_ksiazek.ID_Kategoria = kategorie.U_ID
        GROUP BY ksiazki.ID_Ksiazka
        ORDER BY $column $sortOrder";
        
    // Wyswietlanie tabeli
    if($wynik = $dbh->prepare($selectCondition)) {
        $wynik->execute();

        $searchBoxToURL =  isset($_GET['wyszukaj']) ? "&wyszukaj=$searchBox": false;
?>
            <table cellpadding=6 border=1>
            <tr>
                    <td>
                        <b><a href="index.php?column=ID_Ksiazka&order=<?php echo $sortowanie; echo $searchBoxToURL; ?>">ID Ksiazki</a></b>
                    </td>
                    <td>
                        <b><a href="index.php?column=Tytul&order=<?php echo $sortowanie;echo $searchBoxToURL; ?>">Tytul</a></b>
                    </td>
                    <td>
                        <b><a href="index.php?column=Kategoria&order=<?php echo $sortowanie;echo $searchBoxToURL; ?>">Kategoria</a></b>
                    </td>
                    <td>
                        <b><a href="index.php?column=Jezyk&order=<?php echo $sortowanie;echo $searchBoxToURL; ?>">Jezyk</a></b>
                    </td>
                    <td>
                        <b>Usun</b>
                    </td>
            </tr>

        <?php
                $rekord = $wynik->fetchAll();
               
                foreach($rekord as $key => $value ) {
                    $nr = $value['ID_Ksiazka'];
                    $tytul = $value['Tytul'];
                    $kategoria = $value['ID_Kategoria'];
                    $jezyk = $value['Jezyk'];

                    print "<tr><td>$nr</td><td>$tytul</td><td>$kategoria</td><td>$jezyk</td>";
                    print '<form action="index.php" method="POST">';
                    print '<input type="hidden" name="ID_DELETE" value="'.$nr.'">';
                    print '<td><input type="submit" value="Skasuj"></td></tr>';
                    print '</form>';
                }

                print "</table>"

                ?>
                <br>
                <form action="index.php" method="get">
                    <label for="Wyszukaj">Wyszukaj książki</label>
                    <input type="search" name="wyszukaj" placeholder="Tytul / Kategoria / Numer książki">
                    <input type="submit" value="Szukaj">
                </form>
            
                <br><a href="index.php">Index<a> <a href="addBook.php">Dodaj ksiazke</a>
            </body>
            </html>

        <?php	
    }
        ?>