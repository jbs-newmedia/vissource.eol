<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional //EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo h()->_outputString($title)?></title>
</head>
<body>
<br/>
<table width="800" border="0" align="center" cellpadding="10" cellspacing="0" style="border:2px solid #eeeeee;">
	<tr>
		<td bgcolor="#eeeeee">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="95"><?php echo $this->getOptimizedImage($logo['name'], array('module'=>$logo['module'], 'title'=>$logo['title'], 'longest'=>$logo['longest'], 'height'=>$logo['height'], 'width'=>$logo['width']))?></td>
					<td><span style="font-family:verdana; font-size:20px; color:#000000; font-weight:bold;"><?php echo h()->_outputString($tool['main'])?>: <?php echo h()->_outputString($tool['name'])?></span></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td style="font-family:verdana; font-size:12px; color:#000000;"><span style="font-weight:bold; family:verdana; font-size:12px; color:#000000;"><?php echo h()->_outputString($title)?></span><br/><br/><?php echo $content?></td>
	</tr>
</table>
<br/><br/>
</body>
</html>