<?php
    session_start();
    //echo($_COOKIE["session_id"]."\n".$_SESSION['session_id']);
    if($_COOKIE["session_id"] != $_SESSION['session_id'] || !isset($_COOKIE["session_id"]))
    {
        header( "refresh:0;url=index.php" );
        echo("Nie zalogowany!");
        die();
    }
    if(!$db_link = mysqli_connect("localhost", "blazejm_s29101_WPRG", "G8h44zHNVcptDg98KuDb"))
    {
        exit("blad polaczenia z baza danych");
    }
    if(!mysqli_select_db($db_link, "blazejm_s29101_WPRG"))
    {
        exit("blad wyboru bazy");
    }
    //Sanitanizowanie textu
    $_POST["message"] = mysqli_real_escape_string($db_link, $_POST["message"]);
    $_POST["title"] = mysqli_real_escape_string($db_link, $_POST["title"]);

    if(!isset($_GET['ReplyID']))
    {
        $query = "INSERT INTO posts (AuthorID, PostText, Date, tytul, BoardID)";
        $query .= "VALUES ('";
        $query .= explode(".", $_SESSION['session_id'])[1]."', '";
        $query .= $_POST["message"]."', ";
        $query .= "CURRENT_TIME(), '";
        $query .= $_POST["title"]."', '";
        $query .= $_POST["boardIDref"]."')";
        $result = mysqli_query($db_link,$query);
        echo("Posted new");
        header( "refresh:2;url=main.php");
    }
    else
    {
        $query = "INSERT INTO posts (AuthorID, PostText, Date, MasterPostID)";
        $query .= "VALUES ('";
        $query .= explode(".", $_SESSION['session_id'])[1]."', '";
        $query .= $_POST["message"]."', ";
        $query .= "CURRENT_TIME(), '";
        $query .= $_GET["ReplyID"]."')";
        $result = mysqli_query($db_link,$query);
        echo("Posted repky");
        header( "refresh:0;url=main.php");
    }
?>

<html>
    <head>
        <!--<link rel="stylesheet" href="/Styles/MainPageStyle.css">-->
        <meta http-equiv="Cache-Control" content="no-store" />
        <meta charset="utf-8">
    </head>
</html>