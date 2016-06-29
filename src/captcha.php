<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

require_once 'CaptchaGenerator.php';

try {

    $captcha = new CaptchaGenerator(
        'arial.ttf',
        'pwdimage.png',
        4
    );

    // Intervene here the phrase
    // and save it into the database or at the session.
    $_SESSION['security-phrase'] = $captcha->getPhrase();

    $captcha->render();

} catch (InvalidArgumentException $e) {
    print $e->getMessage();
}
