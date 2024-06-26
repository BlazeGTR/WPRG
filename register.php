<html>
<head>
	<title>
		blazejm.easyisp.pl
	</title>
    <meta charset="utf-8">
	<link rel="stylesheet" href="/Styles/MainPageStyle.css">
</head>
<body>
    <div class="post main-board center" style="width: 50%; margin: auto">
        <script src="https://www.google.com/recaptcha/api.js"async defer></script>
        <script src="/Scripts/CaptchaCheck.js"async defer></script>
            <div class="form">
                <form action="registerConfirm.php" method="post" class="login" onsubmit="return check_captcha_filled()">
                    <h1>Register</h1>
                    <div>
                        <label for="username">Username:<br></label>
                        <input type="text" name="username" required pattern="[A-Za-z0-9]{3,20}" title="3 - 20 characters, latin characters and numbers only">
                    </div>
                            <div>
                        <label for="email">E-mail:<br></label>
                        <input type="email" name="email">
                    </div>
                    <div>
                        <label for="password">Password:<br></label>
                        <input type="password" name="password" required pattern="[A-Za-z0-9]{5,20}" title="5 - 20 characters, latin characters and numbers only">
                    </div>
                    <section>
                        <br>
                        <div class="g-recaptcha" data-callback="captcha_filled" data-expired-callback="captcha_expired" data-sitekey="6LehvP4pAAAAAFvgMlzK2DT4SLs2p4bOeaJ3F6dw" style="width: 304px; display: block; margin-left: auto; margin-right: auto;"></div>
                        <button type="submit">Register</button>
                    </section>
                </form>
            </div>
        </body>
    </div>
</html>