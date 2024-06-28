<?php
    session_start();
    //łączymy z bazą danych
    if(!$db_link = mysqli_connect("localhost", "blazejm_s29101_WPRG", "G8h44zHNVcptDg98KuDb"))
    {
        exit("blad polaczenia z baza danych");
    }
    if(!mysqli_select_db($db_link, "blazejm_s29101_WPRG"))
    {
        exit("blad wyboru bazy");
    }

    //Sprawdzamy czy jest ktoś z takim nickiem
    $query = "SELECT * FROM uzytkownicy WHERE username ='";
    $query .= $_POST["username"]."'";
    $result = mysqli_query($db_link,$query);
    if(mysqli_num_rows($result) == 0)
    {
        //wysyłamy maila z potwierdzeniem i dodajemy do bazy
        $query = "INSERT INTO uzytkownicy (username, password, adres_email, confirmed, creationTime) VALUES (";
        $query .= "'".mysqli_real_escape_string($db_link,$_POST["username"])."', ";
        $query .= "'".mysqli_real_escape_string($db_link,$_POST["password"])."', ";
        $query .= "'".mysqli_real_escape_string($db_link,$_POST["email"])."', ";
        $query .= "0, ";
        $query .= "CURRENT_TIME())";
        $result = mysqli_query($db_link,$query);
        echo("\n");
        //echo("test");
        //echo($query);
        echo("\n");
        if(!$result)
        {
            echo("błędne zapytanie");
        }
        
        //Generujemy link do potwierdzenia
        //username + id w bazie danych + base64
        $query = "SELECT id FROM uzytkownicy WHERE username ='";
        $query .= $_POST["username"]."'";
        $result = mysqli_query($db_link,$query);
        $row = mysqli_fetch_row($result);
        $result = $row[0];
        $messageLink = $_POST["username"];
        $messageLink .= ".";
        $messageLink .= $result;
        $messageLink = base64_encode($messageLink);


        //wysyłamy email do potwierdzenia
        $to      = $_POST["email"];
        $subject = 'Potwierdzenie email';
        $message = 'Witaj! Aby potwierdzić swoje konto, kliknij w poniższy link!: ';
        $message .= "https://blazejm.easyisp.pl/potwierdzenie.php?id=";
        $message .= $messageLink;
        $headers = 'From: blazejm@blazejm.easyisp.pl' . "\r\n" .
        'Reply-To: blazejm@blazejm.easyisp.pl' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

        mail($to, $subject, $message, $headers);
        
        //header( "refresh:0;url=index.php" );
        echo("Wyslano e-mail z potwierdzeniem");
        echo("\n");
    }
    else
    {
        //header( "refresh:1;url=register.php" );
        echo("Nazwa uzytkownika jest zajeta");
    }

    mysqli_close($db_link);

?>