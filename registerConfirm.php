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
        echo("Wyslano e-mail z potwierdzeniem");
        echo("\n");
        $query = "INSERT INTO uzytkownicy (username, password, adres_email, confirmed, creationTime) VALUES (";
        $query .= "'".$_POST["username"]."', ";
        $query .= "'".$_POST["password"]."', ";
        $query .= "'".$_POST["email"]."', ";
        $query .= "0, ".
        $query .= "CURRENT_TIME())";
        $result = mysqli_query($db_link,$query);
        echo("\n");
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
        $result = mysqli_fetch_row($result);
        $result = $result[0];

        $messageLink = $_POST["username"];
        $messageLink .= ".";
        $messageLink .= $result;
        $messageLink = base64_encode($messageLink);

        echo($_POST["username"]);

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
    }
    else
    {
        echo("Nazwa uzytkownika jest zajeta");
    }

    mysqli_close($db_link);

?>