<?php
include_once 'skeleton_firstpart.html';
?>

<div class="row extendedContainer" id="theContainer">
	<div class="col-sm-3">
		<h4 style="text-align:center">Навигация</h4>
		<!-- <nav class="col-sm-3"> -->
		<ul class="nav nav-pills nav-stacked extendedUL">
			<li><button class="btn btn-primary extendedBtn-bottom" onclick="scrollTowards('start')">В начало конца</button></li>
			<li><button class="btn btn-primary extendedBtn-top" onclick="scrollTowards('end')">В конец начала</button></li>
		</ul>
		<!-- </nav> -->
		<br/>
		<div class="input-group extendedUL">
			<input type="text" class="form-control" placeholder="Поиск по сайту..">
			<span class="input-group-btn">
	          <button class="btn btn-default" type="button">
	          <span class="glyphicon glyphicon-search"></span>
			</button>
			</span>
		</div>
	</div>
	<div class="row col-sm-9">
		<div class="col-md-6">
			<p>Матрица</p>
			<?php require 'dialog.php'; ?>
			<!-- < ?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>  -->
			<!-- ../phps/dialog.php -->
			<form class="form-inline" action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>' method="GET">
				<div class="form-group">
					<label class="control-label" for="rows">Размерность: </label>
					<input id="rows" type="text" name="size" class="form-control input-sm" value="<?php echo $size; ?>"/>
					<br/><?php echo $sizeError; ?>
				</div>
				<hr/>
				<input type="submit" name="submitButton" value="Создать матрицу" />
			</form>
			<hr/>
			<p>Или</p>
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data" method="post">
		   	<input type="file" name="datmatrix">
		   	<input type="submit" name="submittya" value="Загрузить матрицу платно с регистрацией и смс">
			</form>
		</div>
	</div>
</div>

<?php
echo
'<script type="text/javascript">
	$("#phptest > a").css("color","rgb(252, 163, 90)");
</script>';
include_once 'skeleton_lastpart.html';
 ?>
