<!DOCTYPE html>
<html lang="<?php echo vOut('frame_current_language')?>">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<?php echo $this->getHeaderData()?>
<?php echo $this->getCSSFileHead()?>
<?php echo $this->getCSSCodeHead()?>
<?php echo $this->getJSFileHead()?>
<?php echo $this->getJSCodeHead()?>
</head>
<body id="page-top">
<?php echo $this->getCSSFileBody()?>
<?php echo $this->getCSSCodeBody()?>
<?php echo $this->getJSFileBody()?>
<?php echo $this->getJSCodeBody()?>

<?php echo $content?>

</body>
</html>