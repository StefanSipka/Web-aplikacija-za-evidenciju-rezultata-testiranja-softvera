<?php
namespace Aplikacija\Modeli\Repozitorijumi;

use Aplikacija\Jezgro\Tabela;
use Aplikacija\Modeli\Entiteti\SlucajEntitet;

class SlucajRepo extends Tabela {

    public function __construct($konekcija) {
        parent::__construct($konekcija, "SlucajTestiranja");
    }

    // Pomoćna metoda za čišćenje rezultata na konekciji
    private function ocistiRezultate() {
        while ($this->konekcija->veza->more_results()) {
            $this->konekcija->veza->next_result();
        }
    }

    // Dohvatanje svih slučajeva za sesiju
    public function dohvatiPoSesiji($sesijaId) {
        $this->ocistiRezultate();
        $upit = "SELECT sl.*, st.naziv AS statusNaziv 
                 FROM SlucajTestiranja sl
                 LEFT JOIN Status st ON sl.statusId = st.statusId
                 WHERE sl.sesijaId = " . (int)$sesijaId . "
                 ORDER BY sl.redniBroj";
        $this->ucitajSve($upit);
    }

    // Dohvatanje jednog slučaja
    public function dohvatiPoId($slucajId) {
        $this->ocistiRezultate();
        $upit = "SELECT sl.*, st.naziv AS statusNaziv 
                 FROM SlucajTestiranja sl
                 LEFT JOIN Status st ON sl.statusId = st.statusId
                 WHERE sl.slucajId = " . (int)$slucajId;
        $this->ucitajSve($upit);
    }

    // Dodavanje slučaja (koristi proceduru)
    public function dodaj(SlucajEntitet $slucaj) {
        $this->ocistiRezultate();

        $sesijaId = (int)$slucaj->getSesijaId();
        $redniBroj = (int)$slucaj->getRedniBroj();
        $opis = $this->konekcija->ocisti($slucaj->getOpis());
        $ocekivani = $this->konekcija->ocisti($slucaj->getOcekivaniRezultat());
        $stvarni = $this->konekcija->ocisti($slucaj->getStvarniRezultat());
        $statusId = (int)$slucaj->getStatusId();
        $komentar = $this->konekcija->ocisti($slucaj->getKomentar());

        $this->konekcija->veza->query("SET @pSesijaId = $sesijaId");
        $this->konekcija->veza->query("SET @pRedniBroj = $redniBroj");
        $this->konekcija->veza->query("SET @pOpis = '$opis'");
        $this->konekcija->veza->query("SET @pOcekivani = '$ocekivani'");
        $this->konekcija->veza->query("SET @pStvarni = '$stvarni'");
        $this->konekcija->veza->query("SET @pStatusId = $statusId");
        $this->konekcija->veza->query("SET @pKomentar = '$komentar'");

        $rez = $this->konekcija->veza->query("CALL spDodajSlucaj(@pSesijaId, @pRedniBroj, @pOpis, @pOcekivani, @pStvarni, @pStatusId, @pKomentar)");
        
        $this->ocistiRezultate();

        if (!$rez) {
            return mysqli_error($this->konekcija->veza);
        }
        return '';
    }

    // Ažuriranje slučaja
    public function azuriraj(SlucajEntitet $slucaj) {
        $this->ocistiRezultate();

        $id = (int)$slucaj->getSlucajId();
        $redniBroj = (int)$slucaj->getRedniBroj();
        $opis = $this->konekcija->ocisti($slucaj->getOpis());
        $ocekivani = $this->konekcija->ocisti($slucaj->getOcekivaniRezultat());
        $stvarni = $this->konekcija->ocisti($slucaj->getStvarniRezultat());
        $statusId = (int)$slucaj->getStatusId();
        $komentar = $this->konekcija->ocisti($slucaj->getKomentar());

        $this->konekcija->veza->query("SET @pId = $id");
        $this->konekcija->veza->query("SET @pRedniBroj = $redniBroj");
        $this->konekcija->veza->query("SET @pOpis = '$opis'");
        $this->konekcija->veza->query("SET @pOcekivani = '$ocekivani'");
        $this->konekcija->veza->query("SET @pStvarni = '$stvarni'");
        $this->konekcija->veza->query("SET @pStatusId = $statusId");
        $this->konekcija->veza->query("SET @pKomentar = '$komentar'");

        $rez = $this->konekcija->veza->query("CALL spAzurirajSlucaj(@pId, @pRedniBroj, @pOpis, @pOcekivani, @pStvarni, @pStatusId, @pKomentar)");
        
        $this->ocistiRezultate();

        if (!$rez) {
            return mysqli_error($this->konekcija->veza);
        }
        return '';
    }

    // Brisanje slučaja
    public function obrisi($slucajId) {
        $this->ocistiRezultate();

        $this->konekcija->veza->query("SET @pId = " . (int)$slucajId);
        $rez = $this->konekcija->veza->query("CALL spObrisiSlucaj(@pId)");
        
        $this->ocistiRezultate();

        if (!$rez) {
            return mysqli_error($this->konekcija->veza);
        }
        return '';
    }

    // Dohvatanje svih slučajeva kao niz objekata
    public function dohvatiSveKaoObjekte() {
        $objekti = [];
        if ($this->brojRedova > 0) {
            $podaci = $this->sviKaoNiz();
            foreach ($podaci as $red) {
                $objekti[] = new SlucajEntitet(
                    $red['slucajId'],
                    $red['sesijaId'],
                    $red['redniBroj'],
                    $red['opis'],
                    $red['ocekivaniRezultat'],
                    $red['stvarniRezultat'],
                    $red['komentar'],
                    $red['statusId'],
                    $red['statusNaziv'] ?? null
                );
            }
        }
        return $objekti;
    }
}