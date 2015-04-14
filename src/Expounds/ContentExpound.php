<?php

namespace Fonsecas72\FailureExpoExtension\Expounds;

use Behat\Mink\Mink;

class ContentExpound extends Expound
{
    public $id;

    public function __construct(Mink $mink, $options = [])
    {
        parent::__construct($mink, $options);
        $this->id = time();
    }

    public function expose()
    {
        $htmlResponseContent = $this->mink->getSession()->getPage()->getContent();
        $htmlResponseFilename = 'response_'.$this->id.'.html';

        $destination = '';
        file_put_contents(
            $destination.$htmlResponseFilename,
            $htmlResponseContent
        );
        echo PHP_EOL."| HtmlResponse captured ~> ".$htmlResponseFilename;
    }
}
