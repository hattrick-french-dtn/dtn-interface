<?php
// from the file foxtrick/blob/master/content/information-aggregation/htms-points.js

function htmspoint($years, $days, $keeper, $defending, $playmaking, $winger, $passing, $scoring, $setPieces) {
	$pointsYears = array(
		17 => 10,
		18 => 9.92,
		19 => 9.81,
		20 => 9.69,
		21 => 9.54,
		22 => 9.39,
		23 => 9.22,
		24 => 9.04,
		25 => 8.85,
		26 => 8.66,
		27 => 8.47,
		28 => 8.27,
		29 => 8.07,
		30 => 7.87,
		31 => 7.67,
		32 => 7.47,
		33 => 7.27,
		34 => 7.07,
		35 => 6.87,
		36 => 6.67,
		37 => 6.47,
		38 => 6.26,
		39 => 6.06,
		40 => 5.86,
		41 => 5.65,
		42 => 6.45,
		43 => 6.24,
		44 => 6.04,
		45 => 5.83
	);

	// keeper, defending, playmaking, winger, passing, scoring, setPieces
	$pointsSkills = array(
		0 => [0, 0, 0, 0, 0, 0, 0],
		1 => [2, 4, 4, 2, 3, 4, 1],
		2 => [12, 18, 17, 12, 14, 17, 2],
		3 => [23, 39, 34, 25, 31, 36, 5],
		4 => [39, 65, 57, 41, 51, 59, 9],
		5 => [56, 98, 84, 60, 75, 88, 15],
		6 => [76, 134, 114, 81, 104, 119, 21],
		7 => [99, 175, 150, 105, 137, 156, 28],
		8 => [123, 221, 190, 132, 173, 197, 37],
		9 => [150, 271, 231, 161, 213, 240, 46],
		10 => [183, 330, 281, 195, 259, 291, 56],
		11 => [222, 401, 341, 238, 315, 354, 68],
		12 => [268, 484, 412, 287, 381, 427, 81],
		13 => [321, 580, 493, 344, 457, 511, 95],
		14 => [380, 689, 584, 407, 540, 607, 112],
		15 => [446, 809, 685, 478, 634, 713, 131],
		16 => [519, 942, 798, 555, 738, 830, 153],
		17 => [600, 1092, 924, 642, 854, 961, 179],
		18 => [691, 1268, 1070, 741, 988, 1114, 210],
		19 => [797, 1487, 1247, 855, 1148, 1300, 246],
		20 => [924, 1791, 1480, 995, 1355, 1547, 287],
		21 => [1074, 1791, 1791, 1172, 1355, 1547, 334],
		22 => [1278, 1791, 1791, 1360, 1355, 1547, 388],
		23 => [1278, 1791, 1791, 1360, 1355, 1547, 450],
	);

	$actValue = $pointsSkills[$keeper][0];
	$actValue += $pointsSkills[$defending][1];
	$actValue += $pointsSkills[$playmaking][2];
	$actValue += $pointsSkills[$winger][3];
	$actValue += $pointsSkills[$passing][4];
	$actValue += $pointsSkills[$scoring][5];
	$actValue += $pointsSkills[$setPieces][6];
	
	//echo "<br/>Value ".$actValue;
	
	$pointdiff = 0;
	if ($years < 28) {
		// compute the HTMS
		$pointYear = $pointsYears[$years];
		$pointdiff = ((112 - $days) / 7) * $pointYear;
		for ($i = $years + 1; $i < 28; $i++) {
			$pointdiff += 16 * $pointsYears[$i];
		}
	}
	else {
		// substract
		$pointdiff = ($days / 7) * $pointsYears[$years];
		for ($i = $years; $i > 28; $i--) {
			$pointdiff += 16 * $pointsYears[$i];
		}
		$pointdiff = -$pointdiff;
	}
	//echo "<br/>Diff ".$pointdiff;
	$potential = $actValue + $pointdiff;
	//echo "<br/>Pot ".$potential;
	
	return ["value" => $actValue, "potential" => round($potential)];
}

?>