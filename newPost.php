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
                <a href="#contact">Kontakt</a>
                <a href="#about">O nas</a>
            </div>
            <div style="margin-left: auto; margin-right: 0; float: right;">
                <a href="mojeKonto.php">Moje Konto</a>
                <a href="logout.php">Wyloguj</a>
            </div>
        </section>
        <a href="https://blazejm.easyisp.pl/main.php">
           <img src="/Assets/logo.png" alt="Logo">
        </a>
    </header>

    <body>
        <div class="main-board">
            <div class="form">
                <?php
                $link ="";
                $badLink = false;
                if(!isset($_GET["id"]))
                {
                    $link = '<form action="newPosted.php" method="post">';
                    echo($link);
                    echo('
                        <div>
                            <label for="title">Tytuł:</label>
                            <input type="text" name="title" required maxlength="30" minlength="3">
                        </div>');
                }
                else
                {
                    if(!is_numeric($_GET["id"]))
                    {
                        echo("Błędny link!");
                        die();
                    }
                        $link = '<form action="newPosted.php?ReplyID='.$_GET["id"].'" method="post">';
                        echo($link);
                        $query = "SELECT * FROM posts WHERE PostID =";
                        $query .= $_GET["id"];
                        $result = mysqli_query($db_link,$query);
                        $row = mysqli_fetch_row($result);
                        echo("<br>");
                        if(!empty($row)){
                            //Zaczynamy post
                            echo ('<div class="post">');
                            //zaczynamy nagłówek
                            echo('<h1 class="post-header">');
                                //Szukamy nazwy usera po id
                                $query = "SELECT * FROM uzytkownicy WHERE id =";
                                $query .= $row[1]."";
                                $results = mysqli_query($db_link,$query);
                                    echo('<section>');
                                        echo(mysqli_fetch_row($results)[1]);    //Autor
                                        echo ('<div class="date-time">');
                                            echo ($row[3]); //data stworzenia
                                        echo ('</div>');
                                    echo('</section>');
                                echo ('</h1>');
                                //Post text
                                echo ('<div class="post-text">');
                                    echo($row[2]);  //Główny text postu
                                echo ('</div>');
                            echo ("</div>");
                        }
                        else
                        {
                            echo('<h1 class="post-header">');
                                echo("Błędny link!");
                                $badLink = true;
                            echo('</h1>');
                        }
                }
                if(!$badLink){
                    echo('
                        <br>
                        <div style="width: 100%">
                            <!-- Obszar do pisania postu -->
                            <label for="message">Treść:<br></label>
                            <textarea name="message" required maxlength="10000" minlength="10">Treść postu</textarea>     
                        </div>
                        <div>
                            <button type="submit">Post!</button>
                        </div>
                        ');
                    }
                    ?>
                </form>
            </div>
        </div>
    </body>
</html>