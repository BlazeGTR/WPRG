<?php
//header( "refresh:2;url=main.php" );
    session_start();
    if(!$db_link = mysqli_connect("localhost", "blazejm_s29101_WPRG", "G8h44zHNVcptDg98KuDb"))
    {
        exit("blad polaczenia z baza danych");
    }
    if(!mysqli_select_db($db_link, "blazejm_s29101_WPRG"))
    {
        exit("blad wyboru bazy");
    }
    $query = "SELECT * FROM uzytkownicy WHERE username ='";
    $query .= $_POST["username"]."'";
    $query .= "AND password ='";
    $query .= $_POST["password"]."'"; 
    $query .= "AND confirmed =1";
    $result = mysqli_query($db_link,$query);
    //echo($query);

    if(mysqli_num_rows($result) == 0)
    {
        header( "refresh:2;url=index.html" );
        echo("Bledny login lub haslo!");
    }
    else{
        header( "refresh:2;url=main.php" );
        echo("Zalogowano!");
        $seed = rand(0, 1000);
        $sessionid = $_POST["username"].".".mysqli_fetch_row($result)[0].".".$seed;     //id sesji = nazwa+id+random
        $_SESSION['session_id'] = $sessionid;
        setcookie("session_id",$sessionid,time()+60*30);
        mysqli_close($db_link);
    }
?>

<html>
</html>
