<?php
namespace Aplikacija\Modeli\Entiteti;

class SlucajEntitet {
    private $slucajId;
    private $sesijaId;
    private $redniBroj;
    private $opis;
    private $ocekivaniRezultat;
    private $stvarniRezultat;
    private $komentar;
    private $statusId;
    private $statusNaziv; // Dodatno za prikaz (iz asocijacije)

    public function __construct(
        $slucajId = null,
        $sesijaId = null,
        $redniBroj = null,
        $opis = null,
        $ocekivaniRezultat = null,
        $stvarniRezultat = null,
        $komentar = null,
        $statusId = null,
        $statusNaziv = null
    ) {
        $this->slucajId = $slucajId;
        $this->sesijaId = $sesijaId;
        $this->redniBroj = $redniBroj;
        $this->opis = $opis;
        $this->ocekivaniRezultat = $ocekivaniRezultat;
        $this->stvarniRezultat = $stvarniRezultat;
        $this->komentar = $komentar;
        $this->statusId = $statusId;
        $this->statusNaziv = $statusNaziv;
    }

    // GET metode
    public function getSlucajId() { return $this->slucajId; }
    public function getSesijaId() { return $this->sesijaId; }
    public function getRedniBroj() { return $this->redniBroj; }
    public function getOpis() { return $this->opis; }
    public function getOcekivaniRezultat() { return $this->ocekivaniRezultat; }
    public function getStvarniRezultat() { return $this->stvarniRezultat; }
    public function getKomentar() { return $this->komentar; }
    public function getStatusId() { return $this->statusId; }
    public function getStatusNaziv() { return $this->statusNaziv; }

    // SET metode
    public function setSlucajId($slucajId) { $this->slucajId = $slucajId; }
    public function setSesijaId($sesijaId) { $this->sesijaId = $sesijaId; }
    public function setRedniBroj($redniBroj) { $this->redniBroj = $redniBroj; }
    public function setOpis($opis) { $this->opis = $opis; }
    public function setOcekivaniRezultat($ocekivaniRezultat) { $this->ocekivaniRezultat = $ocekivaniRezultat; }
    public function setStvarniRezultat($stvarniRezultat) { $this->stvarniRezultat = $stvarniRezultat; }
    public function setKomentar($komentar) { $this->komentar = $komentar; }
    public function setStatusId($statusId) { $this->statusId = $statusId; }
    public function setStatusNaziv($statusNaziv) { $this->statusNaziv = $statusNaziv; }
}