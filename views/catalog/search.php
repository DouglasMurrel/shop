<?php
/*
 * Страница результатов поиска по каталогу, файл views/catalog/search.php
 */

use app\components\ProductWidget;
use app\components\SearchWidget;
use app\components\TreeWidget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap4\LinkPager;
use yii\widgets\ActiveForm;

$this->params['breadcrumbs'] = [['label'=>'','template'=>'']];

if(isset($name) && $name!='')$this->title = "Результаты поиска";
if(isset($description) && $description!='')$this->registerMetaTag(['name' => 'description','content' => $description],'description');
if(isset($keywords) && $keywords!='')$this->registerMetaTag(['name' => 'keywords','content' => $keywords],'keywords');
?>

<?= SearchWidget::widget(); ?>

<div class="col-sm-12">
    <?php if (!empty($products)): ?>
        <h2>Результаты поиска по каталогу</h2>
        <?= ProductWidget::widget(['products'=>$products,'pages'=>$pages,'basketForm'=>$basketForm]); ?>
    <?php else: ?>
        <p>По вашему запросу ничего не найдено.</p>
    <?php endif; ?>
</div>
