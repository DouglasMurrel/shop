<?php

use yii\helpers\Html;
use yii\helpers\Url;
?>

    <div class="tree">
        <ul class="pl-0">
            <li>
                <span class="p-0" style="cursor:default;">
                    <span class="border-0" style="cursor:default;">
                        <i class="expanded">
                            <i class="far fa-folder-open"></i>
                        </i>
                    </span>
                </span>
                <div id="Web" class="collapse show">
                    <ul>
                        <?php foreach ($tree as $item): ?>
                            <?php show_branch($item, $openCategories)?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </li>
        </ul>
    </div>

<?php
function show_branch($item, $openCategories){
    $show_flag = false;
    if(in_array($item['id'],$openCategories))$show_flag = true;
    if (isset($item['nodes'])){
        ?>
        <li>
        <span style="white-space:nowrap;">
             <a style="color:#000; text-decoration:none;" data-toggle="<?= $show_flag?'':'collapse'?>" href="#<?=$item['slug']?>" aria-expanded="<?= $show_flag?'true':'false'?>" aria-controls="<?=$item['slug']?>">
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
                    <?php foreach ($item['nodes'] as $item1): ?>
                        <?php show_branch($item1,$openCategories); ?>
                    <?php endforeach; ?>
                </div>
            </ul>
        </li>
        <?
    }else{
        ?>
        <li>
            <span style="white-space:nowrap;">
                <i class="far"></i>
                <a href="<?=Url::to(['catalog/category','slug'=>$item['slug']])?>"><?=$item['name']?></a>
            </span>
        </li>
        <?
    }
}
?>