<?php


class GenereateurNumeroAdherentTest extends \PHPUnit\Framework\TestCase {

    /**
     * @test
     */

    public function Generer_NumeroGenererValide_Vrai()  {

        $GeneratueurNumeroAdherent = new \App\Services\GenerateurNumeroAdherent();
        $numeroAdherent =$GeneratueurNumeroAdherent->generer();

        $LongeurEgale9 = strlen($numeroAdherent) == 9;
        $this->assertTrue($LongeurEgale9,"La taille du numéro doit être égale à 9");

        $PrefixeEgaleAD = str_starts_with($numeroAdherent, "AD-");
        $this->assertTrue($PrefixeEgaleAD,"Le numéro doit commencer par AD-");

        $sousChaineAdherant = (substr($numeroAdherent, 3,));
        $ContientNombre = preg_match("/[^0-9]/", $sousChaineAdherant );
        $this->assertEquals(0,$ContientNombre,"La numéro adhérent doit contenir que des chiffres");

    }







}