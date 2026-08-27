/**
 * validacija.js
 * Klijentska validacija formi
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Validacija registracione forme
    const registracijaForma = document.getElementById('registracijaForma');
    if (registracijaForma) {
        registracijaForma.addEventListener('submit', function(e) {
            let validno = true;
            let poruke = [];

            // Ime - minimum 2 karaktera, samo slova
            const ime = document.getElementById('ime');
            if (ime && ime.value.trim().length < 2) {
                validno = false;
                poruke.push('Ime mora imati najmanje 2 karaktera.');
                ime.style.borderColor = 'red';
            } else if (ime) {
                ime.style.borderColor = '';
            }

            // Prezime - minimum 2 karaktera, samo slova
            const prezime = document.getElementById('prezime');
            if (prezime && prezime.value.trim().length < 2) {
                validno = false;
                poruke.push('Prezime mora imati najmanje 2 karaktera.');
                prezime.style.borderColor = 'red';
            } else if (prezime) {
                prezime.style.borderColor = '';
            }

            // Email - validan format
            const email = document.getElementById('email');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (email && !emailRegex.test(email.value.trim())) {
                validno = false;
                poruke.push('Unesite validnu email adresu.');
                email.style.borderColor = 'red';
            } else if (email) {
                email.style.borderColor = '';
            }

            // Lozinka - minimum 6 karaktera
            const lozinka = document.getElementById('lozinka');
            if (lozinka && lozinka.value.length < 6) {
                validno = false;
                poruke.push('Lozinka mora imati najmanje 6 karaktera.');
                lozinka.style.borderColor = 'red';
            } else if (lozinka) {
                lozinka.style.borderColor = '';
            }

            // Potvrda lozinke
            const lozinkaPotvrda = document.getElementById('lozinkaPotvrda');
            if (lozinkaPotvrda && lozinka.value !== lozinkaPotvrda.value) {
                validno = false;
                poruke.push('Lozinke se ne poklapaju.');
                lozinkaPotvrda.style.borderColor = 'red';
            } else if (lozinkaPotvrda) {
                lozinkaPotvrda.style.borderColor = '';
            }

            // Prikaz grešaka
            if (!validno) {
                e.preventDefault();
                prikaziGreske(poruke);
            }
        });
    }

    // Validacija forme za sesiju (kreiranje/izmena)
    const sesijaForma = document.getElementById('sesijaForma');
    if (sesijaForma) {
        sesijaForma.addEventListener('submit', function(e) {
            let validno = true;
            let poruke = [];

            // Naziv projekta
            const naziv = document.getElementById('nazivProjekta');
            if (naziv && naziv.value.trim().length < 3) {
                validno = false;
                poruke.push('Naziv projekta mora imati najmanje 3 karaktera.');
                naziv.style.borderColor = 'red';
            } else if (naziv) {
                naziv.style.borderColor = '';
            }

            // Ime testera
            const tester = document.getElementById('imeTestera');
            if (tester && tester.value.trim().length < 2) {
                validno = false;
                poruke.push('Ime testera mora imati najmanje 2 karaktera.');
                tester.style.borderColor = 'red';
            } else if (tester) {
                tester.style.borderColor = '';
            }

            // Provera da li postoji bar jedan slučaj sa opisom
            const opisi = document.querySelectorAll('input[name="opis[]"]');
            let imaSlucajeva = false;
            opisi.forEach(function(input) {
                if (input.value.trim().length > 0) {
                    imaSlucajeva = true;
                }
            });

            if (!imaSlucajeva) {
                validno = false;
                poruke.push('Morate dodati bar jedan test slučaj sa opisom.');
            }

            if (!validno) {
                e.preventDefault();
                prikaziGreske(poruke);
            }
        });
    }

    // Funkcija za prikazivanje grešaka
    function prikaziGreske(poruke) {
        // Ukloni postojeće greške
        const postojece = document.querySelectorAll('.validacija-greska');
        postojece.forEach(function(el) {
            el.remove();
        });

        // Kreiraj div za greške
        const div = document.createElement('div');
        div.className = 'poruka-greska validacija-greska';
        div.style.marginBottom = '20px';
        div.style.padding = '15px';
        div.style.borderRadius = '8px';
        div.style.background = '#f8d7da';
        div.style.color = '#721c24';
        div.style.border = '1px solid #f5c6cb';

        let html = '<strong>Greške u unosu:</strong><ul style="margin:10px 0 0 20px;">';
        poruke.forEach(function(p) {
            html += '<li>' + p + '</li>';
        });
        html += '</ul>';
        div.innerHTML = html;

        // Ubaci na vrh forme
        const forma = document.querySelector('.forma');
        if (forma) {
            forma.insertBefore(div, forma.firstChild);
        }

        // Skroluj do grešaka
        div.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    // Automatsko uklanjanje crvenog okvira na fokus
    document.querySelectorAll('input, select, textarea').forEach(function(el) {
        el.addEventListener('focus', function() {
            this.style.borderColor = '';
        });
    });

});