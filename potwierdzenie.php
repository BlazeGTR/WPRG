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

    //Rozwalamy id na nick + id w bazie
    $id = $_GET["id"];
    $id = base64_decode($id);
    $id = explode(".", $id);

    //sprawdzamy czy ktos taki jest w bazie
    $query = "SELECT * FROM uzytkownicy WHERE username ='";
    $query .= $id[0]."' AND ";
    $query .= "id =".$id[1];
    $result = mysqli_query($db_link,$query);
    if(mysqli_num_rows($result) == 1)
    {
        echo("Potwierdzono email!");
        $query = "UPDATE `uzytkownicy` SET `confirmed` = '1' WHERE `uzytkownicy`.`id` = ".$id[1];
        mysqli_query($db_link,$query);
    }
    else
    {
        echo("Niepoprawny link!");
    }
?>