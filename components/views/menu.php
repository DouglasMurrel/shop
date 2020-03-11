<?php
/*
 * Файл components/views/menu.php
 */
use yii\helpers\Html;
use yii\helpers\Url;
?>

<ul id="accordion">
    <?php foreach ($tree as $item): ?>
    <?php show_branch($item)?>
    <?php endforeach; ?>
</ul>

<?php
function show_branch($item){
?>
    <li>
        <div class="text-nowrap">
            <?php if (isset($item['children'])): ?>
                <span class="badge pull-left mt-2"><i class="fa fa-plus"></i></span>
            <?php endif; ?>
            <a href="<?= Url::to(['catalog/category', 'slug' => $item['slug']]); ?>" class='text-nowrap'>
                <?= Html::encode($item['name']); ?>
            </a>
        </div>
        <?php if (isset($item['children'])): ?>
            <ul class="pl-3">
                <?php foreach ($item['children'] as $item1): ?>
                    <?php show_branch($item1); ?>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </li>
<?php
}
?>