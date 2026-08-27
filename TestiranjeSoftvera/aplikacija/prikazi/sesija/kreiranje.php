<div class="forma-sekcija">
    <h2>Kreiranje nove test sesije</h2>
    
    <form action="<?php echo BASE_URL; ?>sesije" method="POST" class="forma" id="sesijaForma">
        <!-- Podaci o sesiji -->
        <div class="grupa-polja">
            <h3>Podaci o sesiji</h3>
            
            <div class="polje">
                <label for="nazivProjekta">Naziv projekta <span class="obavezno">*</span></label>
                <input type="text" id="nazivProjekta" name="nazivProjekta" required>
            </div>

            <div class="polje">
                <label for="verzija">Verzija softvera</label>
                <input type="text" id="verzija" name="verzija">
            </div>

            <div class="polje">
                <label for="imeTestera">Ime testera <span class="obavezno">*</span></label>
                <input type="text" id="imeTestera" name="imeTestera" required>
            </div>

            <div class="polje">
                <label for="okruzenje">Okruženje (browser/OS)</label>
                <input type="text" id="okruzenje" name="okruzenje">
            </div>

            <div class="polje">
                <label for="komentar">Komentar</label>
                <textarea id="komentar" name="komentar" rows="3"></textarea>
            </div>
        </div>

        <!-- Stavke - test slučajevi -->
        <div class="grupa-polja">
            <h3>Test slučajevi</h3>
            
            <table class="stavke-tabela" id="tabelaSlucajeva">
                <thead>
                    <tr>
                        <th>Rb.</th>
                        <th>Opis <span class="obavezno">*</span></th>
                        <th>Očekivani rezultat</th>
                        <th>Stvarni rezultat</th>
                        <th>Status</th>
                        <th>Komentar</th>
                        <th>Akcija</th>
                    </tr>
                </thead>
                <tbody id="teloSlucajeva">
                    <tr class="red-slucaja">
                        <td class="rb">1</td>
                        <td><input type="text" name="opis[]" required></td>
                        <td><input type="text" name="ocekivani[]"></td>
                        <td><input type="text" name="stvarni[]"></td>
                        <td>
                            <select name="statusId[]">
                                <?php foreach($statusi as $status): ?>
                                    <option value="<?php echo $status->getStatusId(); ?>">
                                        <?php echo htmlspecialchars($status->getNaziv()); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td><input type="text" name="komentarSlucaja[]"></td>
                        <td>
                            <button type="button" class="btn-obrisi-stavku" onclick="obrisiRed(this)">Obriši</button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <button type="button" class="btn-dodaj-stavku" id="dodajSlucaj">+ Dodaj slučaj</button>
        </div>

        <div class="dugmad-akcije">
            <button type="submit" class="btn-primary">Sačuvaj sesiju</button>
            <a href="<?php echo BASE_URL; ?>sesije" class="btn-otkazi">Otkaži</a>
        </div>
    </form>
</div>

<?php 
$potrebneSkripte = ['slucajAjax.js', 'validacija.js']; 
?>