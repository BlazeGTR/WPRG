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
                $query = "SELECT * FROM posts WHERE PostID =";
                $query .= $_GET["id"];
                $result = mysqli_query($db_link,$query);
                $row = mysqli_fetch_row($result);
                if($row[1] == $_SESSION["user_id"]  || $_SESSION['privilege_level'] > 0)
                {
                    if(!isset($_GET["confirm"]))
                    {
                        if(!is_numeric($_GET["id"]))
                        {
                            header( "refresh:2;url=main.php");
                            echo("Błędny link!");
                            die();
                        }
                        if(!empty($row))
                        {
                            $link = '<form action="deletePost.php?id='.$_GET["id"].'&confirm" method="post">';
                            echo($link);
                            //Zaczynamy post
                            echo ('<div class="comment">');
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
                            echo('   
                                <div>
                                    <br>
                                    <label for="submit">Czy na pewno chcesz usunąć post?<br></label><br>
                                    <button name="submit" type="submit">Usuń</button>
                                </div>
                                </form>
                                ');
                        }
                        else
                        {
                            header( "refresh:2;url=main.php");
                            echo("Błędny link!");
                            die();
                        }
                    }
                    else
                    {
                        $query = "DELETE FROM posts WHERE PostID =";
                        $query .= $_GET["id"];
                        mysqli_query($db_link,$query);
                        header( "refresh:2;url=main.php" );
                        //echo($query);
                        echo("Usunięto post!");
                    }
                }
                else
                {

                    echo("Nie masz uprawnień do edytowania tego postu!");
                    header( "refresh:2;url=main.php" );
                }
                    ?>
            </div>
        </div>
    </body>
</html>