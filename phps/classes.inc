<?php
class theMatrix //implements Serializable// extends AnotherClass
{
	public $hSize=-1;
	public $vSize=-1;
	public $filename="matrix.txt";
	public $matrixItself=array();

	function __construct()
	{}

	public static function constructor1()
	{
		$newInstance=new self();
		$newInstance->vSize=rand(4,16);
		$newInstance->hSize=$newInstance->vSize;
		$newInstance->fillMatrix();
		return $newInstance;
	}

	public static function constructor2($H, $V)
	{
		$newInstance=new self();
		$newInstance->hSize=$H;
		$newInstance->vSize=$V;
		$newInstance->fillMatrix();
		return $newInstance;
	}
	public static function loadFrom($matrix)
	{
		$M= new self();
		$M->vSize=count($matrix);
		$M->hSize=count($matrix[0]);
		$M->matrixItself=$matrix;
		return;
	}

	function fillMatrix()
	{
		for ($i=0;$i<$this->vSize;$i++)
			for ($j=0;$j<$this->hSize;$j++)
				$this->matrixItself[$i][$j]=rand(1,20);
	}

	public function getDeterminant()
	{
		$aMatrix = $this->matrixItself;
		if (count($aMatrix)==count($aMatrix[0]))
			{
				$n=count($aMatrix);
				$D=1;
				for ($k=1;$k<$n;$k++)
					for ($i=$k;$i<$n;$i++)
					{
						$c = $aMatrix[$i][$k-1]/$aMatrix[$k-1][$k-1];
						for ($j=0;$j<$n; $j++)
							$aMatrix[$i][$j] -=$c*$aMatrix[$k-1][$j];
					}

				for ($i=0;$i<$n;$i++)
					$D*=$aMatrix[$i][$i];

				return number_format($D);
			}
			else echo "Матрица не квадратная, невозможно посчитать определитель";
	}

	public function getInversed()
	{
		$inversedMatrix=$this->matrixItself;
		$n = count($inversedMatrix);
		$extendedMatrix;

		for ($i = 0; $i <$n; ++$i)
			for ($j = 0; $j <$n; ++$j)
				$extendedMatrix[$i][$j] = ($i == $j) ? 1 : 0;

		for ($i = 0; $i < $n; ++$i)
			$inversedMatrix[$i] = array_merge($inversedMatrix[$i], $extendedMatrix[$i]);

		for ($j = 0; $j < $n-1; ++$j)
			for ($i = $j+1; $i < $n; ++$i)
				if ($inversedMatrix[$i][$j] != 0)
				 {
					$scalar = $inversedMatrix[$j][$j] / $inversedMatrix[$i][$j];
					for ($jj = $j; $jj < $n*2; ++ $jj)
					{
						$inversedMatrix[$i][$jj] *= $scalar;
						$inversedMatrix[$i][$jj] -= $inversedMatrix[$j][$jj];
					}
				 }

		for ($j = $n-1; $j > 0; -- $j)
			for ($i = $j-1; $i >= 0; -- $i)
				if ($inversedMatrix[$i][$j] != 0) {
					$scalar = $inversedMatrix[$j][$j] / $inversedMatrix[$i][$j];
					for ($jj = $i; $jj < $n*2; ++$jj)
					 {
						$inversedMatrix[$i][$jj] *= $scalar;
						$inversedMatrix[$i][$jj] -= $inversedMatrix[$j][$jj];
					 }
				}

		for ($j = 0; $j < $n; ++ $j)
			if ($inversedMatrix[$j][$j] != 1)
			{
				$scalar = 1 / $inversedMatrix[$j][$j];
				for ($jj = $j; $jj < $n*2; ++ $jj)
					$inversedMatrix[$j][$jj] = number_format($inversedMatrix[$j][$jj]*$scalar,5);
			}

		$rezult;
		for ($i = 0; $i < $n; ++$i)
			$rezult[$i] = array_slice($inversedMatrix[$i], $n);

		return $rezult;
	}
	function getReflected_V()
	{
		$reflected;
		for ($i = 0; $i<$this->vSize; $i++)
		{
			$index = 0;
			for($j=$this->hSize-1; $j>-1; $j--,$index++)
				$reflected[$i][$index] = $this->matrixItself[$i][$j];
		}
		return $reflected;
	}
	function getReflected_H()
	{
		$reflected;
		for ($i = $this->vSize-1; $i>-1; $i--)
		{
			$index = 0;
			for($j=$this->hSize-1; $j>-1; $j--,$index++)
			{
				$reflected[$index][$i] = $this->matrixItself[$j][$i];
				// $index++;
			}
		}
		return $reflected;
	}

	function SwapRows($matrix)
	{
		for ($i=0; $i<count($matrix[0])-1; $i+=2)
		{
			$temp = $matrix[$i];
			$matrix[$i] = $matrix[$i+1];
			$matrix[$i+1] =$temp;
		}
		return $matrix;
	}

function SwapColumns($matrix)
{
	for ($i=0; $i<count($matrix[0]); $i++)
		for ($j=0; $j<count($matrix[1])-1; $j+=2)
		{
			$temp = $matrix[$i][$j];
			$matrix[$i][$j] = $matrix[$i][$j+1];
			$matrix[$i][$j+1] =$temp;
		}
	return $matrix;
}

	public function display($matrix, $title)
	{
		echo "<table class='table-bordered' style='margin:5px;'><thead><tr>" .$title. "</tr></thead><tbody>";
		for ($i = 0;$i<count($matrix);$i++)
			{
				echo "<tr>";
				for ($j = 0;$j<count($matrix[$i]);$j++)
					echo "<td>" . $matrix[$i][$j] . "</td>";
				echo "</tr>";
			}
		echo "</tbody></table>";
	}

	public function save()
	{file_put_contents('matrix.txt', serialize($this));}
}

 ?>
