<?php
namespace Aplikacija\Kontroleri;

use Aplikacija\Jezgro\Kontroler;
use Aplikacija\Jezgro\Konekcija;
use Aplikacija\Jezgro\Transakcija;
use Aplikacija\Modeli\Repozitorijumi\SesijaRepo;
use Aplikacija\Modeli\Repozitorijumi\SlucajRepo;
use Aplikacija\Modeli\Entiteti\SesijaEntitet;
use Aplikacija\Modeli\Entiteti\SlucajEntitet;

class ApiKontroler extends Kontroler {
    private $konekcija;

    public function __construct() {
        $this->konekcija = new Konekcija(__DIR__ . '/../podesavanja/bazaPodataka.xml');
        $this->konekcija->otvori();
    }

    // GET /api/sesije - Dohvatanje svih sesija
    public function sve() {
        $repo = new SesijaRepo($this->konekcija);
        $repo->dohvatiSve();
        $podaci = [];

        if ($repo->brojRedova > 0) {
            $niz = $repo->sviKaoNiz();
            foreach ($niz as $red) {
                $podaci[] = [
                    'id' => $red['sesijaId'],
                    'nazivProjekta' => $red['nazivProjekta'],
                    'verzija' => $red['verzija'],
                    'imeTestera' => $red['imeTestera'],
                    'okruzenje' => $red['okruzenje'],
                    'komentar' => $red['komentarSesije'] ?? '',
                    'datumKreiranja' => $red['datumKreiranja'],
                    'brojSlucajeva' => $red['brojSlucajeva'] ?? 0
                ];
            }
        }

        $this->odgovoriJson($podaci);
    }

    // GET /api/sesije/{id} - Dohvatanje jedne sesije sa slučajevima
    public function jedna($id) {
        $repoSesija = new SesijaRepo($this->konekcija);
        $repoSesija->dohvatiPoId($id);
        $sesija = $repoSesija->dohvatiKaoObjekat();

        if (!$sesija) {
            http_response_code(404);
            $this->odgovoriJson(['greska' => 'Sesija nije pronađena']);
        }

        $repoSlucaj = new SlucajRepo($this->konekcija);
        $repoSlucaj->dohvatiPoSesiji($id);
        $slucajevi = [];
        if ($repoSlucaj->brojRedova > 0) {
            $niz = $repoSlucaj->sviKaoNiz();
            foreach ($niz as $red) {
                $slucajevi[] = [
                    'id' => $red['slucajId'],
                    'redniBroj' => $red['redniBroj'],
                    'opis' => $red['opis'],
                    'ocekivaniRezultat' => $red['ocekivaniRezultat'],
                    'stvarniRezultat' => $red['stvarniRezultat'],
                    'statusId' => $red['statusId'],
                    'statusNaziv' => $red['statusNaziv'],
                    'komentar' => $red['komentar']
                ];
            }
        }

        $this->odgovoriJson([
            'sesija' => [
                'id' => $sesija->getSesijaId(),
                'nazivProjekta' => $sesija->getNazivProjekta(),
                'verzija' => $sesija->getVerzija(),
                'imeTestera' => $sesija->getImeTestera(),
                'okruzenje' => $sesija->getOkruzenje(),
                'komentar' => $sesija->getKomentar(),
                'datumKreiranja' => $sesija->getDatumKreiranja()
            ],
            'slucajevi' => $slucajevi
        ]);
    }

    // POST /api/sesije - Kreiranje nove sesije
    public function dodaj() {
        $podaci = json_decode(file_get_contents('php://input'), true);

        if (!$podaci || empty($podaci['nazivProjekta']) || empty($podaci['imeTestera'])) {
            http_response_code(400);
            $this->odgovoriJson(['greska' => 'Naziv projekta i ime testera su obavezni.']);
        }

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
        $id = $this->konekcija->poslednjiId();

        // Slučajevi
        if (empty($greska) && isset($podaci['slucajevi']) && is_array($podaci['slucajevi'])) {
            $repoSlucaj = new SlucajRepo($this->konekcija);
            foreach ($podaci['slucajevi'] as $i => $s) {
                if (empty($s['opis'])) continue;
                $slucaj = new SlucajEntitet();
                $slucaj->setSesijaId($id);
                $slucaj->setRedniBroj($i + 1);
                $slucaj->setOpis($s['opis']);
                $slucaj->setOcekivaniRezultat($s['ocekivaniRezultat'] ?? '');
                $slucaj->setStvarniRezultat($s['stvarniRezultat'] ?? '');
                $slucaj->setStatusId($s['statusId'] ?? 4);
                $slucaj->setKomentar($s['komentar'] ?? '');
                $greska .= $repoSlucaj->dodaj($slucaj);
            }
        }

        $trans->zavrsi($greska);

        if (empty($greska)) {
            http_response_code(201);
            $this->odgovoriJson([
                'uspeh' => true,
                'poruka' => 'Sesija je uspešno kreirana.',
                'id' => $id
            ]);
        } else {
            http_response_code(500);
            $this->odgovoriJson([
                'uspeh' => false,
                'greska' => $greska
            ]);
        }
    }

