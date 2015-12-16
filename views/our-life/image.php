<?php
use dosamigos\fileupload\FileUploadUI;
use yii\widgets\LinkPager;
use evgeniyrru\yii2slick\Slick;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\web\JsExpression;

/* @var $this yii\web\View */
$this->title = 'Наш склад счастья';
$items=[];
$itemsThumb=[];
foreach ($image as $item){
    $itemsThumb=array_merge($itemsThumb,[Html::a('<img src="image/thumbnail/'.$item->name.'">',['our-life/view-photo','href'=>$item->href])]);
}
foreach ($image as $item){
    $items=array_merge($items,['<img src="image/medium/'.$item->name.'">']);
}

//$items=['<img src="image/thumbnail/'.$image[0]->name.'">'];
?>
<div class="site-index">

     
<?php //Pjax::begin(); ?>  
    
<?= LinkPager::widget(['pagination'=>$pagination]) ?>
    
<?php
$iter=1;
echo "<table class=\"grid\">";
foreach ($itemsThumb as $item){
    if ($iter%9==1) {
        echo "<tr>";
    }
    echo "<td>".$item."</td>";
    
    
    if ($iter%9==0) {
        echo "</tr>";
    }
    $iter+=1;
}
echo "</table>";

$slick= Slick::widget([
 
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
            'adaptiveHeight'=>false,
            'adaptiveWidth'=>true,
            'initialSlide'=>$index,
            'useCSS'=>false,
            // note, that for params passing function you should use JsExpression object
            'onReInit' => new JsExpression('function() {alert(111);}')
            ]
 
    ]);  

      yii\bootstrap\Modal::begin([
    'header' => "Просмотр",
    'clientOptions'=> ($href=="") ? ['show'=>false] : ['show'=>true]
    ]); 

  ?> 


 <?php 

    


  echo $slick; 

yii\bootstrap\Modal::end();
    //Pjax::end();

?>


</div>

