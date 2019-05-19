<?php
include_once dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."setting.php";
	$sql = "SELECT * FROM bannedword1";
        $query = mysqli_query($conn,$sql);

        while ($row = mysqli_fetch_assoc($query)) {
            $words1[] = $row['bword1'];
        }
        $count1 = count($words1);
        
		/***************************************************/       
        $sql = "SELECT * FROM bannedword2";
        $query = mysqli_query($conn,$sql);

        while ($row = mysqli_fetch_assoc($query)) {
            $words2[] = $row['bword2'];
        }
		$count2 = count($words2);
        
        /*****************************************/
        
        $sql = "SELECT * FROM bannedword3";
        $query = mysqli_query($conn,$sql);

        while ($row = mysqli_fetch_assoc($query)) {
            $words3[] = $row['bword3'];
        }
		$count3 = count($words3);
		/*****************************************/
		$sql = "SELECT count(id) as total FROM `message`";
        $query = mysqli_query($conn,$sql);
		$row = mysqli_fetch_assoc($query);
        $total = $row['total'];
        /*****************************************/
		$sql = "SELECT message FROM `message`";
        $query = mysqli_query($conn,$sql);
		$i = 0;
		$j = 0;
		while($row = mysqli_fetch_assoc($query)) {
			if (strpos($row['message'], '*') !== false) {
			    $i++;
			}else {
				$j++;
			}
		}
		/*****************************************/
		$bwp1 = ($i/($i+$total)*100);
		$bwr1 = ($i/($i+$count1)*100);
		$bwr2 = ($i/($i+$count2)*100);
		$bwr3 = ($i/($i+$count3)*100);
		$bwf1 = 2*(($bwp1*$bwr1)/($bwp1+$bwr1));
		$bwf2 = 2*(($bwp1*$bwr2)/($bwp1+$bwr2));
		$bwf3 = 2*(($bwp1*$bwr3)/($bwp1+$bwr3));


		$gwp1 = ($j/($j+$total)*100);
		$gwr1 = ($j/($j+$count1)*100);
		$gwr2 = ($j/($j+$count2)*100);
		$gwr3 = ($j/($j+$count3)*100);
		$gwf1 = 2*(($gwp1*$gwr1)/($gwp1+$gwr1));
		$gwf2 = 2*(($gwp1*$gwr2)/($gwp1+$gwr2));
		$gwf3 = 2*(($gwp1*$gwr3)/($gwp1+$gwr3));

		$result = array(
			'badword' => array($bwp1,$bwr1,$bwr2,$bwr3,$bwf1,$bwf2,$bwf3),
			'goodwords' => array($gwp1,$gwr1,$gwr2,$gwr3,$gwf1,$gwf2,$gwf3)
		);
		echo json_encode($result);
		exit;









