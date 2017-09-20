<?php

namespace Tests;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use \Util;

class EncodingTest extends TestCase
{
    /**
     * Tests the transliterator function: Util::limitCharacters.
     * Being that this function can and will have different results between different systems,
     * This tests accepts a certain number of failures while still being able to succeed.
     *
     * @return void
     */
    public function testSimpleNames()
    {
        $accepted_failures = 1;

        $pairs = [
            "Mengos" => "Μέγγος",
            "Snijders" => "Snijders",
            "Alejandra" => "Alejandra",
            "Alvarez" => "Álvarez",
            "Antones" => "Αντώνης",
            "Katarzyna" => "Katarzyna",
            "Jorge" => "Jorge",
            "Laura" => "Laura",
            "Dilara" => "Dilara",
            "Hernandez" => "Hernández",
            "Klanjcic" => "Klanjčić",
            "Derk" => "Derk",
            "Borkovskaa" => "Борковская",
            "Marina" => "Marina",
            "Zeynep" => "Zeynep",
            "Perez" => "Pérez",
            "Ustkanat" => "Üstkanat",
            "Suzan" => "Suzan",
            "Elina" => "Элина",
            "Piot" => "Piot",
            "Ucukoglu" => "Uçukoğlu",
            "Perez-Abadin" => "Pérez-Abadín",
            "Sanchez" => "Sánchez",
            "Oguz" => "Oğuz",
            "Kasia" => "Kasia",
            "Tokac" => "Tokaç",
            "Sokolowska" => "Sokołowska",
        ];

        $keys = array_keys($pairs);

        for ($i = 0; $i < count($pairs); $i++) {
            $expected = $keys[$i];
            $actual = Util::limitCharacters($pairs[$expected]);
            if ($actual != $expected) {
                $accepted_failures--;
                error_log("ERROR: '$actual' does not equal expected '$expected'");
            }

            if ($accepted_failures < 0) {
                $this->fail("Max number of failures reached.");
            }
        }
        echo PHP_EOL . "Number of failures was acceptable";
        return true;
    }
}
