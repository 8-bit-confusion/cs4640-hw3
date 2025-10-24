<?php

class AnagramsGameController {
    private $dbConnection;
    private $context;

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
        $_SESSION["guessedWords"] = [];
        $_SESSION["score"] = 0;
        $this->chooseShuffledString();
        include "./views/game.php";
    }

    public function processGuess() {
        $guess = $this->context["guess"];
        if (!$this->validLetters($guess, $_SESSION["shuffledString"]))
            echo "Guess has invalid letters.";
        elseif (!$this->validWord($guess))
            echo "Guess is not a word.";
        elseif (strlen($guess) < 7) {
            echo "Congratulations on finding a valid word!";
            $_SESSION["score"] += 10;
            $_SESSION["guessedWords"].array_push($guess);
        }
        else {
            include "./views/game-over.php";
        }
    }

    public function reshuffle() {
        $_SESSION["shuffledString"] = str_shuffle($_SESSION["shuffledString"]);
    }

    public function logout() {
        // save score & stats to db
        session_destroy();

        session_start();
        $this->welcome();
    }

    public function gameover() {
        include "./views/game-over.php";
    }

    // PRIVATE FUNCTIONS ###########################################################################################

    // Turns the string into a 'set' of chars to see
    private function validLetters($guess, $word) {
        $guess_chars = array_unique(str_split($guess));
        $word_chars = array_unique(str_split($word));

        sort($guess_chars);
        sort( $word_chars );

        return ($guess_chars == $word_chars);
    }

    // Before uploading to server, replace dict file with /var/www/html/homework/word_bank.json
    private function validWord($guess) {
        $dictFile = "./twl06.txt";
        $dict = file_get_contents($dictFile);
        
        $words = preg_split("/\R", $dict);
        return in_array($guess, $words);
    }


    // note from lily to lilli---removed return
    // value here since refactoring means we can just
    // set a class variable
    private function chooseShuffledString() {
        // Replace with "/var/www/html/homework/words7.txt"
        // when moving pages to server
        $dictFile = "./words7.txt";
        $dict = file_get_contents($dictFile);
        
        $words = preg_split("/\R", $dict);
        $_SESSION["shuffledString"] = str_shuffle($words[array_rand($words)]);
    }
}

?>