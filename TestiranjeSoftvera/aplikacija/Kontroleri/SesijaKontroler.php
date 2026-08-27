<?php
namespace Aplikacija\Kontroleri;

use Aplikacija\Jezgro\Kontroler;
use Aplikacija\Jezgro\Konekcija;
use Aplikacija\Jezgro\Transakcija;
use Aplikacija\Modeli\Repozitorijumi\SesijaRepo;
use Aplikacija\Modeli\Repozitorijumi\SlucajRepo;
use Aplikacija\Modeli\Repozitorijumi\StatusRepo;
use Aplikacija\Modeli\Entiteti\SesijaEntitet;
use Aplikacija\Modeli\Entiteti\SlucajEntitet;

class SesijaKontroler extends Kontroler {
    private $konekcija;

    public function __construct() {
        $this->konekcija = new Konekcija(__DIR__ . '/../podesavanja/bazaPodataka.xml');
        $this->konekcija->otvori();
    }









    // Tabelarni prikaz svih sesija sa filterom
    public function spisak() {
        $repo = new SesijaRepo($this->konekcija);
        $filter = $_GET['filter'] ?? '';

        if (!empty($filter)) {
            $repo->filtrirajPoProjektu($filter);
        } else {
            $repo->dohvatiSve();
        }

        $this->prikazi('sesija/spisak', [
            'repo' => $repo,
            'filter' => $filter
        ]);
    }

    // Forma za kreiranje nove sesije (master-detail)
    public function kreiraj() {
        

        $statusRepo = new StatusRepo($this->konekcija);
        $statusRepo->dohvatiSve();
        $statusi = $statusRepo->dohvatiSveKaoObjekte();

        $this->prikazi('sesija/kreiranje', [
            'statusi' => $statusi
        ]);
    }

    // Snimanje nove sesije sa slučajevima (transakcija)
    public function snimi() {
       

        $podaci = $_POST;

        // Validacija
        if (empty($podaci['nazivProjekta']) || empty($podaci['imeTestera'])) {
            $_SESSION['greska'] = 'Naziv projekta i ime testera su obavezni.';
            header('Location: ' . BASE_URL . 'sesije/kreiraj');
            exit;
        }

        // Kreiranje sesije
        $sesija = new SesijaEntitet();
        $sesija->setNazivProjekta($podaci['nazivProjekta']);
        $sesija->setVerzija($podaci['verzija'] ?? '');
        $sesija->setImeTestera($podaci['imeTestera']);
        $sesija->setOkruzenje($podaci['okruzenje'] ?? '');
        $sesija->setKomentar($podaci['komentar'] ?? '');

        $trans = new Transakcija($this->konekcija);
        $trans->zapocni();

        $repoSesija = new SesijaRepo($this->konekcija);
        $greska = $repoSesija->dodaj($sesija);
        
        // Dohvatanje ID-a nove sesije
        $rezId = $this->konekcija->veza->query("SELECT LAST_INSERT_ID() as id");
        $redId = $rezId ? $rezId->fetch_assoc() : null;
        $id = $redId['id'] ?? 0;

        // Snimanje slučajeva
        if (empty($greska) && isset($podaci['opis']) && is_array($podaci['opis'])) {
            $repoSlucaj = new SlucajRepo($this->konekcija);
            foreach ($podaci['opis'] as $i => $opis) {
                if (empty($opis)) continue;
                
                $slucaj = new SlucajEntitet();
                $slucaj->setSesijaId($id);
                $slucaj->setRedniBroj($i + 1);
                $slucaj->setOpis($opis);
                $slucaj->setOcekivaniRezultat($podaci['ocekivani'][$i] ?? '');
                $slucaj->setStvarniRezultat($podaci['stvarni'][$i] ?? '');
                $slucaj->setStatusId($podaci['statusId'][$i] ?? 4); // 4 = Nije testiran
                $slucaj->setKomentar($podaci['komentarSlucaja'][$i] ?? '');
                
                $greska .= $repoSlucaj->dodaj($slucaj);
            }
        }

        $trans->zavrsi($greska);

        if (empty($greska)) {
            $_SESSION['poruka'] = 'Sesija je uspešno kreirana.';
            header('Location: ' . BASE_URL . 'sesije');
        } else {
            $_SESSION['greska'] = 'Greška pri kreiranju: ' . $greska;
            header('Location: ' . BASE_URL . 'sesije/kreiraj');
        }
        exit;
    }

    // Prikaz jedne sesije sa svim slučajevima (master-detail)
    public function pregled($id) {
        $repoSesija = new SesijaRepo($this->konekcija);
        $repoSesija->dohvatiPoId($id);
        $sesija = $repoSesija->dohvatiKaoObjekat();

        if (!$sesija) {
            $_SESSION['greska'] = 'Sesija nije pronađena.';
            header('Location: ' . BASE_URL . 'sesije');
            exit;
        }

        $repoSlucaj = new SlucajRepo($this->konekcija);
        $repoSlucaj->dohvatiPoSesiji($id);
        $slucajevi = $repoSlucaj->dohvatiSveKaoObjekte();

        $this->prikazi('sesija/pregled', [
            'sesija' => $sesija,
            'slucajevi' => $slucajevi
        ]);
    }

