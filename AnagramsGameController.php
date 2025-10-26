<?php

class AnagramsGameController {
    private $dbConnection;
    private $context;

    private $shortWords;
    private $sevenWords;

    public function __construct() {
        session_start();

        $host = DBConfig::$db["host"];
        $user = DBConfig::$db["user"];
        $database = DBConfig::$db["database"];
        $password = DBConfig::$db["pass"];
        $port = DBConfig::$db["port"];

        $this->dbConnection = pg_connect("host=$host port=$port dbname=$database user=$user password=$password");

        // get input from appropriate request context
        $this->context = match($_SERVER['REQUEST_METHOD']) {
            'GET' => $_GET,
            'POST' => $_POST,
        };

        $shortWordsFile = file_get_contents("word_bank.json");
        $this->shortWords = json_decode($shortWordsFile, true);

        $sevenWordsFile = file_get_contents("./words7.txt");
        $this->sevenWords = preg_split("/\R/", $sevenWordsFile);
    }

    public function run() {
        $command = 'welcome';
        if (isset($this->context['command']))
            $command = $this->context['command'];

        if (!isset($_SESSION["username"]) && $command != 'login') {
            $this->welcome();
            return;
        }
        
        match ($command) {
            'welcome' => $this->welcome(),
            'login' => $this->login(),
            'start-game' => $this->startGame(),
            'guess' => $this->processGuess(),
            'game-over' => $this->gameover(),
            'logout' => $this->logout(),
            'shuffle' => $this->reshuffle(),
            'quit' => $this->gameover(true),
        };
    }

    // COMMAND FUNCTIONS ###########################################################################################

    public function welcome($showMessage = false, $message = "") {
        include "./views/welcome.php";
    }

    public function login() {
        $username = $this->context["username"];
        $email = $this->context["email"];
        $password_hash = password_hash($this->context["password"], PASSWORD_BCRYPT);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->welcome(true, "Email was not valid. Please try again.");
        }
        
        $email_in_db = pg_query_params($this->dbConnection, "SELECT EXISTS (SELECT 1 FROM hw3_users WHERE hw3_users.email = $1)", [$email]);
        echo $email_in_db;
        if ($email_in_db == 1) {
            $db_hash = pg_query_params($this->dbConnection, "SELECT password_hash FROM hw3_users WHERE email = $1", [$email]); // get password where email is the current email
            
            if ($db_hash != $password_hash) {
                $this->welcome(true, "Password was incorrect. Please try again.");
                return;
            }
        } else {
            pg_query_params($this->dbConnection, "INSERT INTO hw3_users (name, email, password_hash) VALUES ($1, $2, $3)", [$username, $email, $password_hash]); // insert username, email, & password into table
        }

        $_SESSION["username"] = $username;
        $_SESSION["email"] = $email;
        $this->startGame();
    }

    public function startGame() {
        $_SESSION["guessedWords"] = array();
        $_SESSION["invalidGuesses"] = 0;
        $_SESSION["score"] = 0;
        $this->chooseShuffledString();
        include "./views/game.php";
    }

    public function processGuess() {
        $guess = $this->context["guess"];
        if (in_array(strtolower($guess), $_SESSION["guessedWords"])) {
            include "./views/game.php";
            echo "You already guessed this!";
            $_SESSION["invalidGuesses"] += 1;
        }
        elseif (!$this->validLetters($guess, $_SESSION["shuffledString"])) {
            include "./views/game.php";
            echo "Guess has invalid letters.";
            $_SESSION["invalidGuesses"] += 1;
        }
        elseif (!$this->validWord($guess)) {
            include "./views/game.php";
            echo "Guess is not a word.";
            $_SESSION["invalidGuesses"] += 1;
        }
        elseif (strlen($guess) < 7) {
            $points = match(strlen($guess)) {
                1 => 1,
                2 => 2,
                3 => 4,
                4 => 8,
                5 => 15,
                6 => 30,
            };

            $_SESSION["score"] += $points;
            array_push($_SESSION["guessedWords"], strtolower($guess));
            include "./views/game.php";
            echo "Congratulations on finding a valid word! +$points points";
        }
        else {
            $this->gameover(false);
        }
    }

    public function reshuffle() {
        $_SESSION["shuffledString"] = str_shuffle($_SESSION["shuffledString"]);
        include "./views/game.php";
    }

    public function logout() {
        session_destroy();

        session_start();
        $this->welcome();
    }

    public function gameover($quit) {
        // TODO: save score & stats to db
        include "./views/game-over.php";
    }

    // PRIVATE FUNCTIONS ###########################################################################################

    // Turns the string into a 'set' of chars to see
    private function validLetters($guess, $word) {
        $guess_chars = str_split(strtolower($guess));
        $word_chars = str_split(strtolower($word));

        sort($guess_chars);
        sort( $word_chars );

        foreach ($guess_chars as $char) {
            if (!in_array($char, $word_chars)) { return false; }
            array_shift($word_chars);
        }

        return true;
    }

    // Before uploading to server, replace dict file with /var/www/html/homework/word_bank.json
    private function validWord($guess) {
        if (strlen($guess) < 6) {
            $length_array = $this->shortWords[(string) strlen($guess)];
            return in_array(strtolower($guess), $length_array);
        }
        
        return in_array(strtolower($guess), $this->sevenWords);
    }


    // note from lily to lilli---removed return
    // value here since refactoring means we can just
    // set a class variable
    private function chooseShuffledString() {
        // Replace with "/var/www/html/homework/words7.txt"
        // when moving pages to server
        $_SESSION["targetWord"] = $this->sevenWords[array_rand($this->sevenWords)];
        $_SESSION["shuffledString"] = str_shuffle($_SESSION["targetWord"]);
    }
}

?>