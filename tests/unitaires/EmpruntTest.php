<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class EmpruntTest extends TestCase
{


    /**
     * @test
     */
    public function empruntEnCours_DateRetourNonRenseigne_Vrai()
    {
        $livre = new \App\Entity\Livre();
        $emprunt = new \App\Entity\Emprunt();
        $adherent = new \App\Entity\Adherent();

        $emprunt->setMedia($livre);
        $emprunt->setAdherent($adherent);
        $emprunt->setDateEmprunt(new \DateTime('09/06/2023'));

        $this->assertTrue($emprunt->empruntEnCours());

    }

    /**
     * @test
     */
    public function empruntEnCours_DateRetourNonRenseigne_Faux()
    {
        $livre = new \App\Entity\Livre();
        $emprunt = new \App\Entity\Emprunt();
        $adherent = new \App\Entity\Adherent();

        $emprunt->setMedia($livre);
        $emprunt->setAdherent($adherent);
        $emprunt->setDateEmprunt(new \DateTime('09/06/2023'));
        $emprunt->setDateRetour(new \DateTime('09/15/2023'));

        $this->assertFalse($emprunt->empruntEnCours());

    }

    /**
     * @test
     */
    public function empruntEnRetard_EmpruntEnCoursEtDateRetourDepasse_Vrai()
    {
        $livre = new \App\Entity\Livre();
        $emprunt = new \App\Entity\Emprunt();
        $adherent = new \App\Entity\Adherent();

        $emprunt->setMedia($livre);
        $emprunt->setAdherent($adherent);
        $emprunt->setDateEmprunt(new \DateTime('06/06/2023'));
        $emprunt->setDateRetourEstime(new \DateTime('06/30/2023'));

        $this->assertTrue($emprunt->empruntEnRetard());
    }

    /**
     * @test
     */
    public function empruntEnRetard_EmpruntEnCoursAndDateRetourEstimeNonDepasse_Faux() {
        $livre = new \App\Entity\Livre();
        $emprunt = new \App\Entity\Emprunt();
        $adherent = new \App\Entity\Adherent();

        $emprunt->setMedia($livre);
        $emprunt->setAdherent($adherent);
        $emprunt->setDateEmprunt(new \DateTime('06/09/2023'));
        $emprunt->setDateRetourEstime(new \DateTime('12/10/2023'));

        $this->assertFalse($emprunt->empruntEnRetard());
    }

    /**
     * @test
     */
    public function empruntEnRetard_EmpruntPasEnCoursAndDateRetourEstimeDepasse_Faux() {
        $livre = new \App\Entity\Livre();
        $emprunt = new \App\Entity\Emprunt();
        $adherent = new \App\Entity\Adherent();

        $emprunt->setMedia($livre);
        $emprunt->setAdherent($adherent);
        $emprunt->setDateEmprunt(new \DateTime('06/09/2023'));
        $emprunt->setDateRetour( new \DateTime('06/25/2023'));
        $emprunt->setDateRetourEstime(new \DateTime('12/10/2023'));

        $this->assertFalse($emprunt->empruntEnRetard());
    }

    /**
     * @test
     */
    public function empruntEnRetard_EmpruntPasEnCoursAndDateRetourEstimeNonDepasse_Faux() {
        $livre = new \App\Entity\Livre();
        $emprunt = new \App\Entity\Emprunt();
        $adherent = new \App\Entity\Adherent();

        $emprunt->setMedia($livre);
        $emprunt->setAdherent($adherent);
        $emprunt->setDateEmprunt(new \DateTime('06/09/2023'));
        $emprunt->setDateRetour( new \DateTime('06/25/2023'));
        $emprunt->setDateRetourEstime(new \DateTime('12/10/2023'));

        $this->assertFalse($emprunt->empruntEnRetard());
    }





}









