<?php 
require 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    


<?php




           
if(isset($_POST['Tytul']) && isset($_POST['Jezyk']) && isset($_POST['ID_Kategoria']) && isset($_POST['ID_Ksiazka'])) {

        $tytulKsiazki = htmlspecialchars(strip_tags($_POST['Tytul']));
        $jezykKsiazki = htmlspecialchars(strip_tags($_POST['Jezyk']));
        $numerKsiazki = htmlspecialchars(strip_tags($_POST['ID_Ksiazka']));

        $bookVariables = [
            'Tytul' => $tytulKsiazki,
            'Jezyk' => $jezykKsiazki,
            'ID_Ksiazka' => $numerKsiazki,
        ];
       
        $addBooks = "INSERT INTO ksiazki (Tytul, Jezyk, ID_Ksiazka) VALUES (:Tytul, :Jezyk, :ID_Ksiazka);";
        $addBooksQuery = $dbh->prepare($addBooks);
        $addBooksQuery -> execute($bookVariables);

        foreach($_POST['ID_Kategoria'] as $check){

            $categoryVariables = [
                'ID_Ksiazka' => $_POST['ID_Ksiazka'],
                'ID_Kategoria' => htmlspecialchars($check),
            ];
            $addCategory = "INSERT INTO kategorie_ksiazek (ID_Kategoria, ID_Ksiazka) VALUES (:ID_Kategoria, :ID_Ksiazka);";
            $addCategoryQuery = $dbh->prepare($addCategory);
            $addCategoryQuery -> execute($categoryVariables);      
        }

    } else echo "Nie ma potrzebnych danych";

    ?>

    <form action="addBook.php" method="POST">
        <label for="Tytul">Tytuł książki</label>
        <input type="text" name="Tytul"><br><br>
        <label for="Jezyk">Język</label>
        <input type="text" name="Jezyk"><br><br>
        <label><b>Kategoria</b></label><br>
        <input type="checkbox" name="ID_Kategoria[]" value="1">Horror<br>
       
    <input type="checkbox" name="ID_Kategoria[]" value="2">Fantasy<br>
    
    <input type="checkbox" name="ID_Kategoria[]" value="3">Dramat<br>
    
    <input type="checkbox" name="ID_Kategoria[]" value="4">Naukowe<br>
    
    <input type="checkbox" name="ID_Kategoria[]" value="5">Powiesc<br>
    
        <label for="ID_Ksiazka">Numer identyfikacyjny książki</label>
        <input type="number" name="ID_Ksiazka"><br><br>
    <input type="submit" value="Wyślij">
    </form>

    <br><a href="index.php">Index<a> <a href="addBook.php">Dodaj nową</a>

    <script>
        if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
        }
    </script>

</body>
</html>