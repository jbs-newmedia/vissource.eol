<!doctype html>
<html>
<head>
<?php echo $this->getHeaderData()?>
<?php echo $this->getCSSFileHead()?>
<?php echo $this->getCSSCodeHead()?>
<?php echo $this->getJSFileHead()?>
<?php echo $this->getJSCodeHead()?>
</head>
<body>
<?php echo $this->getCSSFileBody()?>
<?php echo $this->getCSSCodeBody()?>
<?php echo $this->getJSFileBody()?>
<?php echo $this->getJSCodeBody()?>

<div id="vis_notify"></div>
<div id="vis_loader"><span>Lade ...</span></div>

<?php echo $content?>

</body>
</html>