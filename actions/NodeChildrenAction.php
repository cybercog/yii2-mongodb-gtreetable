<?php

/**
* @link https://github.com/songlipeng2003/yii2-mongodb-gtreetable
* @copyright Copyright (c) 2015 Thinking Song
* @license https://github.com/songlipeng2003/yii2-mongodb-gtreetable/blob/master/LICENSE
*/

namespace songlipeng2003\gtreetable\actions;

use Yii;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\helpers\Json;
use yii\validators\NumberValidator;

class NodeChildrenAction extends BaseAction {

    public function run($id) {
        $query = (new $this->treeModelName)->find();
        
        $nodes = [];
        if ($id == 0) {
            $nodes = $query->roots()->all();
        } else {
            $parent = $query->where(['_id' => $id])->one();
            if ($parent === null) {
                throw new NotFoundHttpException(Yii::t('gtreetable', 'Position indicated by parent ID is not exists!'));
            }
            $nodes = $parent->children(1)->all();
        }
        $result = [];
        foreach ($nodes as $node) {
            $result[] = [
                'id' => $node->getPrimaryKey()->__toString(),
                'name' => $node->getName(),
                'level' => $node->getDepth(),
                'type' => $node->getType()
            ];
        }
        echo Json::encode(['nodes' => $result]);
    }

}