    // PUT /api/sesije/{id} - Ažuriranje sesije
    public function izmeni($id) {
        $podaci = json_decode(file_get_contents('php://input'), true);

        if (!$podaci || empty($podaci['nazivProjekta']) || empty($podaci['imeTestera'])) {
            http_response_code(400);
            $this->odgovoriJson(['greska' => 'Naziv projekta i ime testera su obavezni.']);
        }

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
        if (empty($greska) && isset($podaci['slucajevi'])) {
            $repoSlucaj = new SlucajRepo($this->konekcija);
            
            // Dohvati postojeće
            $repoSlucaj->dohvatiPoSesiji($id);
            $postojeci = [];
            if ($repoSlucaj->brojRedova > 0) {
                $niz = $repoSlucaj->sviKaoNiz();
                foreach ($niz as $red) {
                    $postojeci[$red['slucajId']] = $red;
                }
            }

            $noviIds = [];
            foreach ($podaci['slucajevi'] as $i => $s) {
                if (empty($s['opis'])) continue;
                $slucaj = new SlucajEntitet();
                if (!empty($s['id'])) {
                    $slucaj->setSlucajId($s['id']);
                    $noviIds[] = $s['id'];
                }
                $slucaj->setSesijaId($id);
                $slucaj->setRedniBroj($i + 1);
                $slucaj->setOpis($s['opis']);
                $slucaj->setOcekivaniRezultat($s['ocekivaniRezultat'] ?? '');
                $slucaj->setStvarniRezultat($s['stvarniRezultat'] ?? '');
                $slucaj->setStatusId($s['statusId'] ?? 4);
                $slucaj->setKomentar($s['komentar'] ?? '');

                if (!empty($s['id'])) {
                    $greska .= $repoSlucaj->azuriraj($slucaj);
                } else {
                    $greska .= $repoSlucaj->dodaj($slucaj);
                }
            }

            // Brisanje uklonjenih
            foreach ($postojeci as $idS => $_) {
                if (!in_array($idS, $noviIds)) {
                    $greska .= $repoSlucaj->obrisi($idS);
                }
            }
        }

        $trans->zavrsi($greska);

        if (empty($greska)) {
            $this->odgovoriJson([
                'uspeh' => true,
                'poruka' => 'Sesija je uspešno ažurirana.'
            ]);
        } else {
            http_response_code(500);
            $this->odgovoriJson([
                'uspeh' => false,
                'greska' => $greska
            ]);
        }
    }

    // DELETE /api/sesije/{id} - Brisanje sesije
    public function ukloni($id) {
        $repo = new SesijaRepo($this->konekcija);
        $greska = $repo->obrisi($id);

        if (empty($greska)) {
            $this->odgovoriJson([
                'uspeh' => true,
                'poruka' => 'Sesija je uspešno obrisana.'
            ]);
        } else {
            http_response_code(500);
            $this->odgovoriJson([
                'uspeh' => false,
                'greska' => $greska
            ]);
        }
    }

    // GET /api/sesije/{id}/slucajevi - Dohvatanje samo slučajeva za sesiju
    public function slucajevi($id) {
        $repo = new SlucajRepo($this->konekcija);
        $repo->dohvatiPoSesiji($id);
        $podaci = [];

        if ($repo->brojRedova > 0) {
            $niz = $repo->sviKaoNiz();
            foreach ($niz as $red) {
                $podaci[] = [
                    'id' => $red['slucajId'],
                    'redniBroj' => $red['redniBroj'],
                    'opis' => $red['opis'],
                    'ocekivaniRezultat' => $red['ocekivaniRezultat'],
                    'stvarniRezultat' => $red['stvarniRezultat'],
                    'statusId' => $red['statusId'],
                    'statusNaziv' => $red['statusNaziv'],
                    'komentar' => $red['komentar']
                ];
            }
        }

        $this->odgovoriJson($podaci);
    }
}