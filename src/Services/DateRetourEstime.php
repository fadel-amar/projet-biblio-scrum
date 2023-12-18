<?php

namespace App\Services;

class DateRetourEstime
{
    public function excute (\DateTime $dateEmprunt, int $dureeEmprunt) {
        $interval = new \DateInterval("P{$dureeEmprunt}D");
        $dateRetourEstime= $dateEmprunt->add($interval);
        return $dateRetourEstime;
    }

}