<?php

namespace app\assets;

use yii\web\AssetBundle;

class MultiselectAsset extends AssetBundle
{ 
    public $sourcePath = '@vendor/tree-multiselect'; 
    public $css = [
        'dist/jquery.tree-multiselect.min.css'
    ];
    public $js = [
        'dist/jquery.tree-multiselect.min.js'
    ];
}