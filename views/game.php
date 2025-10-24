<!DOCTYPE html lang='en'>
<html>
    <head>
        <title>AnagramsGame</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="UTF-8">
        <meta name="author" value="Lilli Hrncir">
        <meta name="author" value="Lily Wasko">
        <link rel="stylesheet" href="./main.css">
    </head>
    <body class="flex-col">
        <div class="user">
            <!-- note from lily to lilli---variables are no longer available
                 outside function scope, so they'll either be class variables,
                 which will require $this-> to access, or they'll have to be
                 defined in the function that includes this file. -->
            <h3><?php echo $_SESSION["username"]; ?></h3>
            <h3><?php echo $_SESSION["email"]; ?></h3>
            <h3><?php echo $_SESSION["score"]; ?></h3>
        </div>
        
        <div class="guessed-words">
            <p>
                <?php
                    foreach ($_SESSION["guessedWords"] as $guess) {
                        echo "$guess<br>\n";
                    }
                ?>
            </p>
        </div>
        <form class="shuffled-word" method="post">
            <h3><?php echo $_SESSION["shuffledString"]; ?></h3>
            <input type='hidden' name='command' value='shuffle'>
            <button type="submit" name="shuffle">Shuffle</button>
        </form>

        <form method="post">
            <label for="guess">Guess:</label>
            <input id='guess' type='text' name='guess' style='font-size:20px;' autofocus>
            <input type='hidden' name='command' value='guess'>
            <button type="submit">Submit guess</button>
        </form>
        <form method="post">
            <input type='hidden' name='command' value='quit'>
            <button type="submit">Give up</button>
        </form>
            
    </body>
</html>