<section class="informations-section">
    <div class="section-header">
        <h2 class="section-title">
            <i class="fas fa-info-circle"></i>
            Informations importantes
        </h2>
        <p class="section-subtitle">Restez informé de toutes nos actualités et informations utiles</p>
    </div>

    <div class="informations-container">
        <?php if (!empty(VariablesGlobales::$lesInformations)) { ?>
            <?php foreach (VariablesGlobales::$lesInformations as $index => $information) { ?>
                <article class="information-card" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                    <div class="information-header">
                        <div class="information-icon">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <h3 class="information-title"><?php echo htmlspecialchars($information->titreInformation); ?></h3>
                    </div>
                    
                    <div class="information-content">
                        <p class="information-text"><?php echo nl2br(htmlspecialchars($information->libelleInformation)); ?></p>
                    </div>
                    
                    <div class="information-footer">
                        <div class="information-badge">
                            <i class="fas fa-clock"></i>
                            Information importante
                        </div>
                    </div>
                </article>
            <?php } ?>
        <?php } else { ?>
            <div class="no-informations">
                <i class="fas fa-newspaper"></i>
                <h3>Aucune information disponible</h3>
                <p>Les informations importantes seront affichées ici dès qu'elles seront disponibles.</p>
            </div>
        <?php } ?>
    </div>
</section>

<style>
.informations-section {
    padding: var(--spacing-xl) 0;
    background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
}

.informations-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: var(--spacing-lg);
    margin-top: var(--spacing-xl);
}

.information-card {
    background: white;
    border-radius: var(--border-radius-lg);
    padding: var(--spacing-lg);
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border-color);
    transition: var(--transition-smooth);
    position: relative;
    overflow: hidden;
}

.information-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: var(--gradient-primary);
    transform: scaleY(0);
    transition: var(--transition-smooth);
    transform-origin: top;
}

.information-card:hover::before {
    transform: scaleY(1);
}

.information-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-xl);
}

.information-header {
    display: flex;
    align-items: flex-start;
    gap: var(--spacing-md);
    margin-bottom: var(--spacing-lg);
    padding-bottom: var(--spacing-md);
    border-bottom: 2px solid var(--surface-color);
}

.information-icon {
    width: 45px;
    height: 45px;
    background: var(--gradient-primary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.3rem;
    flex-shrink: 0;
}

.information-title {
    color: var(--primary-color);
    font-size: 1.4rem;
    font-weight: 600;
    margin: 0;
    font-family: var(--font-heading);
    line-height: 1.3;
    flex: 1;
}

.information-content {
    margin-bottom: var(--spacing-lg);
}

.information-text {
    color: var(--text-color);
    line-height: 1.6;
    margin: 0;
    font-size: 1rem;
}

.information-footer {
    display: flex;
    justify-content: flex-end;
}

.information-badge {
    background: rgba(78, 115, 223, 0.1);
    color: var(--primary-color);
    padding: var(--spacing-xs) var(--spacing-sm);
    border-radius: var(--border-radius-sm);
    font-size: 0.9rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
}

.information-badge i {
    font-size: 0.8rem;
}

.no-informations {
    grid-column: 1 / -1;
    text-align: center;
    padding: var(--spacing-xl);
    background: white;
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-md);
}

.no-informations i {
    font-size: 3rem;
    color: var(--secondary-color);
    margin-bottom: var(--spacing-md);
}

.no-informations h3 {
    color: var(--text-color);
    margin-bottom: var(--spacing-sm);
}

.no-informations p {
    color: var(--text-light);
}

@media (max-width: 768px) {
    .informations-container {
        grid-template-columns: 1fr;
        gap: var(--spacing-md);
    }
    
    .information-header {
        flex-direction: column;
        text-align: center;
    }
    
    .information-icon {
        margin: 0 auto;
    }
    
    .information-title {
        text-align: center;
    }
}
</style>

