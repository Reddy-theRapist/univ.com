<?php
//header("Location: http://www.example.com/"); //dis is how do I redirect
require_once('classes.inc');

$sizeError="";
$size="";
$matrix;
$matrixIsReady=false;

// if (!function_exists('is_int'))
// {
	 function is_it_int($v)
	 { return is_numeric($v) && $v*1 == (int)($v*1); }
// }

function preprocessData($data)
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function save($matrix)
{
	$s = serialize($matrix);
	// $sthis=$matrix->serialize();
	file_put_contents('matrix.txt', $s);
}


// if (isset($_GET["submit"]))
if ($_SERVER["REQUEST_METHOD"]=="GET")
{
	if (isset($_GET['size']))
	{
		$size = preprocessData(htmlspecialchars($_GET['size']));
		if (!empty($size))
			if (is_it_int($size)&&((int)$size)>0)
	   		$matrix = theMatrix::constructor2($size,$size);
			else $sizeError="На ввод заходят только целые положительные числа больше 0.";
		else
		{
			$matrix=theMatrix::constructor1();
			$size=$matrix->vSize;
		}
	}

	if (isset($matrix))
		{
			$matrix->display($matrix->matrixItself,"Основная матрица");
			$matrix->display($matrix->getInversed(),"Обратная матрица");
			$matrix->display($matrix->getReflected_H(),"Отраженная по горизонтали");
			$matrix->display($matrix->getReflected_V(), "Отраженная по вертикали");
			save($matrix);
			echo "<p style='margin:5px;'>Определитель матрицы = ". $matrix->getDeterminant() ."</p>";
			/*echo "<form action='<?php save()'>
				<a href='matrix.txt'><input type='button' value = 'save dis shit'/></a>
			</form>";*/
			/*echo "<input type='button' name='downloadIt'
			onclick ='&lt;?php echo &lt;a href='matrix.txt' download style='margin:10px;'&gt;ссылка на скачивание &lt;/a&gt;; ?&lt;'
							value='скачать матрицы бесплатно без регистрации и смс' />
					";*/
			echo " <a href='matrix.txt' download style='margin:10px;'>
						<input type='button' name='downloadIt'
							value='скачать матрицы бесплатно без регистрации и смс' />
					</a>";
		}

}
else if ($_SERVER["REQUEST_METHOD"]=="POST")
	if (isset($_POST["submittya"]))
	{
		echo "<p>".basename($_FILES["datmatrix"]["name"]) ."</p><hr/>";
		$dir="uploads/";
		$targetfile =  $dir . basename($_FILES["datmatrix"]["name"]);
		$r = move_uploaded_file($_FILES["datmatrix"]["tmp_name"], $targetfile);
		$s = file_get_contents($targetfile);

		$matrix = unserialize($s);
		$matrix->display($matrix->matrixItself, "основная матрица");
		$matrix->display($matrix->SwapColumns($matrix->matrixItself), "Переставленные столбцы");
		$matrix->display($matrix->SwapRows($matrix->matrixItself), "Переставленные строки");

		echo "<hr/>";
	}
?>
