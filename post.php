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
            <?php
                function displayComment($row, $db_link_func)
                {
                    //Zaczynamy post
                    echo ('<div class="comment">');
                        //zaczynamy nagłówek
                        echo('<h1 class="post-header">');
                            //Szukamy nazwy usera po id
                            $query = "SELECT * FROM uzytkownicy WHERE id =";
                            $query .= $row[1]."";
                            $results = mysqli_query($db_link_func,$query);
                            echo('<section>');
                                echo(mysqli_fetch_row($results)[1]);    //Autor
                                echo ('<div class="date-time">');
                                    echo ($row[3]); //data stworzenia
                                echo ('</div>');
                                    echo("<br>");   //Link do odpowiedzi
                                    $link = '<a href="https://blazejm.easyisp.pl/newPost.php?id=';
                                    $link .= $row[0];
                                    $link .= '" class="reply">Odpowiedz ->  </a>&emsp;';
                                    echo($link);
                                    if($row[1] == $_SESSION["user_id"])  //Link do edycji tylko jak nasz post
                                    {
                                        $link = '<a href="https://blazejm.easyisp.pl/editPost.php?id=';
                                        $link .= $row[0];
                                        $link .= '" class="reply">Edytuj -></a>&emsp;';
                                        echo($link);
                                    }
                                    if($row[1] == $_SESSION["user_id"] || $_SESSION['privilege_level'] > 0)  //Link do edycji tylko jak nasz post
                                    {
                                        $link = '<a href="https://blazejm.easyisp.pl/deletePost.php?id=';
                                        $link .= $row[0];
                                        $link .= '" class="reply">Usuń -></a>&emsp;';
                                        echo($link);
                                    }
                            echo('</section>');
                        echo ('</h1>');
                        //Post text
                        echo ('<div class="post-text">');
                            echo($row[2]);  //Główny text postu
                        echo ('</div>');
                    
                        $query = "SELECT * FROM posts WHERE MasterPostID = $row[0]";
                        $results = mysqli_query($db_link_func,$query);
                        echo("<br>");
                        while ($row = mysqli_fetch_row($results))
                        {
                            displayComment($row, $db_link_func);
                        }
                    echo ("</div>");
                }

                function displayPost($row, $db_link_func)
                {
                    //Zaczynamy post
                    echo ('<div class="post">');
                    //zaczynamy nagłówek
                    echo('<h1 class="post-header">');
                        //Szukamy nazwy usera po id
                        $query = "SELECT * FROM uzytkownicy WHERE id =";
                        $query .= $row[1]."";
                        $results = mysqli_query($db_link_func,$query);
                            echo('<section>');
                                echo(mysqli_fetch_row($results)[1]);    //Autor
                                echo ('<div class="date-time">');
                                    echo ($row[3]); //data stworzenia
                                echo ('</div>');
                                echo ('<br>');
                                if($row[1] == $_SESSION["user_id"])  //Link do edycji tylko jak nasz post
                                {
                                        $link = '<a href="https://blazejm.easyisp.pl/editPost.php?id=';
                                        $link .= $row[0];
                                        $link .= '" class="reply">Edytuj -></a>&emsp;';
                                        echo($link);
                                }
                                if($row[1] == $_SESSION["user_id"] || $_SESSION['privilege_level'] > 0)  //Link do edycji tylko jak nasz post
                                {
                                    $link = '<a href="https://blazejm.easyisp.pl/deletePost.php?id=';
                                    $link .= $row[0];
                                    $link .= '" class="reply">Usuń -></a>&emsp;';
                                    echo($link);
                                }
                            echo('</section>');
                            echo ('<div class="post-title">');  
                                echo($row[4]);
                            echo ('</div>');
                        echo ('</h1>');
                        //Post text
                        echo ('<div class="post-text">');
                            echo($row[2]);  //Główny text postu
                        echo ('</div>');
                    echo ("</div>");

                    $query = "SELECT * FROM posts WHERE MasterPostID = $row[0]";
                    $results = mysqli_query($db_link_func,$query);
                    echo("<br>");
                    while ($row = mysqli_fetch_row($results))
                    {
                        displayComment($row, $db_link_func);
                    }
                }

                $query = "SELECT * FROM posts WHERE PostID =";
                $query .= $_GET["id"];
                $result = mysqli_query($db_link,$query);
                $row = mysqli_fetch_row($result);
                displayPost($row, $db_link);
                ?>
        </div>
        <!-- KOMENTARZE -->
        <div class="main-board" style="border: 2px solid black; margin-top: 10px;">
        <?php
            $link = 'newPosted.php?ReplyID=';
            $link .= $_GET["id"];
            echo('<div class="form">');
            echo('<form action="'.$link.'" method="post">');
        ?>
                <br>
                <div style="width: 100%">
                    <!-- Obszar do pisania postu -->
                    <label for="message">Odpowiedź:<br></label>
                    <textarea name="message" required maxlength="10000" minlength="10"></textarea>     
                </div>
                <div>
                    <button type="submit">Post!</button>
                </div>            
            </form>
            </div>
        </div>
    </body>
</html>