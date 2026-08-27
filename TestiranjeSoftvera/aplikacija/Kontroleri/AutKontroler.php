<?php
namespace Aplikacija\Kontroleri;

use Aplikacija\Jezgro\Kontroler;
use Aplikacija\Jezgro\Konekcija;
use Aplikacija\Modeli\Repozitorijumi\KorisnikRepo;
use Aplikacija\Modeli\Entiteti\KorisnikEntitet;

class AutKontroler extends Kontroler {
    private $konekcija;

    public function __construct() {
        $this->konekcija = new Konekcija(__DIR__ . '/../podesavanja/bazaPodataka.xml');
        $this->konekcija->otvori();
    }

    // Prikaz forme za prijavu
    public function prijava() {
        $this->prikazi('auth/prijava.php');
    }

    // Obrada prijave
    public function loguj() {
        $email = $_POST['email'] ?? '';
        $lozinka = $_POST['lozinka'] ?? '';

        if (empty($email) || empty($lozinka)) {
            $_SESSION['greska'] = 'Molimo popunite sva polja.';
            header('Location: /prijava');
            exit;
        }

        $repo = new KorisnikRepo($this->konekcija);
        $repo->dohvatiPoEmailu($email);
        $korisnik = $repo->dohvatiKaoObjekat();

        if ($korisnik && password_verify($lozinka, $korisnik->getLozinkaHash())) {
            $_SESSION['korisnikId'] = $korisnik->getKorisnikId();
            $_SESSION['email'] = $korisnik->getEmail();
            $_SESSION['ime'] = $korisnik->getIme();
            $_SESSION['prezime'] = $korisnik->getPrezime();
            header('Location: /sesije');
            exit;
        } else {
            $_SESSION['greska'] = 'Pogrešan email ili lozinka.';
            header('Location: /prijava');
            exit;
        }
    }

    // Prikaz forme za registraciju
    public function registracija() {
        $this->prikazi('auth/registracija.php');
    }

    // Obrada registracije
    public function registruj() {
        $email = $_POST['email'] ?? '';
        $lozinka = $_POST['lozinka'] ?? '';
        $ime = $_POST['ime'] ?? '';
        $prezime = $_POST['prezime'] ?? '';

        // Validacija
        if (empty($email) || empty($lozinka) || empty($ime) || empty($prezime)) {
            $_SESSION['greska'] = 'Molimo popunite sva polja.';
            header('Location: /registracija');
            exit;
        }

        if (strlen($lozinka) < 6) {
            $_SESSION['greska'] = 'Lozinka mora imati najmanje 6 karaktera.';
            header('Location: /registracija');
            exit;
        }

        // Provera da li već postoji
        $repo = new KorisnikRepo($this->konekcija);
        $repo->dohvatiPoEmailu($email);
        if ($repo->brojRedova > 0) {
            $_SESSION['greska'] = 'Korisnik sa ovom email adresom već postoji.';
            header('Location: /registracija');
            exit;
        }

        // Kreiranje novog korisnika
        $hash = password_hash($lozinka, PASSWORD_DEFAULT);
        $noviKorisnik = new KorisnikEntitet(null, $email, $hash, $ime, $prezime);
        $greska = $repo->dodaj($noviKorisnik);

        if (empty($greska)) {
            $_SESSION['poruka'] = 'Uspešno ste registrovani. Molimo prijavite se.';
            header('Location: /prijava');
            exit;
        } else {
            $_SESSION['greska'] = 'Greška pri registraciji: ' . $greska;
            header('Location: /registracija');
            exit;
        }
    }

    // Odjava
    public function odjava() {
        session_destroy();
        header('Location: /prijava');
        exit;
    }
}