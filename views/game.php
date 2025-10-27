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
    <body class="flex-row" style="gap: 64px;">
        <div class="user flex-col">
            <!-- note from lily to lilli---variables are no longer available
                outside function scope, so they'll either be class variables,
                which will require $this-> to access, or they'll have to be
                defined in the function that includes this file. -->
            <h3>User: <?php echo $_SESSION["username"]; ?></h3>
            <h3>Email: <?php echo $_SESSION["email"]; ?></h3>
            <br>
            <h3>Current score: <?php echo $_SESSION["score"]; ?></h3>
            
            <br>
            <?php
                $played_games_result = pg_query_params($this->dbConnection, "SELECT * FROM hw3_games WHERE hw3_games.user_id = $1", [$_SESSION["user_id"]]);
                $played_games = count(pg_fetch_all($played_games_result));

                echo "<h3>Played games: $played_games</h3>";

                $won_games_result = pg_query_params($this->dbConnection, "SELECT * FROM hw3_games WHERE hw3_games.user_id = $1 AND hw3_games.won = TRUE", [$_SESSION["user_id"]]);
                $won_games = count(pg_fetch_all($won_games_result));

                $win_percent = 0;
                if ($won_games > 0) {
                    $win_percent = (int) (100 * $won_games / $played_games);
                }

                echo "<h3>Win rate: $win_percent%</h3>";

                $scores_result = pg_query_params($this->dbConnection, "SELECT score FROM hw3_games WHERE hw3_games.user_id = $1", [$_SESSION["user_id"]]);
                $scores = array_map(function($a) { return $a["score"]; }, pg_fetch_all($scores_result));

                $high_score = 0;
                $avg_score = 0;

                
                if (count($scores) > 0) {
                    $high_score = max($scores);
                    $avg_score = round(array_sum($scores) / count($scores));
                }

                echo "<h3>High score: $high_score</h3>";
                echo "<h3>Avg. score: $avg_score</h3>";
            ?>
        </div>

        <div class="flex-col" style="gap: 32px; flex-grow: 1; height: 100%; max-height: 100%;">
            <div class="flex-col" style="height: 50vh; width: 100%; align-items: start; justify-content: start;">
                <h3>Guessed words:</h3>
                <div class="guessed-words">
                    <?php
                        foreach ($_SESSION["guessedWords"] as $guess) {
                            echo "<span>$guess</span>\n";
                        }
                    ?>
                </div>
            </div>

            <div class="flex-col" style="width: 100%; justify-content: end;">
                <form class="shuffled-word flex-row" method="post">
                    <h1><?php echo $_SESSION["shuffledString"]; ?></h1>
                    <input type='hidden' name='command' value='shuffle'>
                    <button type="submit" name="shuffle">Shuffle</button>
                </form>

                <form class="flex-col" method="post" style="gap: 8px;">
                    <label  for="guess">Guess:</label>
                    <input type='hidden' name='command' value='guess'>
                    <div class="flex-row">
                        <input id='guess' type='text' name='guess' placeholder='Enter guess' style='font-size: 20px;' autofocus>
                        <button type="submit">Submit guess</button>
                    </div>
                </form>

                <form method="post">
                    <input type='hidden' name='command' value='quit'>
                    <button type="submit">Give up</button>
                </form>

                <span><?php echo $response; ?></span>
            </div>
        </div>
    </body>
</html>