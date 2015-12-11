<?php
use dosamigos\fileupload\FileUploadUI;
use yii\widgets\LinkPager;
use evgeniyrru\yii2slick\Slick;
/* @var $this yii\web\View */
$this->title = 'Наш склад счастья';
$items=[];
foreach ($image as $item){
    $items=array_merge($items,['<img src="image/medium/'.$item->name.'">']);
}
//$items=['<img src="image/thumbnail/'.$image[0]->name.'">'];
?>
<div class="site-index">

    
<?= LinkPager::widget(['pagination'=>$pagination]) ?>
    
<?=Slick::widget([
 
        // HTML tag for container. Div is default.
        'itemContainer' => 'div',
 
        // HTML attributes for widget container
        'containerOptions' => ['class' => 'site-content'],
 
        // Items for carousel. Empty array not allowed, exception will be throw, if empty 
        'items' => $items,
 
        // HTML attribute for every carousel item
        'itemOptions' => ['style' => 'border:1px'],
 
        // settings for js plugin
        // @see http://kenwheeler.github.io/slick/#settings
        'clientOptions' => [
            'infinite'=>true,
            'fade'=> true,
            'centerMode'=>true,
            'cssEase'=> 'linear',
            'adaptiveHeight'=>true
            ]
            // note, that for params passing function you should use JsExpression object
            //'onAfterChange' => new JsExpression('function() {console.log("The cat has shown")}'),
 
    ]); ?>

 <?php 
     yii\bootstrap\Modal::begin([
    'header' => '<h2>Hello world</h2>',
    'toggleButton' => ['label' => 'click me'],
    ]);

echo 'Say hello...';

yii\bootstrap\Modal::end();
?>

</div>

