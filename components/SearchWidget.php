<?php
namespace app\components;

use app\models\Forms\SearchForm;
use yii\base\Widget;

/**
 * Виджет для поиска
 */
class SearchWidget extends Widget {

    public function run() {
        $modelSearch = new SearchForm();
        return $this->render('search_form',['modelSearch'=>$modelSearch]);
    }

}
