<?php

namespace Tests\Detecmedia;

use Behat\Mink\Driver\DriverInterface;
use Behat\Mink\Driver\GoutteDriver;
use Behat\Mink\Session;
use PHPUnit\Framework\TestCase;

/**
 * Class MinkTestCase
 * @package Integration
 * @version $id$
 */
class MinkTestCase extends TestCase
{
    /**
     * @var DriverInterface
     */
    protected $driver;

    /**
     * @var Session
     */
    protected $session;

    protected function setUp()
    {
        $this->driver = new GoutteDriver();
        $this->session = new Session($this->driver);
        $this->session->start();
    }

    protected function tearDown()
    {
        $this->session->stop();
    }
}