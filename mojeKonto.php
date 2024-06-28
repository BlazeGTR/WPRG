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
    if(!isset($_GET["id"]))
    {
        $_GET["id"] =$_SESSION["user_id"];
    }
    //znajdujemy usera którego to konto
    $query = "SELECT * FROM uzytkownicy WHERE id=";
    $query .= $_GET["id"];
    $result = mysqli_query($db_link,$query);
    $row = mysqli_fetch_row($result);
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
                <?php
                if($_GET["id"] == $_SESSION["user_id"])
                {
                    echo('
                    <form action="editKonto.php" method="post">
                    <br>
                        <button type="submit">Edytuj Konto</button>
                    </form>
                    ');
                }
                ?>
                <h1 class="post-header" style="font-size: 150%">
                    <section style="display: flex">
                        <div style="align-self: center">
                            <?php
                                $pfpPath = './Assets/ProfilePics/pfp_'.$row[0].".png";
                                if(!file_exists($pfpPath)) $pfpPath = './Assets/ProfilePics/pfp_default.png';
                                echo('<img src="'.$pfpPath.'" alt="pfp" width="64" height="64" class="profile-pic">');
                            ?>
                        </div>
                        <div style="align-self: center; font-size: 150%; padding-left: 15px">
                            <?php
                                echo($row[1]);
                            ?>
                        </div>
                        <div class="date-time" style="text-align: right; margin-left: auto;">
                            <?php
                                echo("Data utworzenia:<br>");
                                echo(substr($row[5], 0, 10));
                            ?>
                        </div>
                    </section>
                </h1>
            </div>
            <h2>
                <div class="center" style="color: white;">
                    Historia postów:
                </div>
            </h2>
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
                            echo(mysqli_fetch_row($results)[1]);    //autor
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
                //Pokazujemy 5 postów na stronę
                if(!isset($_GET["page"])) $_GET["page"] = 1;
                $query = "SELECT * FROM posts WHERE MasterPostID IS NULL AND AuthorID=";
                $query .= $_GET["id"];
                $query .= " ORDER BY `PostID` DESC LIMIT 5 OFFSET ";
                $query .= ($_GET["page"]-1)*5;
                $result = mysqli_query($db_link,$query);
                //Główna tablica
                while ($row = mysqli_fetch_row($result))
                {
                    displayPost($row, $db_link);
                }
            ?>
        </div>
    <section class="center">
         <div class="bottomnav">
                <!-- lewo -->
                <div style="flex-basis: 160px">
                    <?php
                        if(!empty($_GET["page"]))
                        {
                            if($_GET["page"] > 1)
                            {
                                echo('<a href="mojeKonto.php?page='.($_GET["page"]-1).'"> &larr; </a>');
                            }  
                            else
                            {
                                echo('<a> &larr; </a>');
                            }
                            for($i = 0; $i <= 2; $i++)
                            {
                                echo('<a href="mojeKonto.php?page='.($_GET["page"]-3+$i).'"');
                                if($_GET["page"]-3+$i < 1)
                                {
                                    echo(' style="visibility: hidden">');
                                }
                                else
                                {
                                    echo('>');
                                }
                                echo(($_GET["page"]-3+$i).'</a>');
                            }
                        }
                    ?>
                </div>

                <!-- obecna strona -->
                <div>
                    <?php
                        echo('<a href="" class="active" >'.$_GET["page"].'</a>');
                    ?>
                </div>

                <!-- prawo -->
                <div style="flex-basis: 160px">
                    <?php
                        $query = "SELECT * FROM posts WHERE MasterPostID IS NULL AND AuthorID=";
                        $query .= $_GET["id"];
                        $result = mysqli_query($db_link, $query);
                        $rows = mysqli_num_rows($result);   //ile postów
                        //$rows = 11;
                        $pages = ceil((($rows)/5));                   //ile stron
                        for($i = 1; $i <= 3; $i++)
                        {
                            echo('<a href="mojeKonto.php?page='.($_GET["page"]+$i).'"');
                                if( ($_GET["page"] + $i) > $pages)
                                {
                                    echo('  style="visibility: hidden">');
                                }
                                else
                                {
                                    echo('>');
                                }
                                echo(($_GET["page"]+$i).'</a>');
                        }
                        if($_GET["page"] < $pages)
                        {
                            echo('<a href="mojeKonto.php?page='.($_GET["page"]+1).'"> &rarr; </a>');
                        }
                        else
                        {
                            echo('<a> &rarr; </a>');
                        }
                    ?>
                </div>
        </div>
    </section>
        </div>
    </body>
</html>