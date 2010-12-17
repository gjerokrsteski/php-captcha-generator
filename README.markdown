Introduction
============

    Creates a captcha with the PHP's GD library for your web form. Is easy to use, just check it out.

Requirements & Installation
---------------------------

    If you have the GD library (available at » http://www.libgd.org/) you will also be able to
    create and manipulate images. The format of images you are able to manipulate depend on the
    version of GD you install, and any other libraries GD might need to access those image formats.
    Versions of GD older than gd-1.6 support GIF format images, and do not support PNG, where
    versions greater than gd-1.6 and less than gd-2.0.28 support PNG, not GIF.
    GIF support was re-enabled in gd-2.0.28.
    Read more: http://www.php.net/manual/en/image.requirements.php


How to use it?
--------------
1. Create a file captcha.php and make an insatnce of the CaptchaGenerator in it. You have to save the security-phrase for sample at the session. (see sample below) 
2. Integrate the capture-image into you form, using HTML like this 
    <pre>
        <img src="captcha.php" border="0" /> <input  type="text" name="captcha-confirmation" value="" />
    </pre>
3. After sending the form-data you can check if the value of the input-field "captcha-confirmation" equals with the security-phrase from the session. So that is all.


Sample 1 - Easy use
----------------------------------------------------------

    <?php
     try
     {
          $captcha = new CaptchaGenerator(
                           'path/to/your/font.ttf',
                           'path/to/your/background-image.gif',
                           $the_length_of_your_phrase = 4
          );

          // Intervene here the phrase
          // and save it into the database or at the session.
          $the_captcha_phrase = $captcha->getPhrase();

          $captcha->render();
      }
      catch (InvalidArgumentException $e)
      {
          print $e->getMessage();
     }
    ?>


Sample 2 - Customize some options
----------------------------------------------------------

    <?php
        // ...

        $captcha->setFontcolor(CaptchaGenerator::FONT_COLOR_BLUE)
                ->setFontsize(16)
                ->setPhraselength(5)
                ->setFontxmargin(55)
                ->setFontymargin(4)
                ->render();

        // ...
    ?>