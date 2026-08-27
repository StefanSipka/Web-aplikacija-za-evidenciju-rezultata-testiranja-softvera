<?php
namespace Aplikacija\Modeli\Repozitorijumi;

use Aplikacija\Jezgro\Tabela;
use Aplikacija\Modeli\Entiteti\KorisnikEntitet;

class KorisnikRepo extends Tabela {

    public function __construct($konekcija) {
        parent::__construct($konekcija, "Korisnik");
    }

    public function dohvatiPoEmailu($email) {
        $ocisceno = $this->konekcija->ocisti($email);
        $upit = "SELECT * FROM Korisnik WHERE email = '$ocisceno'";
        $this->ucitajSve($upit);
    }

    public function dodaj(KorisnikEntitet $korisnik) {
        $email = $this->konekcija->ocisti($korisnik->getEmail());
        $hash = $this->konekcija->ocisti($korisnik->getLozinkaHash());
        $ime = $this->konekcija->ocisti($korisnik->getIme());
        $prezime = $this->konekcija->ocisti($korisnik->getPrezime());

        $upit = "INSERT INTO Korisnik (email, lozinkaHash, ime, prezime) 
                 VALUES ('$email', '$hash', '$ime', '$prezime')";
        return $this->izvrsiUpit($upit);
    }

    public function dohvatiKaoObjekat() {
        if ($this->brojRedova > 0) {
            $red = $this->sviKaoNiz()[0];
            return new KorisnikEntitet(
                $red['korisnikId'],
                $red['email'],
                $red['lozinkaHash'],
                $red['ime'],
                $red['prezime']
            );
        }
        return null;
    }
}