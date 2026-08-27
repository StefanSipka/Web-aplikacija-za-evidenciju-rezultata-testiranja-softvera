<?php
namespace Aplikacija\Modeli\Entiteti;

class KorisnikEntitet {
    private $korisnikId;
    private $email;
    private $lozinkaHash;
    private $ime;
    private $prezime;

    public function __construct(
        $korisnikId = null,
        $email = null,
        $lozinkaHash = null,
        $ime = null,
        $prezime = null
    ) {
        $this->korisnikId = $korisnikId;
        $this->email = $email;
        $this->lozinkaHash = $lozinkaHash;
        $this->ime = $ime;
        $this->prezime = $prezime;
    }

    public function getKorisnikId() { return $this->korisnikId; }
    public function getEmail() { return $this->email; }
    public function getLozinkaHash() { return $this->lozinkaHash; }
    public function getIme() { return $this->ime; }
    public function getPrezime() { return $this->prezime; }

    public function setKorisnikId($korisnikId) { $this->korisnikId = $korisnikId; }
    public function setEmail($email) { $this->email = $email; }
    public function setLozinkaHash($lozinkaHash) { $this->lozinkaHash = $lozinkaHash; }
    public function setIme($ime) { $this->ime = $ime; }
    public function setPrezime($prezime) { $this->prezime = $prezime; }
}