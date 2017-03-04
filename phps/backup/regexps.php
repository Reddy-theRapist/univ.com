<?php
include_once 'skeleton_firstpart.html';
require 'parsing.php';
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
		<form style="margin:10px;" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data" method="post">
			<div class="form-group">
				<!-- <input type="hidden" name="MAX_FILE_SIZE" value="20*1024*1024" /> -->
				<input style="margin:5px;" type="file" name="FUCKTHIS" />
				<input style="margin:5px;" type="submit" name="submittya" value="Распарсить БД" />
			</div>
			<hr/>
		</form>
		<hr/>

		<?php
			if (isset($_POST['submittya']))
				ParseThatDB();
            if (isset($_GET['SubmitRegionSearch']))
                DisplayPeopleFromRegion($_GET["regionSearch"]);
		?>

	</div>
</div>

<?php
echo
'<script type="text/javascript">
	$("#phptest > a").css("color","rgb(252, 163, 90)");
</script>';
include_once 'skeleton_lastpart.html';
 ?>
