<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class DropZone extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $js = [
        '/js/dropzone.js',

    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
}
