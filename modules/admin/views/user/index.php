<?php

use app\models\DB\User;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\bootstrap4\LinkPager;
use yii\helpers\Url;

if (!empty($users)) {
    ?>
    <div>
        <?
        foreach ($users as $user) {
            ?>
            <p>
                <a href="<?= Url::to(['user/user', 'id' => $user['id']]); ?>">
                    <?= Html::encode($user['email']); ?>
                </a>
                <?= $user['name']; ?>
                <?= $user['phone']; ?>
                <?
                $admin = User::isAdminById($user['id']);
                print Html::checkbox('admin[]',$admin,['value'=>$user['id'],'label'=>'Админ','class'=>'admin_checkbox']);
                ?>
            </p>
            <?php
        }
        ?>
    </div>
    <div class="ml-n3">
        <?= LinkPager::widget(['pagination' => $pages,'lastPageLabel'=>true,'firstPageLabel'=>true,'maxButtonCount'=>4]); /* постраничная навигация */ ?>
    </div>
    <?php
} else {
    echo '<p>Список заказов пуст</p>';
}