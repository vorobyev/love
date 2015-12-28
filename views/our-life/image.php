<?php
use dosamigos\fileupload\FileUploadUI;
use yii\widgets\LinkPager;
use evgeniyrru\yii2slick\Slick;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\web\JsExpression;

/* @var $this yii\web\View */
$this->title = 'Фотографии';
$this->params['breadcrumbs'][] = $this->title;



Pjax::begin(['id'=>'myInnerPhoto','linkSelector'=>'.pjaxMy']);
$numb=0;
foreach ($image as $item){ 
    echo Html::a('',['our-life/view-photo','href'=>$modelFirst[0]->href,'page'=>($pagination->getPage()==0)?1:$pagination->getPage()+1,'href'=>$item->href],['id'=>'href'.$numb,'class'=>'pjaxMy']);   
    $numb+=1;
}



$this->registerJs("
function dump(obj) {
    var out = \"\";
    if(obj && typeof(obj) == \"object\"){
        for (var i in obj) {
            out += i + \": \" + obj[i] + \" \";
        }
    } else {
        out = obj;
    }
    alert(out);
}
");
    
Pjax::end();


Pjax::begin(['id'=>'myPjax']); 
$iter=1;
foreach ($image as $item){
    echo "<div class=\"notShowHref\" id='metaHref".(string)$iter."'><br/>"
        //. Html::a('Оригинал',"image/".$item->name,['target'=>"_blank",'class'=>'notPjax','id'=>'hrefBlank'.(string)$iter])."</div>";
        . Html::a('Оригинал',['our-life/view-photo','href'=>$item->href,'showOriginal'=>1],['target'=>"_blank",'class'=>'notPjax','id'=>'hrefBlank'.(string)$iter])."</div>";
    $iter+=1;
}


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

     
 
    
<?="<div style='text-align:center'>". LinkPager::widget(['pagination'=>$pagination])."</div>" ?>
<?php $link=$pagination->getLinks(); 
    if (!isset($link['next'])){
        echo Html::a('',['our-life/view-photo','href'=>$modelPrev[0]->href,'page'=>$pagination->getPage()],['id'=>'prevPage']);
        echo Html::a('',['our-life/view-photo','href'=>$modelFirst[0]->href,'page'=>1],['id'=>'firstPage']);
                $this->registerJs("
                jQuery(document).ready(function () {
                $('#slickMy').on('beforeChange',function(event, slick, currentSlide, nextSlide) {
                    if ((currentSlide==".($countQuery%$pagination->limit-1).")&&(nextSlide==0)){                     
                        $('.modal-backdrop').remove();
                        $('#modalMy').modal('hide');
                        var link = $('#firstPage')[0];
                        var linkEvent = document.createEvent('MouseEvents');
                        
                        linkEvent.initEvent('click', true, true);
                        link.dispatchEvent(linkEvent);
                        e.preventDefault();
                        return false;
                    } else if ((currentSlide==0)&&(nextSlide==".($countQuery%$pagination->limit-1).")) {
                        $('.modal-backdrop').remove();
                        $('#modalMy').modal('hide');
                        var link = $('#prevPage')[0];
                        var linkEvent = document.createEvent('MouseEvents');
                        
                        linkEvent.initEvent('click', true, true);
                        link.dispatchEvent(linkEvent);
                        e.preventDefault();
                        return false;                       
                    }else {
                        var link = $('#href'+nextSlide.toString())[0];
                        var linkEvent = document.createEvent('MouseEvents');
                        linkEvent.initEvent('click', true, true);
                        link.dispatchEvent(linkEvent);
                        return true;                   
                }
                });
                $('#slickMy').on('afterChange',function(event, slick,currentSlide){
                    var link = $('.slick-active')[0];
                    link.focus();
                    $('#exifInfo').html($('#meta'+(currentSlide+1).toString()).html()+$('#metaHref'+(currentSlide+1).toString()).html());
                    $('#imageInfo').html($('#metaInfo'+(currentSlide+1).toString()).html());
                    return true;
                });
                });"                  
                );        
    }
    if (!isset($link['prev'])){
         echo Html::a('',['our-life/view-photo','href'=>$modelLast[0]->href,'page'=>$pagination->pageCount],['id'=>'lastPage']);
         echo Html::a('',['our-life/view-photo','href'=>$modelNext[0]->href,'page'=>$pagination->getPage()+2],['id'=>'nextPage']);
                $this->registerJs("
                jQuery(document).ready(function () {
                $('#slickMy').on('beforeChange',function(event, slick, currentSlide, nextSlide) {
                if ((currentSlide==0)&&(nextSlide==".($pagination->limit-1).")){
                    $('.modal-backdrop').remove();
                    $('#modalMy').modal('hide');
                        var link = $('#lastPage')[0];
                        var linkEvent = document.createEvent('MouseEvents');
                        linkEvent.initEvent('click', true, true);
                        link.dispatchEvent(linkEvent);
                        e.preventDefault();
                        return false;
                    } else if ((currentSlide==".($pagination->limit-1).")&&(nextSlide==0)){
                    $('.modal-backdrop').remove();
                    $('#modalMy').modal('hide');
                        var link = $('#nextPage')[0];
                        var linkEvent = document.createEvent('MouseEvents');
                        linkEvent.initEvent('click', true, true);
                        link.dispatchEvent(linkEvent);
                        e.preventDefault();
                        return false;
                    }else {
                        var link = $('#href'+nextSlide.toString())[0];
                        var linkEvent = document.createEvent('MouseEvents');
                        linkEvent.initEvent('click', true, true);
                        link.dispatchEvent(linkEvent);
                        return true;                   
                }
                });
                $('#slickMy').on('afterChange',function(event, slick,currentSlide){
                    var link = $('.slick-active')[0];
                    link.focus();
                $('#exifInfo').html($('#meta'+(currentSlide+1).toString()).html()+$('#metaHref'+(currentSlide+1).toString()).html());
                $('#imageInfo').html($('#metaInfo'+(currentSlide+1).toString()).html());
                    return true;
                });
    });" );       
    }
    if ((isset($link['prev']))&&(isset($link['next']))) {
        echo Html::a('',['our-life/view-photo','href'=>$modelNext[0]->href,'page'=>$pagination->getPage()+2],['id'=>'nextPage']);
        echo Html::a('',['our-life/view-photo','href'=>$modelPrev[0]->href,'page'=>$pagination->getPage()],['id'=>'prevPage']);
        $this->registerJs("
                jQuery(document).ready(function () {
                $('#slickMy').on('beforeChange',function(event, slick, currentSlide, nextSlide) {
                if ((currentSlide==0)&&(nextSlide==".($pagination->limit-1).")){
                    $('.modal-backdrop').remove();
                    $('#modalMy').modal('hide');
                        var link = $('#prevPage')[0];
                        var linkEvent = document.createEvent('MouseEvents');
                        linkEvent.initEvent('click', true, true);
                        link.dispatchEvent(linkEvent);
                        e.preventDefault();
                        return false;
                } else if ((currentSlide==".($pagination->limit-1).")&&(nextSlide==0)){
                    $('.modal-backdrop').remove();
                    $('#modalMy').modal('hide');
                        var link = $('#nextPage')[0];
                        var linkEvent = document.createEvent('MouseEvents');
                        linkEvent.initEvent('click', true, true);
                        link.dispatchEvent(linkEvent);
                        e.preventDefault();
                        return false;
                    } else {
                        var link = $('#href'+nextSlide.toString())[0];
                        var linkEvent = document.createEvent('MouseEvents');
                        linkEvent.initEvent('click', true, true);
                        link.dispatchEvent(linkEvent);
                        return true;                   
                }
                });
                $('#slickMy').on('afterChange',function(event, slick,currentSlide){
                    var link = $('.slick-active')[0];
                    link.focus();
                    $('#exifInfo').html($('#meta'+(currentSlide+1).toString()).html()+$('#metaHref'+(currentSlide+1).toString()).html());
                    $('#imageInfo').html($('#metaInfo'+(currentSlide+1).toString()).html());                   
                    return true;
                });
    });" );
    }
    
    echo Html::a('',['our-life/view-photo','page'=>$pagination->getPage()+1],['id'=>'escapeHref']);
    $this->registerJs("
        $('#modalMy').on('hidden.bs.modal',function (e) {
  var link = $('#escapeHref')[0];
  var linkEvent = document.createEvent('MouseEvents');
  linkEvent.initEvent('click', true, true);
  link.dispatchEvent(linkEvent);
  e.preventDefault();
    });
                   
        $('#modalMy').on('shown.bs.modal',function (e) {
            var link = $('.slick-active')[0];
            link.focus();
            return true;
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

$iter=1;
foreach ($image as $item){
    echo "<div class=\"notShowExif\" id='meta".(string)$iter."'><br/><span class=\"exifMeta\">Дата снимка: <br/></span><span class=\"exifValue\">".(isset($item->time)? $item->time:"Не определено")."</span><br/>"
            . "<span class=\"exifMeta\">Устройство: <br/></span><span class=\"exifValue\">".(isset($item->device)? $item->device:"Не определено")."</span><br/>"
            . "<span class=\"exifMeta\">Размер: <br/></span><span class=\"exifValue\">".(isset($item->size)? $item->size:"Не определено")."</span></div>";
    echo "<div class=\"notShowInfo\" id='metaInfo".(string)$iter."'><br/>"
            . "<span class=\"metaInfo\">Название: <br/></span><span class=\"valueInfo\">".(isset($item->fullName)? $item->fullName:"Не определено")."</span><br/>"
            . "<span class=\"metaInfo\">Описание: <br/></span><span class=\"valueInfo\">".(isset($item->descr)? $item->descr:"Не определено")."</span></div>";

    $iter+=1; 
}
$slick= Slick::widget([
        'id'=>'slickMy',
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
            'autoplay'=>false,
            'infinite'=>true,
            'fade'=> true,
            'centerMode'=>true,
            'cssEase'=> 'linear',
            'adaptiveHeight'=>false,
            'variableWidth'=>false,
            'initialSlide'=>$index,
            'useCSS'=>false,
            'focusOnSelect'=>true
           // 'onBeforeChange'=>new JsExpression('function(event, slick, currentSlide, nextSlide) { alert(123333) }'),
            // note, that for params passing function you should use JsExpression object
            ],
 
 
    ]);  

      yii\bootstrap\Modal::begin([
    'id'=>'modalMy',
    'header' => "<h3 align=\"center\">Просмотр фотографий</h3>",
    'clientOptions'=> ($href=="") ? ['show'=>false] : ['show'=>true]
    ]); 

  ?> 


 <?php 

    

  echo $slick; 
  echo "<hr id=\"hrMy\" color=\"red\" align=\"center\" size:\"10px\">";
  echo "<div id=\"footerImage\"><div id=\"exifInfo\" ></div>";
  echo "<div id=\"imageInfo\"></div></div>";
yii\bootstrap\Modal::end();
if (isset($href)) {
    $this->registerJs("
     jQuery(document).ready(function () {    
        $('#exifInfo').html($('#meta".($index+1)."').html()+$('#metaHref".($index+1)."').html());
            
        $('#imageInfo').html($('#metaInfo".($index+1)."').html());
            
});

");
}    

?>


</div>
<?php
Pjax::end();

?>
