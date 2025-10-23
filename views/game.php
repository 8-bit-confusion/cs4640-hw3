<?php
$name = "John Doe";
$email = "@gmail.com";
?>

<!DOCTYPE html lang='en'>
<html>
    <head>
        <title>AnagramsGame</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="UTF-8">
        <meta name="author" value="Lilli Hrncir">
        <link rel="stylesheet" href="./main.css">
    </head>
    <body class="flex-col">
        <div class="user">
            <!-- note from lily to lilli---variables are no longer available
                 outside function scope, so they'll either be class variables,
                 which will require $this-> to access, or they'll have to be
                 defined in the function that includes this file. -->
            <h3><?php echo $this->username; ?></h3>
            <h3><?php echo $email; ?></h3>
        </div>
        <div class="guessed-words">
            
            <p>You have <?php $guess_num; ?> left</p>

        </div>
        <form method="post" action="index.php">
            <label for="guess">Guess:</label>
            <input id='guess' type='text' name='guess' style='font-size:20px;'>
            <input type='hidden' name='command' value='guess'>
            <button type="submit">
        </form>
    </body>
</html>