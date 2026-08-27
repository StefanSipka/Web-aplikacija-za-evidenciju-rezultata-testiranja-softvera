/**
 * sesijaAjax.js
 * REST pozivi za sesije (brisanje, filtriranje, itd.)
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // ============================================
    // 1. Brisanje sesije preko REST API-ja
    // ============================================
    
    // Funkcija za brisanje (poziva se iz tabele)
    window.potvrdiBrisanje = function(nazivProjekta, dugme) {
        const id = dugme.getAttribute('data-id');
        if (!id) return false;
        
        const poruka = 'Da li ste sigurni da želite da obrišete sesiju "' + 
                       nazivProjekta + '" (ID: ' + id + ')?\nOva radnja je nepovratna!';
        
        if (!confirm(poruka)) {
            return false;
        }

        // REST DELETE zahtev
        fetch('/TestiranjeSoftvera/api/sesije/' + id, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(function(odgovor) {
            return odgovor.json();
        })
        .then(function(podaci) {
            if (podaci.uspeh) {
                // Ukloni red iz tabele
                const red = dugme.closest('tr');
                if (red) {
                    red.style.opacity = '0.5';
                    red.style.transition = 'opacity 0.3s';
                    setTimeout(function() {
                        red.remove();
                        // Prikaz poruke o uspehu
                        prikaziPoruku('uspeh', podaci.poruka || 'Sesija je uspešno obrisana.');
                    }, 300);
                }
            } else {
                prikaziPoruku('greska', podaci.greska || 'Greška pri brisanju sesije.');
            }
        })
        .catch(function(greska) {
            console.error('Greška:', greska);
            prikaziPoruku('greska', 'Došlo je do greške prilikom brisanja. Pokušajte ponovo.');
        });

        return false;
    };



    // ============================================
    // 3. Dodavanje slučaja preko REST API-ja (za brzi unos)
    // ============================================
    
    // Ova funkcionalnost je opciona - može se koristiti za dodavanje
    // slučajeva bez osvežavanja stranice, ali trenutno je implementirano
    // preko klasične forme. Ostavljamo za moguću nadogradnju.

    // ============================================
    // 4. Pomoćne funkcije
    // ============================================

    function prikaziPoruku(tip, tekst) {
        // Ukloni postojeće poruke
        const postojece = document.querySelectorAll('.poruka-uspeh, .poruka-greska');
        postojece.forEach(function(el) {
            el.remove();
        });

        const div = document.createElement('div');
        if (tip === 'uspeh') {
            div.className = 'poruka-uspeh';
        } else {
            div.className = 'poruka-greska';
        }
        div.textContent = tekst;

        const kontejner = document.querySelector('.kontejner');
        if (kontejner) {
            // Ubaci posle header-a
            const header = kontejner.querySelector('.zaglavlje');
            if (header) {
                header.parentNode.insertBefore(div, header.nextSibling);
            } else {
                kontejner.insertBefore(div, kontejner.firstChild);
            }
        }

        // Automatsko uklanjanje nakon 5 sekundi
        setTimeout(function() {
            div.style.opacity = '0';
            div.style.transition = 'opacity 0.5s';
            setTimeout(function() {
                div.remove();
            }, 500);
        }, 5000);
    }

    // ============================================
    // 5. Učitavanje podataka preko REST API-ja
    // ============================================
    
    // Primer: učitavanje liste sesija (ako želimo dinamički)
    function ucitajSesije() {
        fetch('/api/sesije')
            .then(function(odgovor) {
                return odgovor.json();
            })
            .then(function(podaci) {
                console.log('Sesije učitane:', podaci);
                // Ovde bi se ažurirala tabela
            })
            .catch(function(greska) {
                console.error('Greška pri učitavanju:', greska);
            });
    }

    // ============================================
    // 6. Prikaz detalja preko REST API-ja (opciono)
    // ============================================
    
    // Može se koristiti za prikaz detalja bez osvežavanja stranice
    window.ucitajDetalj = function(id) {
        fetch('/api/sesije/' + id)
            .then(function(odgovor) {
                return odgovor.json();
            })
            .then(function(podaci) {
                console.log('Detalji sesije:', podaci);
                // Prikaz podataka u modal-u ili slično
            })
            .catch(function(greska) {
                console.error('Greška pri učitavanju detalja:', greska);
            });
    };

});