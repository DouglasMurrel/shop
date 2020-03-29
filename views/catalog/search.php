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

$this->params['breadcrumbs'] = [['label'=>'']];

if(isset($name) && $name!='')$this->title = "Результаты поиска";
if(isset($description) && $description!='')$this->registerMetaTag(['name' => 'description','content' => $description],'description');
if(isset($keywords) && $keywords!='')$this->registerMetaTag(['name' => 'keywords','content' => $keywords],'keywords');
?>

<?= SearchWidget::widget(); ?>

<table class="col-sm-9">
    <?php if (!empty($products)): ?>
        <h2>Результаты поиска по каталогу</h2>
        <table>
            <?
            foreach ($products as $product) {
                ?>
                    <tr class="w-100">
                        <td class="w-25">
                            <a href="<?= Url::to(['catalog/product', 'slug' => $product['slug']]); ?>">
                                <?= Html::encode($product['name']); ?>
                            </a>
                        </td>
                        <td class="w-25"><?= $product['corpus']; ?></td>
                        <td class="w-25"><?= $product['price']; ?> руб.</td>
                        <td class="w-25">
                            <?php $form = ActiveForm::begin(['action'=>Url::to(['basket/add']),'options'=>['id'=>'product'.$product['id']]]); ?>
                            <?= $form->field($basketForm, 'id')->hiddenInput(['value'=>$product['id']])->label(false) ?>
                            <?= $form->field($basketForm, 'count',['options'=>['class'=>'w-auto d-inline-block']])->textInput(['value'=>1,'style'=>'width:70px;'])->label(false) ?>
                            <a href='' onclick="$('form#product<?=$product['id']?>').submit();return false;" style="white-space:nowrap;">Добавить в корзину</a>
                            <?php ActiveForm::end(); ?>
                        </td>
                    </tr>
                <?php
            }
            ?>
        </table>
        <div class="ml-n3">
            <?= LinkPager::widget(['pagination' => $pages,'lastPageLabel'=>true,'firstPageLabel'=>true,'maxButtonCount'=>4]); /* постраничная навигация */ ?>
        </div>
    <?php else: ?>
        <p>По вашему запросу ничего не найдено.</p>
    <?php endif; ?>
</div>
