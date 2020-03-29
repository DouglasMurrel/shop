<?php

use app\modules\admin\components\ProductWidget;

echo ProductWidget::widget(['product'=>$product,'tree'=>$tree]);