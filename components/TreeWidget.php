<?php


namespace app\components;

use yii\base\Widget;
use app\models\DB\Category;
use Yii;

class TreeWidget extends Widget
{

    /**
     * Выборка категорий каталога из базы данных
     */
    protected $data;

    /**
     * Массив категорий каталога в виде дерева
     */
    protected $tree;

    public function run() {
        // сохраняем полученные данные в кеше
        $html = Yii::$app->cache->getOrSet('catalog-menu', function(){
            $this->data = Category::find()->indexBy('id')->asArray()->all();
            $this->makeTree();
            if ( ! empty($this->tree)) {
                $html = $this->render('menu', ['tree' => $this->tree]);
            } else {
                $html = '';
            }
            return $html;
        }, 60);
        return $html;
    }

    /**
     * Функция принимает на вход линейный массив элеменов, связанных
     * отношениями parent-child, и возвращает массив в виде дерева
     */
    protected function makeTree() {
        if (empty($this->data)) {
            return;
        }
        foreach ($this->data as $id => &$node) {
            if ( ! $node['parent_id']) {
                $this->tree[$id] = &$node;
            } else {
                $this->data[$node['parent_id']]['children'][$id] = &$node;
            }
        }
    }
}