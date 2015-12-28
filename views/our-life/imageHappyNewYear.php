<?php
use evgeniyrru\yii2slick\Slick;
use yii\helpers\Html;
use yii\web\JsExpression;
use app\assets\AppAssetSurprise;

/* @var $this yii\web\View */
$this->title = 'С Новым годом!';
AppAssetSurprise::register($this);
$items=[];
$itemsThumb=[];
foreach ($image as $item){
    $itemsThumb=array_merge($itemsThumb,['<img src="image/thumbnail/'.$item->name.'">']);
}
foreach ($image as $item){
    $items=array_merge($items,['<img src="image/medium/'.$item->name.'">']);
}


?>
<div class="surprise">
<div class="site-index">  
<?php

$slick1= Slick::widget([
 
        // HTML tag for container. Div is default.
        'itemContainer' => 'div',
 
        // HTML attributes for widget container
        'containerOptions' => ['class' => 'site-content'],
 
        // Items for carousel. Empty array not allowed, exception will be throw, if empty 
        'items' => $items,
 
        // HTML attribute for every carousel item
        'itemOptions' => ['style' => 'border:0px'],
 
        // settings for js plugin
        // @see http://kenwheeler.github.io/slick/#settings
        'clientOptions' => [
            'autoplay'=>true,
            'autoplaySpeed'=>3000,
            'infinite'=>true,
            'slidesToShow'=>1,
            'slidesToScroll'=>1,
            'fade'=> true,
            'speed'=> 1000,
            'arrows'=>false
           // 'onBeforeChange'=>new JsExpression('function(event, slick, currentSlide, nextSlide) { alert(123333) }'),
            // note, that for params passing function you should use JsExpression object
            ],
    ]);  

 


   
  echo $slick1; 


$this->registerJs("
snowStorm.followMouse = true;
snowStorm.animationInterval = 10;
snowStorm.flakesMax = 15;
snowStorm.freezeOnBlur = true;

");
   

?>
<audio autoplay preload > 
    <source src="music/1.mp3"></source> 
</audio>

</div>
</div>
