<?php

namespace Fonsecas72\FailureExpoExtension\Expounds;

use Behat\Mink\Mink;

abstract class Expound
{
    /** @var Mink */
    public $mink;
    
    public $globaldestinationPath;

    public function __construct(Mink $mink, $options = [])
    {
        $this->mink = $mink;

        foreach ($options as $optionKey => $optionValue) {
            $this->{$optionKey} = $optionValue;
        }
    }

    abstract public function expose();
}
