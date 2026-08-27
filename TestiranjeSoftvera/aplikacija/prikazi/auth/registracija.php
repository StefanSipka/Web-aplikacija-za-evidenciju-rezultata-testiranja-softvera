<div class="forma-sekcija">
    <h2>Registracija korisnika</h2>
    <form action="<?php echo BASE_URL; ?>registracija" method="POST" class="forma" id="registracijaForma">
        <div class="polje">
            <label for="ime">Ime:</label>
            <input type="text" id="ime" name="ime" required minlength="2">
        </div>

        <div class="polje">
            <label for="prezime">Prezime:</label>
            <input type="text" id="prezime" name="prezime" required minlength="2">
        </div>

        <div class="polje">
            <label for="email">Email adresa:</label>
            <input type="email" id="email" name="email" required placeholder="unestite@email.com">
        </div>

        <div class="polje">
            <label for="lozinka">Lozinka (min 6 karaktera):</label>
            <input type="password" id="lozinka" name="lozinka" required minlength="6">
        </div>

        <div class="polje">
            <label for="lozinkaPotvrda">Potvrdite lozinku:</label>
            <input type="password" id="lozinkaPotvrda" name="lozinkaPotvrda" required>
        </div>

        <button type="submit" class="btn-primary">Registruj se</button>
        
        <div class="registracija-link">
    Već imate nalog? <a href="<?php echo BASE_URL; ?>prijava">Prijavite se</a>
</div>
    </form>
</div>

<?php 
$potrebneSkripte = ['validacija.js']; 
?>