<?php

use yii\helpers\Html;
use yii\helpers\Url;
?>

    <div class="tree">
        <ul class="pl-0">
            <li>
                <div id="Web" class="collapse show root-tree">
                    <ul>
                        <?php foreach ($tree as $item): ?>
                            <?php show_branch($item, $openCategories, $slug)?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </li>
        </ul>
    </div>

<?php
function show_branch($item, $openCategories, $slug){
    $show_flag = false;
    if(in_array($item['id'],$openCategories))$show_flag = true;
    $img = '/images/category/'.$item['image'];
    $file = Yii::getAlias('@webroot').$img;
    if(!file_exists($file) || $item['image']=='')$img = '/images/noimage.jpg';
    if (isset($item['nodes'])){
        ?>
        <li<?if($item['slug']==$slug){?> class="leaf_current"<?}?>>
        <span style="white-space:nowrap;min-width:100%;min-height:40px;">
             <a style="color:#000; text-decoration:none;" data-toggle="collapse" href="#<?=$item['slug']?>" aria-expanded="<?= $show_flag?'true':'false'?>" aria-controls="<?=$item['slug']?>">
                  <i class="collapsed">
                      <i class="fas fa-folder"></i>
                  </i>
                  <i class="expanded">
                      <i class="far fa-folder-open"></i>
                  </i>
             </a>
             <a href="<?=Url::to(['catalog/category','slug'=>$item['slug']])?>"><?=$item['name']?></a>
        </span>
            <ul>
                <div id="<?=$item['slug']?>" class="collapse<?= $show_flag?' show':''?>">
                    <?php foreach ($item['nodes'] as $item1):
                        ?>
                        <?php show_branch($item1,$openCategories,$slug); ?>
                    <?php endforeach; ?>
                </div>
            </ul>
        </li>
        <?
    }else{
        ?>
        <li<?if($item['slug']==$slug){?> class="leaf_current"<?}?>>
            <span style="min-width:100%;min-height:40px;">
                <i class="far"></i>
                <a class="w-100" href="<?=Url::to(['catalog/category','slug'=>$item['slug']])?>"><?=$item['name']?></a>
            </span>
        </li>
        <?
    }
}
?>