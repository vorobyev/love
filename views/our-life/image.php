<?php
use dosamigos\fileupload\FileUploadUI;
use yii\widgets\LinkPager;
use evgeniyrru\yii2slick\Slick;
/* @var $this yii\web\View */
$this->title = 'Наш склад счастья';
$items=[];
foreach ($image as $item){
    $items=array_merge($items,['<img src="image/thumbnail/'.$item->name.'">']);
}
?>
<div class="site-index">

    
<?= LinkPager::widget(['pagination'=>$pagination]) ?>
<?= var_dump($image[0]->id) ?>

<?=Slick::widget([
 
        // HTML tag for container. Div is default.
        'itemContainer' => 'div',
 
        // HTML attributes for widget container
        'containerOptions' => ['class' => 'container'],
 
        // Items for carousel. Empty array not allowed, exception will be throw, if empty 
        'items' => $items,
 
        // HTML attribute for every carousel item
        'itemOptions' => ['style' => 'border:1px'],
 
        // settings for js plugin
        // @see http://kenwheeler.github.io/slick/#settings
        'clientOptions' => [
            'centerMode'=>true,
            'slidesToShow'=> 3,
            'slidesToScroll'=> 1
            // note, that for params passing function you should use JsExpression object
            //'onAfterChange' => new JsExpression('function() {console.log("The cat has shown")}'),
        ],
 
    ]); ?>

</div>

