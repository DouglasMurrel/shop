<?php


namespace app\modules\admin\controllers;


use app\models\DB\Color;
use Yii;
use yii\helpers\Url;

class ColorController extends DefaultController
{
    function actionIndex(){
        $allColors = Color::getAll();
        $colors = [];
        $color = new Color();

        return $this->render('index',[
            'color'=>$color,
            'allColors'=>$allColors,
        ]);
    }

    function actionUpdate(){
        $post = Yii::$app->request->post('Color', []);
        $post = array_values($post);
        $count = count($post);
        $colors = [new Color()];
        for($i = 1; $i < $count; $i++) {
            $colors[] = new Color();
        }
        if(Color::loadMultiple($colors, ["Color"=>$post])) {
            $colors = array_values($colors);
            if(Color::validateMultiple($colors)) {
                foreach ($colors as $k => $color) {
                    $color->saveColor();
                }
            }
        }
        return $this->redirect(Url::to(['index']));
    }

    function actionDelete($id){
        Color::findOne($id)->delete();
        return $this->redirect(Url::to(['index']));
    }
}