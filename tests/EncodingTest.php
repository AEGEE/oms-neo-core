<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EncodingTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSimpleNames()
    {
        $this->assertEquals("Derk Snijders", Util::limitCharacters("Derk Snijders"));
        $this->assertEquals("Alejandra Piot Perez-Abadin", Util::limitCharacters("Alejandra Piot Pérez-Abadín"));
        $this->assertEquals("Laura Perez Alvarez", Util::limitCharacters("Laura Pérez Álvarez"));
        $this->assertEquals("Antones Mengos", Util::limitCharacters("Αντώνης Μέγγος"));
        $this->assertEquals("Jorge Sanchez Hernandez", Util::limitCharacters("Jorge Sánchez Hernández"));
        $this->assertEquals("Suzan Dilara Tokac", Util::limitCharacters("Suzan Dilara Tokaç"));
        $this->assertEquals("Marina Klanjcic", Util::limitCharacters("Marina Klanjčić"));
        $this->assertEquals("Zeynep Ustkanat", Util::limitCharacters("Zeynep Üstkanat"));
        $this->assertEquals("Oguz Ucukoglu", Util::limitCharacters("Oğuz Uçukoğlu"));
        $this->assertEquals("Katarzyna Kasia Sokolowska", Util::limitCharacters("Katarzyna Kasia Sokołowska"));
        $this->assertEquals("Elina Borkovskaa", Util::limitCharacters("Элина Борковская"));




    }

}
