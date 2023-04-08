<?php

namespace Explore\Test;

use Exception;

use PHPUnit\Framework\TestCase;

// Pattern class test

class EnsembleTest extends TestCase {

    private $ensembleTeste;

    //On réinitialise l'ensemble avant chaque test
    protected function setUp(): void
    {
        parent::setUp();
        $this->ensembleTeste = new Ensemble();
    }

    public function testVideDepart() {
        $this->assertEquals(0, $this->ensembleTeste->getTaille());
    }

    public function testAjout() {
        $this->assertFalse($this->ensembleTeste->contient(7));
        $this->ensembleTeste->ajouter(7);
        $this->assertTrue($this->ensembleTeste->contient(7));
        $this->assertEquals(1, $this->ensembleTeste->getTaille());
        //On n'ajoute pas deux fois dans un ensemble, donc la taille doit rester à 1
        $this->ensembleTeste->ajouter(7);
        $this->assertEquals(1, $this->ensembleTeste->getTaille());
    }

    public function testPop() {
        $this->ensembleTeste->ajouter(1);
        $this->ensembleTeste->ajouter(2);
        $this->ensembleTeste->ajouter(3);
        $this->assertEquals(3, $this->ensembleTeste->pop());
        $this->assertEquals(2, $this->ensembleTeste->pop());
        $this->assertEquals(1, $this->ensembleTeste->pop());
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("L'ensemble est vide!");
        $this->ensembleTeste->pop();
    }
}