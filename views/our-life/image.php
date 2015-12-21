<?php
use dosamigos\fileupload\FileUploadUI;
use yii\widgets\LinkPager;
use evgeniyrru\yii2slick\Slick;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\web\JsExpression;

/* @var $this yii\web\View */
$this->title = 'Наш склад счастья';
Pjax::begin(['id'=>'myPjax']);
$numb=0;
foreach ($image as $item){ 
    echo Html::a('123',['our-life/view-photo','href'=>$modelFirst[0]->href,'page'=>($pagination->getPage()==0)?1:$pagination->getPage()+1,'href'=>$item->href],['id'=>'href'.$numb]);   
    $numb+=1;
}

    
Pjax::end();


Pjax::begin(['id'=>'myPjax']); 
$items=[];
$itemsThumb=[];
foreach ($image as $item){
    $itemsThumb=array_merge($itemsThumb,[Html::a('<img src="image/thumbnail/'.$item->name.'">',['our-life/view-photo','href'=>$item->href,'page'=>($pagination->getPage()==0)?1:$pagination->getPage()+1])]);
}
foreach ($image as $item){
    $items=array_merge($items,['<img src="image/medium/'.$item->name.'">']);
}

//$items=['<img src="image/thumbnail/'.$image[0]->name.'">'];
?>
<div class="site-index">

     
 
    
<?= LinkPager::widget(['pagination'=>$pagination]) ?>
<?php $link=$pagination->getLinks(); 
    if (!isset($link['next'])){
        echo Html::a('',['our-life/view-photo','href'=>$modelPrev[0]->href,'page'=>$pagination->getPage()],['id'=>'prevPage']);
        echo Html::a('',['our-life/view-photo','href'=>$modelFirst[0]->href,'page'=>1],['id'=>'firstPage']);
                $this->registerJs("
                jQuery(document).ready(function () {
                $('#w0').on('beforeChange',function(event, slick, currentSlide, nextSlide) {
                    if ((currentSlide==".($countQuery%$pagination->limit-1).")&&(nextSlide==0)){                     
                        $('.modal-backdrop').remove();
                        $('#w1').modal('hide');
                        var link = $('#firstPage')[0];
                        var linkEvent = document.createEvent('MouseEvents');
                        
                        linkEvent.initEvent('click', true, true);
                        link.dispatchEvent(linkEvent);
                        e.preventDefault();
                        return false;
                    } else if ((currentSlide==0)&&(nextSlide==".($countQuery%$pagination->limit-1).")) {
                        $('.modal-backdrop').remove();
                        $('#w1').modal('hide');
                        var link = $('#prevPage')[0];
                        var linkEvent = document.createEvent('MouseEvents');
                        
                        linkEvent.initEvent('click', true, true);
                        link.dispatchEvent(linkEvent);
                        e.preventDefault();
                        return false;                       
                    }
                });
                });"                  
                );        
    }
    if (!isset($link['prev'])){
         echo Html::a('',['our-life/view-photo','href'=>$modelLast[0]->href,'page'=>$pagination->pageCount],['id'=>'lastPage']);
         echo Html::a('',['our-life/view-photo','href'=>$modelNext[0]->href,'page'=>$pagination->getPage()+2],['id'=>'nextPage']);
                $this->registerJs("
                jQuery(document).ready(function () {
                $('#w0').on('beforeChange',function(event, slick, currentSlide, nextSlide) {
                if ((currentSlide==0)&&(nextSlide==".($pagination->limit-1).")){
                    $('.modal-backdrop').remove();
                    $('#w1').modal('hide');
                        var link = $('#lastPage')[0];
                        var linkEvent = document.createEvent('MouseEvents');
                        linkEvent.initEvent('click', true, true);
                        link.dispatchEvent(linkEvent);
                        e.preventDefault();
                        return false;
                    } else if ((currentSlide==".($pagination->limit-1).")&&(nextSlide==0)){
                    $('.modal-backdrop').remove();
                    $('#w1').modal('hide');
                        var link = $('#nextPage')[0];
                        var linkEvent = document.createEvent('MouseEvents');
                        linkEvent.initEvent('click', true, true);
                        link.dispatchEvent(linkEvent);
                        e.preventDefault();
                        return false;
                    }
                });
    });" );       
    }
    if ((isset($link['prev']))&&(isset($link['next']))) {
        echo Html::a('',['our-life/view-photo','href'=>$modelNext[0]->href,'page'=>$pagination->getPage()+2],['id'=>'nextPage']);
        echo Html::a('',['our-life/view-photo','href'=>$modelPrev[0]->href,'page'=>$pagination->getPage()],['id'=>'prevPage']);
        $this->registerJs("
                jQuery(document).ready(function () {
                $('#w0').on('beforeChange',function(event, slick, currentSlide, nextSlide) {
                if ((currentSlide==0)&&(nextSlide==".($pagination->limit-1).")){
                    $('.modal-backdrop').remove();
                    $('#w1').modal('hide');
                        var link = $('#prevPage')[0];
                        var linkEvent = document.createEvent('MouseEvents');
                        linkEvent.initEvent('click', true, true);
                        link.dispatchEvent(linkEvent);
                        e.preventDefault();
                        return false;
                } else if ((currentSlide==".($pagination->limit-1).")&&(nextSlide==0)){
                    $('.modal-backdrop').remove();
                    $('#w1').modal('hide');
                        var link = $('#nextPage')[0];
                        var linkEvent = document.createEvent('MouseEvents');
                        linkEvent.initEvent('click', true, true);
                        link.dispatchEvent(linkEvent);
                        e.preventDefault();
                        return false;
                    } else {
                        var link = $('#href'+nextSlide.toString())[0];
                        alert(link);
                        var linkEvent = document.createEvent('MouseEvents');
                        linkEvent.initEvent('click', true, true);
                        link.dispatchEvent(linkEvent);
                        return true;                   
                }
                });
    });" );
    }
    
    echo Html::a('',['our-life/view-photo','page'=>$pagination->getPage()+1],['id'=>'escapeHref']);
    $this->registerJs("
        $('#w1').on('hidden.bs.modal',function (e) {
  var link = $('#escapeHref')[0];
  var linkEvent = document.createEvent('MouseEvents');
  linkEvent.initEvent('click', true, true);
  link.dispatchEvent(linkEvent);
  e.preventDefault();
    });


    ");
    

    

?>   
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
            'variableWidth'=>false,
            'initialSlide'=>$index,
            'useCSS'=>false,
           // 'onBeforeChange'=>new JsExpression('function(event, slick, currentSlide, nextSlide) { alert(123333) }'),
            // note, that for params passing function you should use JsExpression object
            ],
 
 
    ]);  

      yii\bootstrap\Modal::begin([
    'header' => "Просмотр",
    'clientOptions'=> ($href=="") ? ['show'=>false] : ['show'=>true]
    ]); 

  ?> 


 <?php 

    

  echo $slick; 

yii\bootstrap\Modal::end();
    

?>


</div>
<?php
Pjax::end();
?>
