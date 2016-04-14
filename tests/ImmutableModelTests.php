<?php

use Eloquent\Attributes\SquirrelCache;

class ImmutableModelTests extends PHPUnit_Framework_TestCase
{
    public function testSettings()
    {
        $user = new DefaultsTestUser();
    }
}