<?php

namespace Fonsecas72\FailureExpoExtension;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Behat\Mink\Mink;
use Behat\Behat\EventDispatcher\Event\StepTested;
use Behat\Behat\EventDispatcher\Event\AfterStepTested;
use Behat\Testwork\Tester\Result\TestResult;
use Behat\Testwork\Tester\Result\ExceptionResult;

class FailureExpoListener implements EventSubscriberInterface
{
    private $id;
    private $mink;
    private $parameters;
    private $observers;

    /**
     * Initializes initializer.
     *
     * @param Mink  $mink
     * @param array $parameters
     */
    public function __construct(Mink $mink, array $parameters)
    {
        $this->id = time();
        $this->mink       = $mink;
        $this->parameters = $parameters;

        foreach($this->parameters['expounds'] as $potencialObserver) {
            $this->observers[] = $this->instantiateObserver($potencialObserver);
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            StepTested::AFTER => array('exposeFailInfo', -10)
        );
    }

    public function exposeFailInfo(AfterStepTested $event)
    {
        $testResult = $event->getTestResult();
        if (!$testResult instanceof ExceptionResult) {
            return;
        }
        if (!$this->isTestFailed($testResult)) {
            return;
        }

        foreach ($this->observers as $observer) {
            $observer->expose();
        }
    }
    private function isTestFailed(TestResult $testResult)
    {
        return $testResult->isPassed() === false && TestResult::FAILED === $testResult->getResultCode();
    }

    private function instantiateObserver($potencialObserver)
    {
        if (class_exists($class = $potencialObserver)) {
            $observer = new $class($this->mink);
            if ($observer instanceof Expounds\Expound) {
                return $observer;
            }
            throw new \Exception(sprintf(
                '`%s` expo observer class must be an instance of Expounds\Expound.',
                $potencialObserver
            ));
        }

        throw new \Exception(sprintf(
            '`%s` expo observer class could not be located.',
            $potencialObserver
        ));
    }
}
