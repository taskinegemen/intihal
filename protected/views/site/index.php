
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>TÜBİTAK SOBAG</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo Yii::app()->getBaseUrl(true);?>/css/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->getBaseUrl(true);?>/css/style.css" rel="stylesheet">


    <!-- Custom styles for this template -->
    <link href="jumbotron-narrow.css" rel="stylesheet">
    <script type="text/javascript" src="<?php echo Yii::app()->getBaseUrl(true);?>/js/jquery-1.12.0.min.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->getBaseUrl(true);?>/js/diff_match_patch.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->getBaseUrl(true);?>/js/jquery.pretty-text-diff.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->getBaseUrl(true);?>/js/bootstrap.min.js"></script>
    <script type="text/javascript">

 $(document).ready(function(){
    var incoming_search_type='<?php if(isset($search_type))echo $search_type;?>';
    console.log("INCOMING SEARCH TYPE:",incoming_search_type);
     if(incoming_search_type=="text")
     {
      $('#metin').attr('checked','checked');
      $('#text_handler').show();
      $('#file_handler').hide();
     }
     else if(incoming_search_type=="file")
     {
      $('#file_handler').show();
      $('#text_handler').hide();
     }

    $('input[name="SearchForm[search_type]"]').on('change', function() {
     var search_type=$(this).val();
     if(search_type=="text")
     {
      $('#metin').attr('checked','checked');
      $('#text_handler').show();
      $('#file_handler').hide();
     }
     else if(search_type=="file")
     {
      $('#file_handler').show();
      $('#text_handler').hide();
     }
  });

      $(".radio_menu_item").click(function(){
        var id=$(this).data(id).id;
        console.log("ID->",id);
        $('#'+id).prop("checked",true);
        $('#'+id).change();
      });

      $(".compare").click(function () {
        $('#voicePositiveDialog').modal();
        var id=$(this).attr("data-id");
        console.log("LOG->",id);
        var changedContent=$('#'+id).html();
        var originalContent=$('#main_text').html();
    $("#wrapper tr").prettyTextDiff({
        cleanup: false,
        originalContent:originalContent,
        changedContent:changedContent,
        diffContainer: ".diff2"
    });


});

});
    </script>
  </head>

  <body>

    <div class="container">
      <div class="header clearfix">
        <!--<nav>
          <ul class="nav nav-pills pull-right">
            <li role="presentation" class="active"><a href="#">Home</a></li>
            <li role="presentation"><a href="#">About</a></li>
            <li role="presentation"><a href="#">Contact</a></li>
          </ul>
        </nav>-->
        <h3 class="text-muted"><img src="http://www.physics.metu.edu.tr/EIC/tubitak-logo.gif">TÜBİTAK SOBAG</h3>
      </div>

      <div class="jumbotron" style="background-color:#B0DDF4">
        <!--<h1>Jumbotron heading</h1>-->
        <!--<p class="lead">Sorgulama için kaynak dosyayı yükleyiniz</p>-->
        <?php $form=$this->beginWidget(
    'CActiveForm',
    array(
        'id'=>'search-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array('enctype' => 'multipart/form-data'),
    )
);
?>
        <!--<?php echo $form->fileField($SearchForm, 'inputForm');?>-->


        <!--<input id="ytSearchForm_inputForm" type="hidden" value="" name="SearchForm[inputForm]" />
        <span class="btn btn-default">
          <input name="SearchForm[inputForm]" id="SearchForm_inputForm" type="file">
        </span>-->


        <!--<input name="SearchForm[inputForm]" id="SearchForm_inputForm" type="file" />-->


