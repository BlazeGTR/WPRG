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
                if($row[1] == $_SESSION["user_id"])
                {
                    if(!isset($_GET["confirm"]))
                    {
                        if(!isset($_GET["id"]))
                        {
                            header( "refresh:2;url=main.php");
                            echo("Błędny link!");
                            die();
                        }
                        if(!empty($row))
                        {
                            $link = '<form action="editPost.php?id='.$_GET["id"].'&confirm" method="post">';
                            echo($link);
                            echo('
                                <br>
                                <div style="width: 100%">
                                    <!-- Obszar do pisania postu -->
                                    <label for="message">Treść:<br></label>
                                    <textarea name="message" required maxlength="10000" minlength="10">');

                                    echo($row[2]);
                            echo('</textarea>     
                                </div>
                                <div>
                                    <button type="submit">Post!</button>
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
                        $query = "UPDATE posts SET PostText ='";
                        $query .= $_POST["message"];
                        $query .= "' WHERE PostID =";
                        $query .= $_GET["id"];
                        mysqli_query($db_link,$query);
                        $link = "post.php?id=";
                        $link .= $_GET["id"];
                        header( "refresh:2;url=".$link );
                        //echo($query);
                        echo("Edytowano post!");
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