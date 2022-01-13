    <?php
    
    $dbhost="localhost";
    $dbuser="root";
    $dbpass="";
    $dbname="library";


    try {
    
        $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);  
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            if($dbh) {
                echo "Connected to the $dbname datebase successfully!";
            }
        } catch (PDOException $e) {
            echo $e -> getMessage();
        }
    ?>