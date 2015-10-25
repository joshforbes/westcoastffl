<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TeamTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testItLoads()
    {
        $this->visit('/team')
            ->see('Team Points');
    }
}
