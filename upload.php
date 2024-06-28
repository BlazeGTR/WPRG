<?php
    session_start();
    if($_COOKIE["session_id"] != $_SESSION['session_id'] || !isset($_COOKIE["session_id"]))
    {
        header( "refresh:2;url=index.php" );
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

    header("refresh:2;url=mojeKonto.php");
    $target_dir = $_POST["path"];
    $target_file = $_POST["name"];
    $target = $target_dir.$target_file;
    if(isset($_POST["submit"])) {
    move_uploaded_file( $_FILES['fileUpload']['tmp_name'], $target);
}
?>