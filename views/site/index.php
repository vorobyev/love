<?php
use dosamigos\fileupload\FileUploadUI;
/* @var $this yii\web\View */
$this->title = 'Наш склад счастья';
?>
<div class="site-index">

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
