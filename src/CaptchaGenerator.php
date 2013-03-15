<?php
/**
 * CaptchaGenerator
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * http://krsteski.de/new-bsd-license/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to gjero@krsteski.de so we can send you a copy immediately.
 *
 * @category CaptchaGenerator
 * @copyright Copyright (c) 2010-2011 Gjero Krsteski (http://krsteski.de)
 * @license http://krsteski.de/new-bsd-license New BSD License
 */

/**
 * CaptchaGenerator
 *
 * @category   CaptchaGenerator
 * @copyright   Copyright (c) 2010-2011 Gjero Krsteski (http://krsteski.de)
 * @license   http://krsteski.de/new-bsd-license New BSD License
 * @author    Gjero Krsteski <gjero@krsteski.de>
 *
 * <code>
 * try
 * {
 *     $captcha = new CaptchaGenerator(
 *                      'path/to/your/font.ttf',
 *                      'path/to/your/background-image.gif',
 *                      $the_length_of_your_phrase = 4
 *     );
 *
 *     // Intervene here the phrase
 *     // and save it into the database or at the session.
 *     $the_captcha_phrase = $captcha->getPhrase();
 *
 *     $captcha->render();
 * }
 * catch (InvalidArgumentException $e)
 * {
 *     print $e->getMessage();
 * }
 * </code>
 *
 */
class CaptchaGenerator
{
  const FONT_COLOR_BLACK = 'black';
  const FONT_COLOR_WHITE = 'white';
  const FONT_COLOR_BLUE  = 'blue';

  /**
   * The background-image.
   * @var string
   */
  protected $_image = '';

  /**
   * The font-file.
   * @var string
   */
  protected $_font = '';

  /**
   * The length of the secure-phrase.
   * @var integer
   */
  protected $_phraselength = 0;

  /**
   * The secure-phrase.
   * @var string
   */
  protected $_phrase = '';

  /**
   * The font-size.
   * @var integer
   */
  protected $_fontsize = 17;

  /**
   * The font-x-margin to the background-image.
   * @var integer
   */
  protected $_fontxmargin = 55;

  /**
   * The font-y-margin to the background-image.
   * @var integer
   */
  protected $_fontymargin = 4;

  /**
   * The font-color.
   * @var string
   */
  protected $_fontcolor = self::FONT_COLOR_BLUE;

  /**
   * @param $font
   * @param $image
   * @param int $phraselength
   * @throws RuntimeException
   */
  public function __construct($font, $image, $phraselength = 4)
  {
    if (!extension_loaded('gd')) {
      throw new RuntimeException(
        'The GD extension is required, but the extension is not loaded'
      );
    }

    $this->setFont($font);
    $this->setImage($image);
    $this->setPhraselength($phraselength);

    $this->_phrase = $this->getRandomPhrase();
  } // function

  /**
   * Sets the background-image.
   * @param $image
   * @return CaptchaGenerator
   * @throws InvalidArgumentException
   */
  public function setImage($image)
  {
    if (false === file_exists($image)) {
      throw new InvalidArgumentException('The background-image do not exists!');
    }

    $this->_image = $image;
    return $this;
  } // function

  /**
   * Sets the font-file.
   * @param $font
   * @return CaptchaGenerator
   * @throws InvalidArgumentException
   */
  public function setFont($font)
  {
    if (false === file_exists($font)) {
      throw new InvalidArgumentException('The font do not exists!' . $font);
    }

    $this->_font = $font;
    return $this;
  } // function

  /**
   * @param $phraselength
   * @return CaptchaGenerator
   * @throws InvalidArgumentException
   */
  public function setPhraselength($phraselength)
  {
    if (false === is_int($phraselength) || $phraselength < 4) {
      throw new InvalidArgumentException('The phrase-length must be an integer and bigger than 3!');
    }

    $this->_phraselength = $phraselength;
    return $this;
  } // function

