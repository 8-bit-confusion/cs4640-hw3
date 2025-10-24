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
            else {
                echo "<h1>GAME OVER</h1>";
            }
        ?>
        <div id="button-options" class="flex-row">
            <a id='restart-button' href="./?command=start-game">
                <img class="game-icon" src="images/play.png" style="height:10px; width:10px;">    
                Play Again
            </a>
            <a id='giveup-button' href="./?command=logout">
                <img class="game-icon"src="images/play.png" style="height:10px; width:10px;">    
                Log Out
            </a>
        </div>
    </body>
</html>