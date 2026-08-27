<?php
namespace Aplikacija\Modeli\Repozitorijumi;

use Aplikacija\Jezgro\Tabela;
use Aplikacija\Modeli\Entiteti\SesijaEntitet;

class SesijaRepo extends Tabela {

    public function __construct($konekcija) {
        parent::__construct($konekcija, "SesijaTestiranja");
    }

    // Pomoćna metoda za čišćenje rezultata na konekciji
    private function ocistiRezultate() {
        while ($this->konekcija->veza->more_results()) {
            $this->konekcija->veza->next_result();
        }
    }

    // Dohvatanje svih sesija (koristi pogled)
    public function dohvatiSve() {
        $this->ocistiRezultate();
        $upit = "SELECT * FROM poglSesije ORDER BY datumKreiranja DESC";
        $this->ucitajSve($upit);
    }

    // Filtriranje po nazivu projekta
    public function filtrirajPoProjektu($naziv) {
        $this->ocistiRezultate();
        $ocisceno = $this->konekcija->ocisti($naziv);
        $upit = "SELECT * FROM poglSesije 
                 WHERE nazivProjekta LIKE '%$ocisceno%' 
                 ORDER BY datumKreiranja DESC";
        $this->ucitajSve($upit);
    }

    // Dohvatanje po ID-u
    public function dohvatiPoId($sesijaId) {
        $this->ocistiRezultate();
        $upit = "SELECT * FROM poglSesije WHERE sesijaId = " . (int)$sesijaId;
        $this->ucitajSve($upit);
    }

    // Dodavanje nove sesije (koristi proceduru)
    public function dodaj(SesijaEntitet $sesija) {
        $this->ocistiRezultate();

        $naziv = $this->konekcija->ocisti($sesija->getNazivProjekta());
        $verzija = $this->konekcija->ocisti($sesija->getVerzija());
        $imeTestera = $this->konekcija->ocisti($sesija->getImeTestera());
        $okruzenje = $this->konekcija->ocisti($sesija->getOkruzenje());
        $komentar = $this->konekcija->ocisti($sesija->getKomentar());

        $this->konekcija->veza->query("SET @pNaziv = '$naziv'");
        $this->konekcija->veza->query("SET @pVerzija = '$verzija'");
        $this->konekcija->veza->query("SET @pImeTestera = '$imeTestera'");
        $this->konekcija->veza->query("SET @pOkruzenje = '$okruzenje'");
        $this->konekcija->veza->query("SET @pKomentar = '$komentar'");

        $rez = $this->konekcija->veza->query("CALL spDodajSesiju(@pNaziv, @pVerzija, @pImeTestera, @pOkruzenje, @pKomentar)");
        
        $this->ocistiRezultate();

        if (!$rez) {
            return mysqli_error($this->konekcija->veza);
        }
        return '';
    }

    // Ažuriranje sesije
    public function azuriraj(SesijaEntitet $sesija) {
        $this->ocistiRezultate();

        $id = (int)$sesija->getSesijaId();
        $naziv = $this->konekcija->ocisti($sesija->getNazivProjekta());
        $verzija = $this->konekcija->ocisti($sesija->getVerzija());
        $imeTestera = $this->konekcija->ocisti($sesija->getImeTestera());
        $okruzenje = $this->konekcija->ocisti($sesija->getOkruzenje());
        $komentar = $this->konekcija->ocisti($sesija->getKomentar());

        $this->konekcija->veza->query("SET @pId = $id");
        $this->konekcija->veza->query("SET @pNaziv = '$naziv'");
        $this->konekcija->veza->query("SET @pVerzija = '$verzija'");
        $this->konekcija->veza->query("SET @pImeTestera = '$imeTestera'");
        $this->konekcija->veza->query("SET @pOkruzenje = '$okruzenje'");
        $this->konekcija->veza->query("SET @pKomentar = '$komentar'");

        $rez = $this->konekcija->veza->query("CALL spAzurirajSesiju(@pId, @pNaziv, @pVerzija, @pImeTestera, @pOkruzenje, @pKomentar)");
        
        $this->ocistiRezultate();

        if (!$rez) {
            return mysqli_error($this->konekcija->veza);
        }
        return '';
    }

    // Brisanje sesije (kaskadno briše i slučajeve)
    public function obrisi($sesijaId) {
        $this->ocistiRezultate();

        $this->konekcija->veza->query("SET @pId = " . (int)$sesijaId);
        $rez = $this->konekcija->veza->query("CALL spObrisiSesiju(@pId)");
        
        $this->ocistiRezultate();

        if (!$rez) {
            return mysqli_error($this->konekcija->veza);
        }
        return '';
    }

    // Dohvatanje sesije kao objekta
    public function dohvatiKaoObjekat() {
        if ($this->brojRedova > 0) {
            $red = $this->sviKaoNiz()[0];
            return new SesijaEntitet(
                $red['sesijaId'],
                $red['nazivProjekta'],
                $red['verzija'],
                $red['imeTestera'],
                $red['okruzenje'],
                $red['komentarSesije'] ?? '',
                $red['datumKreiranja'],
                $red['brojSlucajeva'] ?? 0
            );
        }
        return null;
    }
}