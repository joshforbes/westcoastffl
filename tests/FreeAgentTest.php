<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FreeAgentTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testItLoads()
    {
        $this->visit('/free-agent')
            ->see('Free Agents');
    }
}
