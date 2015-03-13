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
use yii\db\Exception;
use yii\helpers\Json;
use yii\helpers\Html;
use songlipeng2003\gtreetable\models\TreeModel;

class NodeCreateAction extends ModifyAction {

    public function run() {
        $model = new $this->treeModelName();
        $model->scenario = 'create';
        $model->load(Yii::$app->request->post(), '');

        if (!$model->validate()) {
            throw new HttpException(500, current(current($model->getErrors())));
        }

        $isRootNode = !(integer) $model->parent > 0;
        if (!$isRootNode && !($model->relatedNode instanceof $this->treeModelName)) {
            throw new NotFoundHttpException(Yii::t('gtreetable', 'Position indicated by related ID is not exists!'));
        }

        try {
            if (is_callable($this->beforeAction)) {
                call_user_func_array($this->beforeAction,['model' => $model]);
            }
            
            $action = $isRootNode ? 'makeRoot' : $this->getInsertAction($model);
            if (!call_user_func(array($model, $action), $model->relatedNode)) {
                throw new Exception(Yii::t('gtreetable', 'Adding operation `{name}` failed!', ['{name}' => Html::encode((string) $model)]));
            }
            
            if (is_callable($this->afterAction)) {
                call_user_func_array($this->afterAction,['model' => $model]);
            }             
            
            echo Json::encode([
                '_id' => $model->getPrimaryKey()->__toString(),
                'name' => $model->getName(),
                'level' => $model->getDepth(),
                'type' => $model->getType()
            ]);
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    protected function getInsertAction($model) {
        if ($model->position === TreeModel::POSITION_BEFORE) {
            return 'insertBefore';
        } else if ($model->position === TreeModel::POSITION_AFTER) {
            return 'insertAfter';
        } else if ($model->position === TreeModel::POSITION_FIRST_CHILD) {
            return 'prependTo';
        } else if ($model->position === TreeModel::POSITION_LAST_CHILD) {
            return 'appendTo';
        } else {
            throw new HttpException(500, Yii::t('gtreetable', 'Unsupported insert position!'));
        }
    }

}