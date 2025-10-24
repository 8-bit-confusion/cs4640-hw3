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
        <?php
            if (!$quit) {
                echo "<h2>You Found the 7 Letter Word!</h2>\n<p>Congratulations</p>";
            }
        ?>
        <div id="button-options" class="flex-row">
            <a id='restart-button' href="./?command=start-game">Play Again</a>
            <a id='giveup-button' href="./?command=logout">Log Out</a>
        </div>
    </body>
</html>