    // Forma za izmenu sesije
    public function izmeni($id) {
        

        $repoSesija = new SesijaRepo($this->konekcija);
        $repoSesija->dohvatiPoId($id);
        $sesija = $repoSesija->dohvatiKaoObjekat();

        if (!$sesija) {
            $_SESSION['greska'] = 'Sesija nije pronađena.';
            header('Location: ' . BASE_URL . 'sesije');
            exit;
        }

        $repoSlucaj = new SlucajRepo($this->konekcija);
        $repoSlucaj->dohvatiPoSesiji($id);
        $slucajevi = $repoSlucaj->dohvatiSveKaoObjekte();

        $statusRepo = new StatusRepo($this->konekcija);
        $statusRepo->dohvatiSve();
        $statusi = $statusRepo->dohvatiSveKaoObjekte();

        $this->prikazi('sesija/izmena', [
            'sesija' => $sesija,
            'slucajevi' => $slucajevi,
            'statusi' => $statusi
        ]);
    }

    // Ažuriranje sesije
    public function azuriraj($id) {
        


        $podaci = $_POST;

        // Validacija
        if (empty($podaci['nazivProjekta']) || empty($podaci['imeTestera'])) {
            $_SESSION['greska'] = 'Naziv projekta i ime testera su obavezni.';
            header("Location: " . BASE_URL . "sesije/$id/izmeni");
            exit;
        }

        // Ažuriranje sesije
        $sesija = new SesijaEntitet();
        $sesija->setSesijaId($id);
        $sesija->setNazivProjekta($podaci['nazivProjekta']);
        $sesija->setVerzija($podaci['verzija'] ?? '');
        $sesija->setImeTestera($podaci['imeTestera']);
        $sesija->setOkruzenje($podaci['okruzenje'] ?? '');
        $sesija->setKomentar($podaci['komentar'] ?? '');

        $trans = new Transakcija($this->konekcija);
        $trans->zapocni();

        $repoSesija = new SesijaRepo($this->konekcija);
        $greska = $repoSesija->azuriraj($sesija);

        // Ažuriranje slučajeva
        if (empty($greska) && isset($podaci['slucajId']) && is_array($podaci['slucajId'])) {
            $repoSlucaj = new SlucajRepo($this->konekcija);
            
            // Dohvati postojeće slučajeve
            $repoSlucaj->dohvatiPoSesiji($id);
            $postojeci = [];
            foreach ($repoSlucaj->dohvatiSveKaoObjekte() as $s) {
                $postojeci[$s->getSlucajId()] = $s;
            }

            $noviIds = [];
            foreach ($podaci['slucajId'] as $i => $slucajId) {
                if (empty($podaci['opis'][$i])) continue;
                
                $slucaj = new SlucajEntitet();
                if (!empty($slucajId)) {
                    $slucaj->setSlucajId($slucajId);
                    $noviIds[] = $slucajId;
                }
                $slucaj->setSesijaId($id);
                $slucaj->setRedniBroj($i + 1);
                $slucaj->setOpis($podaci['opis'][$i]);
                $slucaj->setOcekivaniRezultat($podaci['ocekivani'][$i] ?? '');
                $slucaj->setStvarniRezultat($podaci['stvarni'][$i] ?? '');
                $slucaj->setStatusId($podaci['statusId'][$i] ?? 4);
                $slucaj->setKomentar($podaci['komentarSlucaja'][$i] ?? '');

                if (!empty($slucajId)) {
                    $greska .= $repoSlucaj->azuriraj($slucaj);
                } else {
                    $greska .= $repoSlucaj->dodaj($slucaj);
                }
            }

            // Brisanje uklonjenih slučajeva
            foreach ($postojeci as $idS => $slucaj) {
                if (!in_array($idS, $noviIds)) {
                    $greska .= $repoSlucaj->obrisi($idS);
                }
            }
        }

        $trans->zavrsi($greska);

        if (empty($greska)) {
            $_SESSION['poruka'] = 'Sesija je uspešno ažurirana.';
            header('Location: ' . BASE_URL . 'sesije');
        } else {
            $_SESSION['greska'] = 'Greška pri ažuriranju: ' . $greska;
            header("Location: " . BASE_URL . "sesije/$id/izmeni");
        }
        exit;
    }

    // Brisanje sesije
// Brisanje sesije sa transakcijom
public function obrisi($id) {
    

    $trans = new Transakcija($this->konekcija);
    $trans->zapocni();

    $repo = new SesijaRepo($this->konekcija);
    $greska = $repo->obrisi($id);

    $trans->zavrsi($greska);

    if (empty($greska)) {
        $_SESSION['poruka'] = 'Sesija je uspešno obrisana.';
    } else {
        $_SESSION['greska'] = 'Greška pri brisanju: ' . $greska;
    }
    header('Location: ' . BASE_URL . 'sesije');
    exit;
}

    // Štampa sesije (master-detail dokument)
    public function stampaj($id) {
        $repoSesija = new SesijaRepo($this->konekcija);
        $repoSesija->dohvatiPoId($id);
        $sesija = $repoSesija->dohvatiKaoObjekat();

        if (!$sesija) {
            $_SESSION['greska'] = 'Sesija nije pronađena.';
            header('Location: ' . BASE_URL . 'sesije');
            exit;
        }

        $repoSlucaj = new SlucajRepo($this->konekcija);
        $repoSlucaj->dohvatiPoSesiji($id);
        $slucajevi = $repoSlucaj->dohvatiSveKaoObjekte();

        $this->prikazi('sesija/stampa', [
            'sesija' => $sesija,
            'slucajevi' => $slucajevi
        ]);
    }
}