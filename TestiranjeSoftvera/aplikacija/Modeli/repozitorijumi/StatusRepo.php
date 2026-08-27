<?php
namespace Aplikacija\Modeli\Repozitorijumi;

use Aplikacija\Jezgro\Tabela;
use Aplikacija\Modeli\Entiteti\StatusEntitet;

class StatusRepo extends Tabela {

    public function __construct($konekcija) {
        parent::__construct($konekcija, "Status");
    }

    public function dohvatiSve() {
        $upit = "SELECT * FROM Status ORDER BY naziv";
        $this->ucitajSve($upit);
    }

    public function dohvatiPoId($statusId) {
        $upit = "SELECT * FROM Status WHERE statusId = " . (int)$statusId;
        $this->ucitajSve($upit);
    }

    public function dohvatiSveKaoObjekte() {
        $objekti = [];
        if ($this->brojRedova > 0) {
            $podaci = $this->sviKaoNiz();
            foreach ($podaci as $red) {
                $objekti[] = new StatusEntitet($red['statusId'], $red['naziv']);
            }
        }
        return $objekti;
    }
}