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
        $row = mysqli_fetch_row($result);
        $user_id =$row[0];
        $user_privilege_level = $row[6];
        $sessionid = $_POST["username"].".".$user_id.".".$seed;     //id sesji = nazwa+id+random
        $_SESSION['session_id'] = $sessionid;
        $_SESSION['username'] = $_POST["username"];
        $_SESSION['user_id'] = $user_id;
        $_SESSION['privilege_level'] = $user_privilege_level;
        setcookie("session_id",$sessionid,time()+60*60);
        echo($_SESSION['privilege_level']);
        mysqli_close($db_link);
    }
?>

<html>
</html>
