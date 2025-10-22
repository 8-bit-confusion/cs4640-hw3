<?php

class AnagramsGameController {
    private $db;
    private $context;

    private $username;
    private $email;
    private $password;

    private $shuffledString;

    public function __construct() {
        session_start();

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
        
        match ($command) {
            'welcome' => $this->welcome(),
            'login' => $this->login(),
            'start-game' => $this->startGame(),
            'guess' => $this->proccessGuess(),
            'game-over' => $this->gameover(),
        };
    }

    // COMMAND FUNCTIONS ###########################################################################################

    public function welcome($passwordIncorrect = false) {
        include "./welcome.html";
        if ($passwordIncorrect) {
            echo '<script>document.getElementById("password-incorrect").style.display = "block";</script>';
        }
    }

    public function login() {
        $this->username = $this->context['username'];
        $this->email = $this->context['email'];
        $this->password = $this->context['password'];
        
        // handle login

        // if (password is wrong)
        $this->welcome(true);
    }

    public function startGame() {
        include "./game.html";
    }

    public function processGuess() {
        $guess = $this->context["guess"];
        if (strlen($guess) != 7) {
            // Change echos later
            echo "Guess is invalid";
        }
        if (!$this->validLetters($guess, $shuffledString))
            echo "Guess is invalid";
        else {
            include "./game-over.html";
        }
    }

    public function gameover() {
        include "./game-over.html";
    }

    // PRIVATE FUNCTIONS ###########################################################################################

    private function validLetters($guess, $word) {
        $guess_chars = str_split($guess);
        $word_chars = str_split($word);

        sort($guess_chars);
        sort( $word_chars );

        return ($guess_chars == $word_chars);
    }

    private function validWord($guess) {
        $dictFile = "./words7.txt";
        $dict = file_get_contents($dictFile);
        
        $words = preg_split("/\R", $dict);
        
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
        $this->shuffledString = str_shuffle($words[array_rand($words)]);
    }
}

?>