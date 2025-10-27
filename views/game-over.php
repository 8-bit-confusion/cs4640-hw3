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
        <h1>Statistics:</h1>
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