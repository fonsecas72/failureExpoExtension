<?php

namespace Fonsecas72\FailureExpoExtension\Expounds;

use Behat\Mink\Mink;

class ScreenshotExpound extends Expound
{
    public $id;

    public function __construct(Mink $mink, $options = [])
    {
        parent::__construct($mink, $options);
        $this->id = time();
    }

    public function expose()
    {
        $screenshotContent = $this->mink->getSession()->getScreenshot();
        $screenshotFilename = 'screenshot_'.$this->id.'.png';
        $destination = '';
        file_put_contents(
            $destination.$screenshotFilename,
            $screenshotContent
        );
        echo PHP_EOL."| Screenshot captured ~> ".$screenshotFilename;
    }
}
