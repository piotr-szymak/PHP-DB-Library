<?php

    if(isset($_POST['ID_DELETE'])) {
          
        $deleteBooks = "DELETE FROM ksiazki WHERE ID_Ksiazka = '".$_POST['ID_DELETE']."';";
        $deleteBooksQuery = $dbh->prepare($deleteBooks);
        $deleteBooksQuery -> execute();
        }

?>