<div class="input-group">
  <input id="ytSearchForm_inputForm" type="hidden" value="" name="SearchForm[inputForm]" />
  <input name="SearchForm[inputForm]" type="text" style="display:none;" class="form-control" aria-label="..." id="text_handler" value="<?php if(isset($value) && $search_type=='text')echo $value; ?>">
        <span class="btn btn-default" style="width:100%" id="file_handler">
          <input name="SearchForm[inputForm]" id="SearchForm_inputForm" type="file">
        </span>
  <div class="input-group-btn">
    <input class="btn btn-success" role="button" type="submit" value="Sorgula">
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </button>
  <ul class="dropdown-menu dropdown-menu-right">
    <li>
      <!--<span class="input-group-addon">
        <input type="radio" checked name="SearchForm[search_type]" id="dosya" value="file" aria-label="Metin">
        <label for="dosya">Dosya</label>
      </span>-->
      <span class="input-group-addon" style="padding-left:0px;padding:0px;">
          <button type="button" class="btn btn-default dropdown-toggle radio_menu_item" data-id="dosya" style="width:100%">Dosya</button>
          <input type="radio" style="display:none" checked="checked" name="SearchForm[search_type]" id="dosya" value="file" >
      </span>
    </li>
    <li>
      <!--<span class="input-group-addon">
        <input type="radio" name="SearchForm[search_type]"  id="metin" value="text" aria-label="Dosya">
        <label for="metin">Metin</label>
      </span>-->
      <span class="input-group-addon" style="padding-left:0px;padding:0px;">
        <button type="button" class="btn btn-default dropdown-toggle radio_menu_item" data-id="metin" style="width:100%">Metin</button>
        <input type="radio" style="display:none" name="SearchForm[search_type]" id="metin" value="text">
      </span>
    </li>
  </div>

</div>


        <!--<p><input class="btn btn-lg btn-success" role="button" type="submit" value="Sorgula"></p>-->
        <?php $this::endWidget(); ?>
      </div>

<?php if(isset($document)){/*print_r($document);*/} ?>
<?php 
if(isset($result))
{
$match_list=json_decode($result);
/*print_r($match_list->response);
die();*/
if(isset($match_list->response->docs))
{
    $counter=0;
    foreach ($match_list->response->docs as $item) { ?>
    <div class="panel panel-info">
    <div class="panel-heading"> 
    <h3 class="panel-title">Döküman Başlığı:
    <?php 
        if(isset($item->title[0]))
        {?>
            <span class="label label-primary">
              <?php print_r($item->title[0]);?>
            </span>
        <?php
        }
        else
        {?>
            <span class="label label-warning">
              mevcut değil
            </span>
        <?php 
        }
        ?>
    </h3> 

      </div>
    <div class="panel-body">   
    <h3 class="panel-title">Dosya ismi ve yolu:<br>
    <?php 
        $file = basename($item->id);
        if(isset($item->id))
        {?>
            <span class="label label-success">
              <?php print_r($file.",<br>".$item->id);?>
            </span>
        <?php
        }
        else
        {?>
            <span class="label label-warning">
              mevcut değil
            </span>
        <?php 
        }
        ?>
    </h3> 
    <br>
    <h3 class="panel-title">Yazar:
    <?php 
        if(isset($item->dc_creator[0]))
        {?>
            <span class="label label-success">
              <?php print_r($item->dc_creator[0]);?>
            </span>
        <?php
        }
        else
        {?>
            <span class="label label-warning">
              mevcut değil
            </span>
        <?php 
        }
        ?>
    </h3> 
    <div style="display:none;" id="<?php echo $counter."_div"; ?>">
    <?php print_r($this->just_read_doc($item->id,$file));?>
    </div>



    <?php /*print_r($item);*/ ?></div>

<div class="panel-footer" style="text-align:right">
<button type="button" class="btn btn-primary compare" data-id="<?php echo $counter."_div"; ?>" >Karşılaştır</button>
</div> 

    </div>
    <?php $counter++; }
}
}
else
{
  //echo "error";
}

?>
      <!--
      <footer class="footer">
        <p>&copy; 2016 TÜBİTAK</p>
      </footer>-->

<!-- Large modal -->
<!--<button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bs-example-modal-lg">Large modal</button>-->

<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
egemen
    </div>
  </div>
</div>

<div class="modal fade" id="voicePositiveDialog" role="dialog" >
  <div class="modal-dialog modal-lg" style="width:90%">
    <div class="modal-content">
      <div class="modal-body">
          <div id="container">

<div id="wrapper">
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <!--<th>Original</th>
                <th>Changed</th>-->
                <th>Karşılaştır</th>
            </tr>
        </thead>
        <tbody> 
            <tr>
                <!--<td >Some text here.</td>
                <td >Some more text which can be passed to this function.</td>-->
                <td class="diff2"></td>
            </tr>
         </tbody>
    </table>
    
</div>



          </div>
      </div>
    </div>
  </div>
</div>

<!--<button type="button" id="compare" class="btn btn-primary" >Compare</button>-->
<div id="main_text" style="display:none;">
  <?php if(isset($document))print_r($document); ?>
</div>


    </div> <!-- /container -->
  </body>
</html>

