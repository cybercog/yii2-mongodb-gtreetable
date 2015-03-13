<?php

/**
* @link https://github.com/songlipeng2003/yii2-mongodb-gtreetable
* @copyright Copyright (c) 2015 Thinking Song
* @license https://github.com/songlipeng2003/yii2-mongodb-gtreetable/blob/master/LICENSE
*/

namespace songlipeng2003\gtreetable\assets;

class BrowserAsset extends \yii\web\AssetBundle {

    /**
     * @inheritdoc
     */
    public $sourcePath = '@bower/jquery.browser/dist';

    /**
     * @inheritdoc
     */
    public $js = [
        'jquery.browser.min.js'
    ];
    
    public $depends = [
        'yii\web\JqueryAsset',
    ];    

}
