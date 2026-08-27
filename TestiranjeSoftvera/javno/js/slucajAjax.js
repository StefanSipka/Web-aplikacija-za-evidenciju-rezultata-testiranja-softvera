/**
 * slucajAjax.js
 * Dinamičko dodavanje i uklanjanje redova u tabeli (master-detail)
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Dugme za dodavanje novog slučaja
    const dugmeDodaj = document.getElementById('dodajSlucaj');
    if (dugmeDodaj) {
        dugmeDodaj.addEventListener('click', function() {
            dodajRed();
        });
    }

    // Funkcija za dodavanje novog reda
    function dodajRed() {
        const tbody = document.getElementById('teloSlucajeva');
        if (!tbody) return;

        const brojRedova = tbody.children.length;
        const noviRed = document.createElement('tr');
        noviRed.className = 'red-slucaja';

        // Redni broj
        const rbCelija = document.createElement('td');
        rbCelija.className = 'rb';
        rbCelija.textContent = brojRedova + 1;
        noviRed.appendChild(rbCelija);

        // Opis (sa skrivenim inputom za ID ako je izmena)
        const opisCelija = document.createElement('td');
        const skriveniInput = document.createElement('input');
        skriveniInput.type = 'hidden';
        skriveniInput.name = 'slucajId[]';
        skriveniInput.value = '';
        opisCelija.appendChild(skriveniInput);
        
        const opisInput = document.createElement('input');
        opisInput.type = 'text';
        opisInput.name = 'opis[]';
        opisInput.required = true;
        opisCelija.appendChild(opisInput);
        noviRed.appendChild(opisCelija);

        // Očekivani rezultat
        const ocekivaniCelija = document.createElement('td');
        const ocekivaniInput = document.createElement('input');
        ocekivaniInput.type = 'text';
        ocekivaniInput.name = 'ocekivani[]';
        ocekivaniCelija.appendChild(ocekivaniInput);
        noviRed.appendChild(ocekivaniCelija);

        // Stvarni rezultat
        const stvarniCelija = document.createElement('td');
        const stvarniInput = document.createElement('input');
        stvarniInput.type = 'text';
        stvarniInput.name = 'stvarni[]';
        stvarniCelija.appendChild(stvarniInput);
        noviRed.appendChild(stvarniCelija);

        // Status (select)
        const statusCelija = document.createElement('td');
        const statusSelect = document.createElement('select');
        statusSelect.name = 'statusId[]';
        // Preuzimanje opcija iz prvog selecta (ako postoji)
        const prviSelect = tbody.querySelector('select[name="statusId[]"]');
        if (prviSelect) {
            const opcije = prviSelect.options;
            for (let i = 0; i < opcije.length; i++) {
                const op = document.createElement('option');
                op.value = opcije[i].value;
                op.textContent = opcije[i].textContent;
                statusSelect.appendChild(op);
            }
        }
        statusCelija.appendChild(statusSelect);
        noviRed.appendChild(statusCelija);

        // Komentar
        const komentarCelija = document.createElement('td');
        const komentarInput = document.createElement('input');
        komentarInput.type = 'text';
        komentarInput.name = 'komentarSlucaja[]';
        komentarCelija.appendChild(komentarInput);
        noviRed.appendChild(komentarCelija);

        // Akcija - dugme za brisanje
        const akcijaCelija = document.createElement('td');
        const dugmeObrisi = document.createElement('button');
        dugmeObrisi.type = 'button';
        dugmeObrisi.className = 'btn-obrisi-stavku';
        dugmeObrisi.textContent = 'Obriši';
        dugmeObrisi.addEventListener('click', function() {
            obrisiRed(this);
        });
        akcijaCelija.appendChild(dugmeObrisi);
        noviRed.appendChild(akcijaCelija);

        tbody.appendChild(noviRed);
        azurirajRedneBrojeve();
    }

    // Funkcija za brisanje reda
    window.obrisiRed = function(dugme) {
        const red = dugme.closest('tr');
        if (!red) return;

        // Provera da li je poslednji red (ne dozvoljavamo brisanje poslednjeg)
        const tbody = document.getElementById('teloSlucajeva');
        if (tbody && tbody.children.length <= 1) {
            alert('Morate imati bar jedan test slučaj.');
            return;
        }

        if (red) {
            red.remove();
            azurirajRedneBrojeve();
        }
    };

    // Ažuriranje rednih brojeva
    function azurirajRedneBrojeve() {
        const tbody = document.getElementById('teloSlucajeva');
        if (!tbody) return;

        const redovi = tbody.children;
        for (let i = 0; i < redovi.length; i++) {
            const rbCelija = redovi[i].querySelector('.rb');
            if (rbCelija) {
                rbCelija.textContent = i + 1;
            }
        }
    }

    // Dodavanje event listenera za postojeće dugmiće za brisanje
    document.querySelectorAll('.btn-obrisi-stavku').forEach(function(dugme) {
        dugme.addEventListener('click', function() {
            obrisiRed(this);
        });
    });

});