  /**
   * Sets the font-x-margin to the background-image.
   * @param $fontxmargin
   * @return CaptchaGenerator
   * @throws InvalidArgumentException
   */
  public function setFontxmargin($fontxmargin)
  {
    if (false === is_int($fontxmargin)) {
      throw new InvalidArgumentException('The font-x-margin must be an integer!');
    }

    $this->_fontxmargin = $fontxmargin;
    return $this;
  } // function

  /**
   * Sets the font-y-margin to the background-image.
   * @param $fontymargin
   * @return CaptchaGenerator
   * @throws InvalidArgumentException
   */
  public function setFontymargin($fontymargin)
  {
    if (false === is_int($fontymargin)) {
      throw new InvalidArgumentException('The font-y-margin must be an integer!');
    }

    $this->_fontymargin = $fontymargin;
    return $this;
  } // function

  /**
   * @param $fontsize
   * @return CaptchaGenerator
   * @throws InvalidArgumentException
   */
  public function setFontsize($fontsize)
  {
    if (false === is_int($fontsize)) {
      throw new InvalidArgumentException('The font-size must be an integer!');
    }

    $this->_fontsize = $fontsize;
    return $this;
  } // function

  /**
   * @param $fontcolor
   * @return CaptchaGenerator
   */
  public function setFontcolor($fontcolor)
  {
    $this->_fontcolor = $fontcolor;
    return $this;
  } // function

  /**
   * Returns the phrase.
   * @return string
   */
  public function getPhrase()
  {
    return $this->_phrase;
  } // function

  /**
   * Sets the phrase.
   * @param string $phrase
   * @return CaptchaGenerator
   */
  public function setPhrase($phrase)
  {
    $this->_phrase = $phrase;
    return $this;
  }

  /**
   * Retrieves an random-phrase.
   * @return string
   */
  public function getRandomphrase()
  {
    $string = '27893456qwertzupasdfghjkyxcvbnm';

    $cout = $pos = '';

    for ($i = 1; $i <= $this->_phraselength; $i++) {
      $pos = rand(0, mb_strlen($string) - 1);
      $cout .= $string{$pos};
    }

    return $cout;
  } // function

  /**
   * Generates the random-phrase with the
   * background-image and sends it as header-output.
   * @return void
   */
  public function render()
  {
    $srcid = 0;
    $aimg  = getimagesize($this->_image);

    switch ($aimg['mime']) {
      case 'image/png':
        $srcid = imagecreatefrompng($this->_image);
        break;

      case 'image/jpeg':
        $srcid = imagecreatefromjpeg($this->_image);
        break;

      case 'image/gif':
        $oldid = imagecreatefromgif($this->_image);
        $srcid = imagecreatetruecolor($aimg[0], $aimg[1]);
        imagecopy($srcid, $oldid, 0, 0, 0, 0, $aimg[0], $aimg[1]);
        break;
      default:
        break;
    }

    switch ($this->_fontcolor) {
      case self::FONT_COLOR_BLACK:
        $fontcolor = imagecolorallocate($srcid, 0, 0, 0);
        break;

      case self::FONT_COLOR_WHITE:
        $fontcolor = imagecolorallocate($srcid, 255, 255, 255);
        break;

      case self::FONT_COLOR_BLUE:
        $fontcolor = imagecolorallocate($srcid, 0, 76, 134);
        break;

      default:
      case self::FONT_COLOR_BLUE:
        $fontcolor = imagecolorallocate($srcid, 0, 76, 134);
        break;
    }

    $xsize = imagesx($srcid);
    $ysize = imagesy($srcid);

    imagettftext(
      $srcid,
      $this->_fontsize,
      0,
      $xsize - $this->_fontxmargin,
      $ysize - $this->_fontymargin,
      $fontcolor,
      $this->_font,
      $this->_phrase
    );

    switch ($aimg['mime']) {
      case 'image/png':
        header("Content-type: image/png");
        imagepng($srcid);
        break;

      case 'image/jpeg':
        header("Content-type: image/jpeg");
        imagejpeg($srcid);
        break;

      case 'image/gif':
        header("Content-type: image/gif");
        imagegif($srcid);
        break;

      default:
        break;
    }

    imagedestroy($srcid);
  } // function
} // class