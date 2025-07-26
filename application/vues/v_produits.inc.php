<section class="products-section">
    <div class="section-header">
        <h2 class="section-title">
            <i class="fas fa-shopping-bag"></i>
            Nos produits et services
        </h2>
        <p class="section-subtitle">Découvrez notre gamme complète de soins capillaires professionnels</p>
    </div>

    <div class="products-container">
        <?php if (!empty(VariablesGlobales::$lesProduits)) { ?>
            <?php foreach (VariablesGlobales::$lesProduits as $index => $produit) { ?>
                <div class="product-card" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                    <div class="product-header">
                        <div class="product-icon">
                            <i class="fas fa-cut"></i>
                        </div>
                        <h3 class="product-name"><?php echo htmlspecialchars($produit->nomProduit); ?></h3>
                    </div>
                    
                    <div class="product-content">
                        <div class="product-price">
                            <span class="price-amount"><?php echo $produit->prix; ?>€</span>
                            <span class="price-unit">par <?php echo htmlspecialchars($produit->unite); ?></span>
                        </div>
                        
                        <div class="product-actions">
                            <button class="btn-secondary product-btn">
                                <i class="fas fa-info-circle"></i>
                                En savoir plus
                            </button>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="no-products">
                <i class="fas fa-shopping-basket"></i>
                <h3>Aucun produit disponible</h3>
                <p>Notre catalogue de produits sera bientôt disponible.</p>
            </div>
        <?php } ?>
    </div>
</section>

<style>
.products-section {
    padding: var(--spacing-xl) 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.products-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: var(--spacing-lg);
    margin-top: var(--spacing-xl);
}

.product-card {
    background: white;
    border-radius: var(--border-radius-lg);
    padding: var(--spacing-lg);
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border-color);
    transition: var(--transition-smooth);
    position: relative;
    overflow: hidden;
}

.product-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--gradient-primary);
    transform: scaleX(0);
    transition: var(--transition-smooth);
    transform-origin: left;
}

.product-card:hover::before {
    transform: scaleX(1);
}

.product-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-xl);
}

.product-header {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
    margin-bottom: var(--spacing-lg);
    padding-bottom: var(--spacing-md);
    border-bottom: 2px solid var(--surface-color);
}

.product-icon {
    width: 50px;
    height: 50px;
    background: var(--gradient-primary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.product-name {
    color: var(--primary-color);
    font-size: 1.3rem;
    font-weight: 600;
    margin: 0;
    font-family: var(--font-heading);
    flex: 1;
}

.product-content {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
}

.product-price {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: var(--spacing-md);
    background: var(--surface-color);
    border-radius: var(--border-radius-sm);
}

.price-amount {
    font-size: 2rem;
    font-weight: 700;
    color: var(--primary-color);
    font-family: var(--font-heading);
}

.price-unit {
    color: var(--text-light);
    font-size: 0.9rem;
    margin-top: var(--spacing-xs);
}

.product-actions {
    display: flex;
    justify-content: center;
}

.product-btn {
    padding: var(--spacing-sm) var(--spacing-lg);
    border: none;
    border-radius: var(--border-radius-sm);
    background: var(--gradient-secondary);
    color: white;
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition-smooth);
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
}

.product-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.no-products {
    grid-column: 1 / -1;
    text-align: center;
    padding: var(--spacing-xl);
    background: white;
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-md);
}

.no-products i {
    font-size: 3rem;
    color: var(--secondary-color);
    margin-bottom: var(--spacing-md);
}

.no-products h3 {
    color: var(--text-color);
    margin-bottom: var(--spacing-sm);
}

.no-products p {
    color: var(--text-light);
}

@media (max-width: 768px) {
    .products-container {
        grid-template-columns: 1fr;
        gap: var(--spacing-md);
    }
    
    .product-header {
        flex-direction: column;
        text-align: center;
    }
    
    .product-icon {
        margin: 0 auto;
    }
}
</style>
