<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\DB\Product;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";

        return ExitCode::OK;
    }

    public function actionAddproducts(){
        for ($i=100;$i<1000;$i++){
//            echo "$i\n";
            $product = new Product();
            $product->name = "Товар $i";
            $product->slug = "tovar$i";
            $product->category_id = 2;
            $product->brand_id = 1;
            $product->content = "Описание $i";
            $product->price = $i;
            $product->keywords = "keyword$i";
            $product->description = "description$i";
            $product->hit = 1;
            $product->new = 1;
            $product->sale = 1;
            $product->save();
        }
        return ExitCode::OK;
    }
}
