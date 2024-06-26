<?php
    session_start();
    if(isset($_COOKIE["session_id"]) && $_COOKIE["session_id"] == $_SESSION['session_id'])
    {
        header( "refresh:0;url=main.php" );
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
        <title>
            blazejm.easyisp.pl
        </title>

        <link rel="stylesheet" href="/Styles/MainPageStyle.css">
    </head>

    <header class="center">
        <a href="https://blazejm.easyisp.pl/index.php">
           <img src="/Assets/logo.png" alt="Logo">
        </a>
    </header>

    <body>
        <div class="post main-board center" style="width: 50%; margin: auto">
            <form action="login.php" method="post" class="login">
                <h1>Login</h1>
                <div>
                    <label for="username">Username:<br></label>
                    <input type="text" name="username" required>
                </div>
                <div>
                    <label for="password">Password:<br></label>
                    <input type="password" name="password" required>
                </div>
                <section>
                    <br>
                    <button type="submit">Login</button>
                    <a href="register.php">Register</a>
                </section>
            </form>
        </div>
    </body>
</html>
