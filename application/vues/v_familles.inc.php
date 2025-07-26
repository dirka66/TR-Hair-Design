<section class="categories-section">
    <div class="section-header">
        <h2 class="section-title">
            <i class="fas fa-tags"></i>
            Nos catégories de services
        </h2>
        <p class="section-subtitle">Explorez notre gamme complète de prestations professionnelles</p>
    </div>

    <div class="categories-container">
        <?php if (!empty($familles)) { ?>
            <?php foreach ($familles as $index => $famille): ?>
                <div class="category-card" data-aos="zoom-in" data-aos-delay="<?php echo $index * 150; ?>">
                    <div class="category-icon">
                        <i class="fas fa-scissors"></i>
                    </div>
                    <h3 class="category-name"><?php echo htmlspecialchars($famille['nomFamille']); ?></h3>
                    <div class="category-overlay">
                        <button class="btn-primary category-btn">
                            <i class="fas fa-arrow-right"></i>
                            Découvrir
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php } else { ?>
            <div class="no-categories">
                <i class="fas fa-list-alt"></i>
                <h3>Aucune catégorie disponible</h3>
                <p>Nos catégories de services seront bientôt disponibles.</p>
            </div>
        <?php } ?>
    </div>
</section>

<style>
.categories-section {
    padding: var(--spacing-xl) 0;
    background: white;
}

.categories-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: var(--spacing-lg);
    margin-top: var(--spacing-xl);
}

.category-card {
    background: linear-gradient(145deg, #ffffff 0%, #f8f9fa 100%);
    border-radius: var(--border-radius-lg);
    padding: var(--spacing-xl);
    text-align: center;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border-color);
    position: relative;
    overflow: hidden;
    transition: var(--transition-smooth);
    cursor: pointer;
}

.category-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: var(--gradient-primary);
    opacity: 0;
    transition: var(--transition-smooth);
    z-index: 1;
}

.category-card:hover::before {
    opacity: 0.1;
}

.category-card:hover {
    transform: translateY(-10px);
    box-shadow: var(--shadow-xl);
}

.category-card:hover .category-overlay {
    opacity: 1;
    visibility: visible;
}

.category-card:hover .category-name {
    color: var(--primary-color);
}

.category-icon {
    width: 80px;
    height: 80px;
    background: var(--gradient-primary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto var(--spacing-lg);
    color: white;
    font-size: 2rem;
    position: relative;
    z-index: 2;
    transition: var(--transition-smooth);
}

.category-card:hover .category-icon {
    transform: scale(1.1);
}

.category-name {
    color: var(--text-color);
    font-size: 1.4rem;
    font-weight: 600;
    margin: 0;
    font-family: var(--font-heading);
    position: relative;
    z-index: 2;
    transition: var(--transition-smooth);
}

.category-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    visibility: hidden;
    transition: var(--transition-smooth);
    z-index: 3;
}

.category-btn {
    padding: var(--spacing-sm) var(--spacing-lg);
    border: none;
    border-radius: var(--border-radius-sm);
    background: white;
    color: var(--primary-color);
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition-smooth);
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
    transform: translateY(20px);
}

.category-card:hover .category-btn {
    transform: translateY(0);
}

.category-btn:hover {
    background: var(--primary-color);
    color: white;
    transform: scale(1.05);
}

.no-categories {
    grid-column: 1 / -1;
    text-align: center;
    padding: var(--spacing-xl);
    background: var(--surface-color);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-md);
}

.no-categories i {
    font-size: 3rem;
    color: var(--secondary-color);
    margin-bottom: var(--spacing-md);
}

.no-categories h3 {
    color: var(--text-color);
    margin-bottom: var(--spacing-sm);
}

.no-categories p {
    color: var(--text-light);
}

@media (max-width: 768px) {
    .categories-container {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: var(--spacing-md);
    }
    
    .category-card {
        padding: var(--spacing-lg);
    }
    
    .category-icon {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }
    
    .category-name {
        font-size: 1.2rem;
    }
}
</style>
