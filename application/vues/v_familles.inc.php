?>
<div class="menu-familles">
    <ul>
        <?php foreach ($familles as $famille): ?>
            <li><?php echo htmlspecialchars($famille['nomFamille']); ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php
