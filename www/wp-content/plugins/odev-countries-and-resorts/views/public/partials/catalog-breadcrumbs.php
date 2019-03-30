<?php
/**
 * Created by PhpStorm.
 * User: aleksandrfishchenko
 * Date: 26.09.16
 * Time: 17:04
 */
?>

<?php $crumbs = ODevCatalogManager::getCatalogPageBreadcrumbs(); ?>

<ol class="breadcrumb">
    <?php foreach ( $crumbs as $index => $crumb ): ?>
        <li>
            <?php if( $index === count( $crumbs ) - 1 || $index === 0 ): ?>
                <span>
                    <?php echo $crumb['label']; ?>
                </span>
            <?php else: ?>
                <a href = "<?php echo $crumb['link']; ?>" >
                    <?php echo $crumb['label']; ?>
                </a>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ol>
