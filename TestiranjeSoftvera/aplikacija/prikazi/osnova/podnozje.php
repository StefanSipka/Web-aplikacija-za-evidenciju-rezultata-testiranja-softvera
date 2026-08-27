    </div> <!-- kraj kontejnera -->

    <footer class="podnozje">
        <div class="kontejner">
            <p>&copy; <?php echo date('Y'); ?> - Evidentiranje rezultata testiranja softvera</p>
            <p>Seminarski rad - Veb programiranje</p>
        </div>
    </footer>

    <!-- Skripte -->
<script src="<?php echo BASE_URL; ?>javno/js/validacija.js"></script>
<?php if(isset($potrebneSkripte)): ?>
    <?php foreach($potrebneSkripte as $skripta): ?>
        <script src="<?php echo BASE_URL; ?>javno/js/<?php echo $skripta; ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>
</body>
</html>