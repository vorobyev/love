<?php
use dosamigos\fileupload\FileUploadUI;
use yii\widgets\LinkPager;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\web\JsExpression;

/* @var $this yii\web\View */
$this->title = 'Фотографии (редактирование)';
$this->params['breadcrumbs'][] = $this->title;

$iter=1;
foreach ($image as $item){
    echo "<div class=\"notShowHref\" id='metaHref".(string)$iter."'><br/>"
        //. Html::a('Оригинал',"image/".$item->name,['target'=>"_blank",'class'=>'notPjax','id'=>'hrefBlank'.(string)$iter])."</div>";
        . Html::a('Оригинал',['our-life/view-photo','href'=>$item->href,'showOriginal'=>1],['target'=>"_blank",'class'=>'notPjax','id'=>'hrefBlank'.(string)$iter])."</div>";
    $iter+=1;
}

Pjax::begin(['id'=>'myInnerPhoto','linkSelector'=>'.pjaxMy']);
$numb=0;
foreach ($image as $item){ 
    echo Html::a('',['our-life/view-photo','page'=>($pagination->getPage()==0)?1:$pagination->getPage()+1,'href'=>$item->href,'edit'=>1],['id'=>'href'.$numb,'class'=>'pjaxMy']);   
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


//Pjax::begin(['id'=>'myPjax']); 
$items=[];
$itemsThumb=[];
foreach ($image as $item){
    $itemsThumb=array_merge($itemsThumb,[Html::a('<img src="image/thumbnail/'.$item->name.'">',['our-life/view-photo','href'=>$item->href,'page'=>($pagination->getPage()==0)?1:$pagination->getPage()+1,'edit'=>1])]);
}
foreach ($image as $item){
    $items=array_merge($items,['<img src="image/medium/'.$item->name.'">']);
    
}

//$items=['<img src="image/thumbnail/'.$image[0]->name.'">'];
?>
<div class="site-index">

     
 
    
<?= "<div style='text-align:center'>". LinkPager::widget(['pagination'=>$pagination])."</div>" ?>

    

    

 
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


if (isset($href)){
      yii\bootstrap\Modal::begin([
    'id'=>'modalMy',
    'header' => "<h3 align=\"center\">Редактирование информации о фотографии</h3>",
    'clientOptions'=> ($href=="") ? ['show'=>false] : ['show'=>true]
    ]); 

  

 echo "<div><img src='image/medium/".$imageEdit->name."'></div>";   

 
  echo "<hr id=\"hrMy\" color=\"red\" align=\"center\" size:\"10px\">";
  $form = ActiveForm::begin(['layout' => 'horizontal','id' => 'login-form']);
  echo $form->field($imageEdit, 'fullName')->textInput(['value'=>$imageEdit->fullName])->label("Название");        
  echo $form->field($imageEdit, 'descr')->textarea(['value'=>$imageEdit->descr,'rows'=>4])->label("Описание"); 
  echo $form->field($imageEdit, 'effects')->textInput(['value'=>$imageEdit->effects])->label("Эффект"); 
  echo "<div style='text-align:center'>".Html::submitButton('Изменить', ['class' => 'btn btn-primary', 'name' => 'login-button'])."</div>" ;         
  ActiveForm::end();         
  yii\bootstrap\Modal::end();
}

    echo Html::a('',['our-life/view-photo','page'=>$pagination->getPage()+1,'edit'=>1],['id'=>'escapeHref']);
    $this->registerJs("
        $('#modalMy').on('hidden.bs.modal',function (e) {
  var link = $('#escapeHref')[0];
  var linkEvent = document.createEvent('MouseEvents');
  linkEvent.initEvent('click', true, true);
  link.dispatchEvent(linkEvent);
  e.preventDefault();
    });
        ");

?>


</div>
<div style="text-align:center;">


<?= FileUploadUI::widget([
        'model' => $model,
        'attribute' => 'profile_pic',
        'url' => ['files/add'],
         'gallery' => true,
         'fieldOptions' => [
             'accept' => 'image/*',
         ],
         'clientOptions' => [  
             'maxFileSize' => 10000000
          ],
          'clientEvents' => [
              'fileuploaddone' => 'function(e, data) {
                                      jQuery(".fb-image-profile").attr("src",data.result);
                                  }',
              'fileuploadfail' => 'function(e, data) {
                                      
                                  }',
          ],
]);
?>
</div>


