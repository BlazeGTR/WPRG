<?php
    session_start();
    echo($_COOKIE["session_id"]."\n".$_SESSION['session_id']);
    if($_COOKIE["session_id"] != $_SESSION['session_id'] || !isset($_COOKIE["session_id"]))
    {
        header( "refresh:2;url=index.html" );
        echo("Nie zalogowany!");
        die();
    }
    echo("Strona glowna");
    echo("cookie".empty($_COOKIE["session_id"]));
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

    <body>
        <div class="main-board">
            <div class="form">
                <a href="newPost.php" class="new-post">Nowe ogłoszenie</a>
            </div>
            <br>
            <?php 
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
                        echo('</section>');
                        echo ('<div class="post-title">');  
                            //Tworzymy klikalny tyuł z odnośnikiem do posta
                            $postLink = 'https://blazejm.easyisp.pl/post.php?id=';
                            $postLink .= $row[0];
                            echo("<a href=\"".$postLink."\" class=\"title\">");
                                echo($row[4]);
                            echo('</a>');
                        echo ('</div>');
                    echo ('</h1>');
                    //Post text
                    echo ('<div class="post-text">');
                        echo($row[2]);  //Główny text postu
                    echo ('</div>');
                echo ("</div>");
            }

                $query = "SELECT * FROM posts WHERE MasterPostID IS NULL";
                $result = mysqli_query($db_link,$query);
                $row = mysqli_fetch_row($result);
                //Główna tablica
                while ($row = mysqli_fetch_row($result))
                {
                    displayPost($row, $db_link);
                }
            ?>
        </div>
    </body>
</html>