<?php

// Lilli Hrncir site: https://cs4640.cs.virginia.edu/rrt9da/hw3/
// Lily Wasko site: https://cs4640.cs.virginia.edu/nep8zt/hw3/
// Natalie Nguyen site: https://cs4640.cs.virginia.edu/gzg8pf/hw3/

// DEBUG: print errors
// (from trivia example)
error_reporting(E_ALL);
ini_set("display_errors", 1);

// autoload classes from src/
// OUR AnagramsGameController.php FILE WILL BE DEPLOYED IN
// OPT/SRC, NOT IN A PUBLIC FOLDER
// (from trivia example)
spl_autoload_register(function ($classname) {
    include "$classname.php";
});

// instantiate and run controller
// (from trivia example)
$controller = new AnagramsGameController();
$controller->run();

?>