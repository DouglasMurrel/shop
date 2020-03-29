<?php

use app\modules\admin\components\CategoryWidget;

echo CategoryWidget::widget(['category'=>$category,'tree'=>$tree]);