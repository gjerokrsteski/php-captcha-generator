<?php

error_reporting(E_ALL | E_STRICT);

ini_set('display_errors', 1);

require_once '../CaptchaGenerator.php';

// Einfache Anwendung.
try
{
    $captcha = new CaptchaGenerator(
        dirname($_SERVER["SCRIPT_FILENAME"]).'/arial.ttf',
        dirname($_SERVER["SCRIPT_FILENAME"]).'/pwdimage.png',
        4
    );

    // Intervene here the phrase
    // and save it into the database or at the session.
    $_SESSION['security-phrase'] = $captcha->getPhrase();

    $captcha->render();
}
catch (InvalidArgumentException $e)
{
    print $e->getMessage();
}
