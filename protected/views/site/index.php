<?php
/* @var $this SiteController */
if(!session_id()) {
    session_start();
}
$this->pageTitle=Yii::app()->name;
?>

<h1><i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>


<?php
  $fb = new FBGraph();

  if($fb->login()){
    echo "<h6>Bienvenido $fb->name </h6>";
    echo "<br><strong>Usuario: </strong> $fb->name";
    echo "<br><strong>e-mail: </strong> $fb->email";
    echo "<br><strong>perfil: </strong> $fb->link";
    echo "<br><strong>genero: </strong> $fb->gender";


  } else{
    echo '<a href="' . $fb->loginUrl . '">Logueate con tu cuenta de Facebook</a>';
  }
?>

<div align=center>
	<img src="images/Descargar-paginas-web.jpg" width="600" height="350">
</div>
