<?php

namespace Tests;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use \Util;

class EncodingTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSimpleNames()
    {
        $this->assertEquals("Mengos", Util::limitCharacters("Μέγγος"));
        $this->assertEquals("Snijders", Util::limitCharacters("Snijders"));
        $this->assertEquals("Alejandra", Util::limitCharacters("Alejandra"));
        $this->assertEquals("Alvarez", Util::limitCharacters("Álvarez"));
        $this->assertEquals("Antones", Util::limitCharacters("Αντώνης"));
        $this->assertEquals("Katarzyna", Util::limitCharacters("Katarzyna"));
        $this->assertEquals("Jorge", Util::limitCharacters("Jorge"));
        $this->assertEquals("Laura", Util::limitCharacters("Laura"));
        $this->assertEquals("Dilara", Util::limitCharacters("Dilara"));
        $this->assertEquals("Hernandez", Util::limitCharacters("Hernández"));
        $this->assertEquals("Klanjcic", Util::limitCharacters("Klanjčić"));
        $this->assertEquals("Derk", Util::limitCharacters("Derk"));
        $this->assertEquals("Borkovskaa", Util::limitCharacters("Борковская"));
        $this->assertEquals("Marina", Util::limitCharacters("Marina"));
        $this->assertEquals("Zeynep", Util::limitCharacters("Zeynep"));
        $this->assertEquals("Perez", Util::limitCharacters("Pérez"));
        $this->assertEquals("Ustkanat", Util::limitCharacters("Üstkanat"));
        $this->assertEquals("Suzan", Util::limitCharacters("Suzan"));
        $this->assertEquals("Elina", Util::limitCharacters("Элина"));
        $this->assertEquals("Piot", Util::limitCharacters("Piot"));
        $this->assertEquals("Ucukoglu", Util::limitCharacters("Uçukoğlu"));
        $this->assertEquals("Perez-Abadin", Util::limitCharacters("Pérez-Abadín"));
        $this->assertEquals("Sanchez", Util::limitCharacters("Sánchez"));
        $this->assertEquals("Oguz", Util::limitCharacters("Oğuz"));
        $this->assertEquals("Kasia", Util::limitCharacters("Kasia"));
        $this->assertEquals("Tokac", Util::limitCharacters("Tokaç"));
        $this->assertEquals("Soko?owska", Util::limitCharacters("Sokołowska"));
    }
}
