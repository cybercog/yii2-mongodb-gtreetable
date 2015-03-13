<?php

/**
* @link https://github.com/songlipeng2003/yii2-mongodb-gtreetable
* @copyright Copyright (c) 2015 Thinking Song
* @license https://github.com/songlipeng2003/yii2-mongodb-gtreetable/blob/master/LICENSE
*/

namespace songlipeng2003\gtreetable\models;

use yii\mongodb\ActiveQuery;
use creocoder\nestedsets\NestedSetsQueryBehavior;

class TreeQuery extends ActiveQuery {

    public $nestedSetParams = [];
    
    public function behaviors() {
        return [
            array_merge(['class' => NestedSetsQueryBehavior::className()], $this->nestedSetParams)
        ];
    }

}
