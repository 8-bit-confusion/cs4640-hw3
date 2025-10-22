<!DOCTYPE html lang="en">
<html>
    <head>
        <link rel="stylesheet" href="./main.css">
    </head>
    <body class="flex-row" style="width: 100vw; height: 100vh; margin: 0;">
        <form class="flex-col" style="width: 20%; align-items: stretch;" method="POST">
            <span>Login to AnagramGame</span>
            <input type="hidden" name="command" value="login">
            <input name="username" placeholder="Enter username" required>
            <input name="email" placeholder="Enter email address" required>
            <input name="password" placeholder="Enter password" required>
            <span id="pwd-feedback"><?php if($passwordIncorrect) echo 'Password was incorrect. Please try again.' ?></span>
            <button type="submit" style="align-self: end;">Login</button>
        </form>
    </body>
</html>