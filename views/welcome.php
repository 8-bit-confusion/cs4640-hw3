<!DOCTYPE html lang="en">
<html>
    <head>
        <title>Login | AnagramsGame</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="utf-8">
        <link rel="stylesheet" href="./main.css">
    </head>
    <body class="flex-row" style="width: 100vw; height: 100vh; margin: 0;">
        <form class="flex-col" style="width: 20%; align-items: stretch;" method="POST">
            <span>Login to AnagramsGame</span>
            <input type="hidden" name="command" value="login">
            <input name="username" placeholder="Enter username" required>
            <input name="email" placeholder="Enter email address" required>
            <input name="password" placeholder="Enter password" required>
            <span id="pwd-feedback"><?php if($showMessage) echo $message ?></span>
            <button type="submit" style="align-self: end;">Login</button>
        </form>
    </body>
</html>