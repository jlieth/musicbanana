<?php

namespace App\Tests;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class BaseDbTest extends KernelTestCase
{
    public function connection(): Connection
    {
        return $this->kernel->getContainer()->get("doctrine.dbal.default_connection");
    }
}
