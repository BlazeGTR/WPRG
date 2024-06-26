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
?>

<html>
    <head>
        <link rel="stylesheet" href="/Styles/MainPageStyle.css">
        <meta http-equiv="Cache-Control" content="no-store" />
        <meta charset="utf-8">
    </head>

    <header class="center">
        <section class="topnav">
            <div>
                <a href="main.php">Strona główna</a>
                <a href="contact.php">Kontakt</a>
                <a href="about.php">O nas</a>
            </div>
            <div style="margin-left: auto; margin-right: 0; float: right;">
                <a href="mojeKonto.php" class="active">Moje Konto</a>
                <a href="logout.php">Wyloguj</a>
            </div>
        </section>
        <a href="https://blazejm.easyisp.pl/main.php">
           <img src="/Assets/logo.png" alt="Logo">
        </a>
    </header>

    <body>
        <div class="main-board">
            <div class="post">
                <h1 class="post-header">
                    <section>
                        <div class="date-time" style="text-align: right">
                            <?php
                                $query = "SELECT creationTime FROM uzytkownicy WHERE username='";
                                $query .= $_SESSION["username"]."'";
                                $result = mysqli_query($db_link,$query);
                                $row = mysqli_fetch_row($result);
                                echo("Data utworzenia:<br>");
                                echo(substr($row[0], 0, 10));
                            ?>
                        </div>
                        <div style="font-size: 200%">
                            <?php
                                echo($_SESSION["username"]);
                            ?>
                        </div>
                    </section>
                </h1>
                <form action="upload.php" method="post" enctype="multipart/form-data">
                    Zmień obraz profilowy:
                    <input type="file" accept=".png" name="fileUpload" id="fileUpload">
                    <input id="path" type="hidden" name="path" value='./Assets/ProfilePics/'>
                    <input id="name" type="hidden" name="name" <?php echo('value=pfp_'.intval($_SESSION["user_id"]).".png"); ?>>
                    <input type="submit" value="Wyślij" name="submit">
                </form>
            </div>
        </div>
    </body>
</html>


