<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectionTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testItLoads()
    {
        $this->visit('/')
             ->see('Projected Points');
    }
}
