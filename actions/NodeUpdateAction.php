<?php

/**
* @link https://github.com/songlipeng2003/yii2-mongodb-gtreetable
* @copyright Copyright (c) 2015 Thinking Song
* @license https://github.com/songlipeng2003/yii2-mongodb-gtreetable/blob/master/LICENSE
*/

namespace songlipeng2003\gtreetable\actions;

use Yii;
use yii\web\HttpException;
use yii\db\Exception;
use yii\helpers\Html;
use yii\helpers\Json;

class NodeUpdateAction extends ModifyAction {

    public function run($id) {
        $model = $this->getNodeById($id);
        $model->scenario = 'update';
        $model->load(Yii::$app->request->post(), '');

        if (!$model->validate()) {
            throw new HttpException(500, current(current($model->getErrors())));
        }

        try {
            if (is_callable($this->beforeAction)) {
                call_user_func_array($this->beforeAction,['model' => $model]);
            }
            
            if ($model->save(false) === false) {
                throw new Exception(Yii::t('gtreetable', 'Update operation `{name}` failed!', ['{name}' => Html::encode((string) $model)]));
            }

            if (is_callable($this->afterAction)) {
                call_user_func_array($this->afterAction,['model' => $model]);
            }               
            
            echo Json::encode([
                'id' => $model->getPrimaryKey()->__toString(),
                'name' => $model->getName(),
                'level' => $model->getDepth(),
                'type' => $model->getType()
            ]);
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

}

?>