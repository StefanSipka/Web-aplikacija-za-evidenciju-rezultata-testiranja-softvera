<?php
namespace Aplikacija\Modeli\Entiteti;

class SesijaEntitet {
    private $sesijaId;
    private $nazivProjekta;
    private $verzija;
    private $imeTestera;
    private $okruzenje;
    private $komentar;
    private $datumKreiranja;
    private $brojSlucajeva; // Dodatno polje za prikaz (iz pogleda)

    // Konstruktor
    public function __construct(
        $sesijaId = null,
        $nazivProjekta = null,
        $verzija = null,
        $imeTestera = null,
        $okruzenje = null,
        $komentar = null,
        $datumKreiranja = null,
        $brojSlucajeva = 0
    ) {
        $this->sesijaId = $sesijaId;
        $this->nazivProjekta = $nazivProjekta;
        $this->verzija = $verzija;
        $this->imeTestera = $imeTestera;
        $this->okruzenje = $okruzenje;
        $this->komentar = $komentar;
        $this->datumKreiranja = $datumKreiranja;
        $this->brojSlucajeva = $brojSlucajeva;
    }

    // GET metode
    public function getSesijaId() { return $this->sesijaId; }
    public function getNazivProjekta() { return $this->nazivProjekta; }
    public function getVerzija() { return $this->verzija; }
    public function getImeTestera() { return $this->imeTestera; }
    public function getOkruzenje() { return $this->okruzenje; }
    public function getKomentar() { return $this->komentar; }
    public function getDatumKreiranja() { return $this->datumKreiranja; }
    public function getBrojSlucajeva() { return $this->brojSlucajeva; }

    // SET metode
    public function setSesijaId($sesijaId) { $this->sesijaId = $sesijaId; }
    public function setNazivProjekta($nazivProjekta) { $this->nazivProjekta = $nazivProjekta; }
    public function setVerzija($verzija) { $this->verzija = $verzija; }
    public function setImeTestera($imeTestera) { $this->imeTestera = $imeTestera; }
    public function setOkruzenje($okruzenje) { $this->okruzenje = $okruzenje; }
    public function setKomentar($komentar) { $this->komentar = $komentar; }
    public function setDatumKreiranja($datumKreiranja) { $this->datumKreiranja = $datumKreiranja; }
    public function setBrojSlucajeva($brojSlucajeva) { $this->brojSlucajeva = $brojSlucajeva; }
}