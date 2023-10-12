<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class EmpruntTest extends TestCase
{


    /**
     * @test
     */
    public function empruntTest_DateRetourNull_EmpruntEncoursTrue()
    {
        $livre = new \App\Livre();
        $emprunt = new \App\Emprunt();
        $adherant = new \App\Adherant();

        $emprunt->setMedia($livre);
        $emprunt->setAdherant($adherant);
        $emprunt->setDateEmprunt(new \DateTime('09/06/2023'));
        $this->assertTrue($emprunt->empruntEnCours(), "L'emprunt est en cours");

    }

    /**
     * @test
     */
    public function empruntTest_DateRetourNotNull_EmpruntEncoursFalse()
    {
        $livre = new \App\Livre();
        $emprunt = new \App\Emprunt();
        $adherant = new \App\Adherant();

        $emprunt->setMedia($livre);
        $emprunt->setAdherant($adherant);
        $emprunt->setDateEmprunt(new \DateTime('09/06/2023'));
        $emprunt->setDateRetour(new \DateTime('09/10/2023'));
        $this->assertFalse($emprunt->empruntEnCours(), "L'emprunt n'est plus  en cours");

    }

    /**
     * @test
     */
    public function empruntTest_EmpruntPasRetournerAndDateRetourDepasse_EmpruntRetardTrue()
    {
        $livre = new \App\Livre();
        $emprunt = new \App\Emprunt();
        $adherant = new \App\Adherant();

        $emprunt->setMedia($livre);
        $emprunt->setAdherant($adherant);
        $emprunt->setDateEmprunt(new \DateTime('09/06/2023'));
        $emprunt->setDateRetourEstime(new \DateTime('09/06/2023'));
        $this->assertTrue($emprunt->empruntEnRetard(), "L'emprunt est en retard");
    }

    /**
     * @test
     */
    public function empruntTest_EmpruntPasRetournerAndDateRetourNoDepasse_EmpruntRetardFalse() {
        $livre = new \App\Livre();
        $emprunt = new \App\Emprunt();
        $adherant = new \App\Adherant();

        $emprunt->setMedia($livre);
        $emprunt->setAdherant($adherant);
        $emprunt->setDateEmprunt(new \DateTime('06/09/2023'));
        $emprunt->setDateRetourEstime(new \DateTime('12/10/2023'));
        $this->assertFalse($emprunt->empruntEnRetard(), "L'emprunt est en retard");
    }




}









