<?php

$context = NULL;
switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $context = $_POST;
        break;
    
    case 'GET':
        $context = $_GET;
        break;
}

$command = $context['command'];
switch ($command) {
    case 'welcome':
    default:
        include "./welcome.html";
        break;

    case 'login':
        $username = $context['username'];
        $email = $context['email'];
        $password = $context['password'];
        
        // handle login
        // if (password is wrong)
        include "./welcome.html";
        echo '<script>document.getElementById("password-incorrect").style.display = "block";</script>';
        break;
    
    case 'game':
        include "./game.html";
        break;

    case 'guess':
        $guess = $context["guess"];
        if (strlen($guess) != 7) {
            // Change echos later
            echo "Guess is invalid";
        }
        //elseif(strlen($guess) == 7 && )
        
        break;

    case 'game-over':
        include "./game-over.html";
        break;
}

function validLetters($guess, $word){
    $guess_chars = str_split($guess);
    $word_chars = str_split($word);

    sort($guess_chars);
    sort( $word_chars );

    return ($guess_chars == $word_chars);
}

function validWord($guess) {
    
}

function chooseWord(): String{
    // Replace with "/var/www/html/homework/words7.txt"
    // when moving pages to server
    $dictFile = "web/www/hw3/words7.txt";
    $dict = file_get_contents($dictFile);
    
    $words = preg_split("/\R", $dict);
    return $words[array_rand($words)];
}
?>