<?php
/*
 * Файл components/views/brands.php
 */
use yii\helpers\Html;
use yii\helpers\Url;
?>

<ul class="list-unstyled">
    <?php foreach ($brands as $brand): ?>
        <li>
            <a href="<?= Url::to(['catalog/brand', 'slug' => $brand['slug']]); ?>">
                <span class="badge badge-secondary float-right mt-1"><?= $brand['count']; ?></span>
                <?= Html::encode($brand['name']); ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>