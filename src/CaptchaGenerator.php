<?php

/**
 * CaptchaGenerator
 *
 * @category   CaptchaGenerator
 * @license    http://krsteski.de/new-bsd-license New BSD License
 * @author     Gjero Krsteski <gjero@krsteski.de>
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
    const FONT_COLOR_BLUE = 'blue';

    /**
     * The background-image.
     *
     * @var string
     */
    protected $image = '';

    /**
     * The font-file.
     *
     * @var string
     */
    protected $font = '';

    /**
     * The length of the secure-phrase.
     *
     * @var integer
     */
    protected $phraseLength = 0;

    /**
     * The secure-phrase.
     *
     * @var string
     */
    protected $phrase = '';

    /**
     * The font-size.
     *
     * @var integer
     */
    protected $fontSize = 17;

    /**
     * The font-x-margin to the background-image.
     *
     * @var integer
     */
    protected $fontXmargin = 55;

    /**
     * The font-y-margin to the background-image.
     *
     * @var integer
     */
    protected $fontYmargin = 4;

    /**
     * The font-color.
     *
     * @var string
     */
    protected $fontColor = self::FONT_COLOR_BLUE;

    /**
     * @param     $font
     * @param     $image
     * @param int $phraseLength
     *
     * @throws RuntimeException
     */
    public function __construct($font, $image, $phraseLength = 4)
    {
        if (!extension_loaded('gd')) {
            throw new RuntimeException(
                'The GD extension is required, but the extension is not loaded'
            );
        }

        $this->setFont($font);
        $this->setImage($image);
        $this->setPhraseLength($phraseLength);

        $this->phrase = $this->getRandomPhrase();
    } 

    /**
     * Sets the background-image.
     *
     * @param $image
     *
     * @return CaptchaGenerator
     * @throws InvalidArgumentException
     */
    public function setImage($image)
    {
        if (false === file_exists($image)) {
            throw new InvalidArgumentException('The background-image do not exists!');
        }

        $this->image = $image;

        return $this;
    } 

    /**
     * Sets the font-file.
     *
     * @param $font
     *
     * @return CaptchaGenerator
     * @throws InvalidArgumentException
     */
    public function setFont($font)
    {
        if (false === file_exists($font)) {
            throw new InvalidArgumentException('The font do not exists!' . $font);
        }

        $this->font = $font;

        return $this;
    } 

    /**
     * @param $phraseLength
     *
     * @return CaptchaGenerator
     * @throws InvalidArgumentException
     */
    public function setPhraseLength($phraseLength)
    {
        if (false === is_int($phraseLength) || $phraseLength < 4) {
            throw new InvalidArgumentException('The phrase-length must be an integer and bigger than 3!');
        }

        $this->phraseLength = $phraseLength;

        return $this;
    } 

    /**
     * Sets the font-x-margin to the background-image.
     *
     * @param $fontXmargin
     *
     * @return CaptchaGenerator
     * @throws InvalidArgumentException
     */
    public function setFontXmargin($fontXmargin)
    {
        if (false === is_int($fontXmargin)) {
            throw new InvalidArgumentException('The font-x-margin must be an integer!');
        }

        $this->fontXmargin = $fontXmargin;

        return $this;
    } 

    /**
     * Sets the font-y-margin to the background-image.
     *
     * @param $fontYmargin
     *
     * @return CaptchaGenerator
     * @throws InvalidArgumentException
     */
    public function setFontYmargin($fontYmargin)
    {
        if (false === is_int($fontYmargin)) {
            throw new InvalidArgumentException('The font-y-margin must be an integer!');
        }

        $this->fontYmargin = $fontYmargin;

        return $this;
    } 

    /**
     * @param $fontSize
     *
     * @return CaptchaGenerator
     * @throws InvalidArgumentException
     */
    public function setFontSize($fontSize)
    {
        if (false === is_int($fontSize)) {
            throw new InvalidArgumentException('The font-size must be an integer!');
        }

        $this->fontSize = $fontSize;

        return $this;
    } 

    /**
     * @param $fontColor
     *
     * @return CaptchaGenerator
     */
    public function setFontColor($fontColor)
    {
        $this->fontColor = $fontColor;

        return $this;
    } 

    /**
     * Returns the phrase.
     *
     * @return string
     */
    public function getPhrase()
    {
        return $this->phrase;
    } 

    /**
     * Sets the phrase.
     *
     * @param string $phrase
     *
     * @return CaptchaGenerator
     */
    public function setPhrase($phrase)
    {
        $this->phrase = $phrase;

        return $this;
    }

    /**
     * Retrieves an random-phrase.
     *
     * @return string
     */
    public function getRandomPhrase()
    {
        $string = '27893456qwertzupasdfghjkyxcvbnm';

        $cout = $pos = '';

        for ($i = 1; $i <= $this->phraseLength; $i++) {
            $pos = rand(0, mb_strlen($string) - 1);
            $cout .= $string{$pos};
        }

        return $cout;
    } 

    /**
     * Generates the random-phrase with the
     * background-image and sends it as header-output.
     *
     * @return void
     */
    public function render()
    {
        $srcId = 0;
        $imgMeta = getimagesize($this->image);

        switch ($imgMeta['mime']) {
            case 'image/png':
                $srcId = imagecreatefrompng($this->image);
                break;

            case 'image/jpeg':
                $srcId = imagecreatefromjpeg($this->image);
                break;

            case 'image/gif':
                $oldid = imagecreatefromgif($this->image);
                $srcId = imagecreatetruecolor($imgMeta[0], $imgMeta[1]);
                imagecopy($srcId, $oldid, 0, 0, 0, 0, $imgMeta[0], $imgMeta[1]);
                break;
            default:
                break;
        }

        switch ($this->fontColor) {
            case self::FONT_COLOR_BLACK:
                $fontColor = imagecolorallocate($srcId, 0, 0, 0);
                break;

            case self::FONT_COLOR_WHITE:
                $fontColor = imagecolorallocate($srcId, 255, 255, 255);
                break;

            case self::FONT_COLOR_BLUE:
                $fontColor = imagecolorallocate($srcId, 0, 76, 134);
                break;

            case self::FONT_COLOR_BLUE:
            default:
                $fontColor = imagecolorallocate($srcId, 0, 76, 134);
                break;
        }

        $xSize = imagesx($srcId);
        $ySize = imagesy($srcId);

        imagettftext(
            $srcId,
            $this->fontSize,
            0,
            $xSize - $this->fontXmargin,
            $ySize - $this->fontYmargin,
            $fontColor,
            $this->font,
            $this->phrase
        );

        switch ($imgMeta['mime']) {
            case 'image/png':
                header("Content-type: image/png");
                imagepng($srcId);
                break;

            case 'image/jpeg':
                header("Content-type: image/jpeg");
                imagejpeg($srcId);
                break;

            case 'image/gif':
                header("Content-type: image/gif");
                imagegif($srcId);
                break;

            default:
                break;
        }

        imagedestroy($srcId);
    } 
}