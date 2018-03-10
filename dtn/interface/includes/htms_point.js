// from the file foxtrick/blob/master/content/information-aggregation/htms-points.js
function htmspoint(years, days, 
	keeper, wkkeeper, 
	defending, wkdefending, 
	playmaking, wkplaymaking,
	winger, wkwinger,
	passing, wkpassing,
	scoring, wkscoring,
	setPieces) {

	var pointsYears = [];
	pointsYears[17] = 10;
	pointsYears[18] = 9.92;
	pointsYears[19] = 9.81;
	pointsYears[20] = 9.69;
	pointsYears[21] = 9.54;
	pointsYears[22] = 9.39;
	pointsYears[23] = 9.22;
	pointsYears[24] = 9.04;
	pointsYears[25] = 8.85;
	pointsYears[26] = 8.66;
	pointsYears[27] = 8.47;
	pointsYears[28] = 8.27;
	pointsYears[29] = 8.07;
	pointsYears[30] = 7.87;
	pointsYears[31] = 7.67;
	pointsYears[32] = 7.47;
	pointsYears[33] = 7.27;
	pointsYears[34] = 7.07;
	pointsYears[35] = 6.87;
	pointsYears[36] = 6.67;
	pointsYears[37] = 6.47;
	pointsYears[38] = 6.26;
	pointsYears[39] = 6.06;
	pointsYears[40] = 5.86;
	pointsYears[41] = 5.65;
	pointsYears[42] = 6.45;
	pointsYears[43] = 6.24;
	pointsYears[44] = 6.04;
	pointsYears[45] = 5.83;

	// keeper, defending, playmaking, winger, passing, scoring, setPieces
	var pointsSkills = [];
	pointsSkills[0] = [0, 0, 0, 0, 0, 0, 0];
	pointsSkills[1] = [2, 4, 4, 2, 3, 4, 1];
	pointsSkills[2] = [12, 18, 17, 12, 14, 17, 2];
	pointsSkills[3] = [23, 39, 34, 25, 31, 36, 5];
	pointsSkills[4] = [39, 65, 57, 41, 51, 59, 9];
	pointsSkills[5] = [56, 98, 84, 60, 75, 88, 15];
	pointsSkills[6] = [76, 134, 114, 81, 104, 119, 21];
	pointsSkills[7] = [99, 175, 150, 105, 137, 156, 28];
	pointsSkills[8] = [123, 221, 190, 132, 173, 197, 37];
	pointsSkills[9] = [150, 271, 231, 161, 213, 240, 46];
	pointsSkills[10] = [183, 330, 281, 195, 259, 291, 56];
	pointsSkills[11] = [222, 401, 341, 238, 315, 354, 68];
	pointsSkills[12] = [268, 484, 412, 287, 381, 427, 81];
	pointsSkills[13] = [321, 580, 493, 344, 457, 511, 95];
	pointsSkills[14] = [380, 689, 584, 407, 540, 607, 112];
	pointsSkills[15] = [446, 809, 685, 478, 634, 713, 131];
	pointsSkills[16] = [519, 942, 798, 555, 738, 830, 153];
	pointsSkills[17] = [600, 1092, 924, 642, 854, 961, 179];
	pointsSkills[18] = [691, 1268, 1070, 741, 988, 1114, 210];
	pointsSkills[19] = [797, 1487, 1247, 855, 1148, 1300, 246];
	pointsSkills[20] = [924, 1791, 1480, 995, 1355, 1547, 287];
	pointsSkills[21] = [1074, 1791, 1791, 1172, 1355, 1547, 334];
	pointsSkills[22] = [1278, 1791, 1791, 1360, 1355, 1547, 388];
	pointsSkills[23] = [1278, 1791, 1791, 1360, 1355, 1547, 450];

	var actValue = pointsSkills[keeper][0];
	actValue += pointsSkills[defending][1];
	actValue += pointsSkills[playmaking][2];
	actValue += pointsSkills[winger][3];
	actValue += pointsSkills[passing][4];
	actValue += pointsSkills[scoring][5];
	actValue += pointsSkills[setPieces][6];
	
	var yweek = days / 7;
	var corValue = actValue;
	if (keeper > 0) {
		var ptweek = 0
		if (wkkeeper > yweek) {
			ptweek = (wkkeeper - yweek)*pointsYears[years];
			var cut = (wkkeeper - yweek);
			var cutyears = years - 1;
			while (cut > 0) {
				ptweek += Math.min(cut,16) * pointsYears[cutyears];
				cut -= 16;
				cutyears -= 1;
			}
		} else {
			ptweek = wkkeeper*pointsYears[years];
		}
		corValue += Math.min(pointsSkills[keeper+1][0]-pointsSkills[keeper][0]-1, ptweek);
	}
	if (defending > 0) {
		var ptweek = 0
		if (wkdefending > yweek) {
			ptweek = (wkdefending - yweek)*pointsYears[years];
			var cut = (wkdefending - yweek);
			var cutyears = years - 1;
			while (cut > 0) {
				ptweek += Math.min(cut,16) * pointsYears[cutyears];
				cut -= 16;
				cutyears -= 1;
			}
		} else {
			ptweek = wkdefending*pointsYears[years];
		}
		corValue += Math.min(pointsSkills[defending+1][1]-pointsSkills[defending][1]-1, ptweek);
	}
	if (playmaking > 0) {
		var ptweek = 0
		if (wkplaymaking > yweek) {
			ptweek = (wkplaymaking - yweek)*pointsYears[years];
			var cut = (wkplaymaking - yweek);
			var cutyears = years - 1;
			while (cut > 0) {
				ptweek += Math.min(cut,16) * pointsYears[cutyears];
				cut -= 16;
				cutyears -= 1;
			}
		} else {
			ptweek = wkplaymaking*pointsYears[years];
		}
		corValue += Math.min(pointsSkills[playmaking+1][2]-pointsSkills[playmaking][2]-1, ptweek);
	}
	if (winger > 0) {
		var ptweek = 0
		if (wkwinger > yweek) {
			ptweek = (wkwinger - yweek)*pointsYears[years];
			var cut = (wkwinger - yweek);
			var cutyears = years - 1;
			while (cut > 0) {
				ptweek += Math.min(cut,16) * pointsYears[cutyears];
				cut -= 16;
				cutyears -= 1;
			}
		} else {
			ptweek = wkwinger*pointsYears[years];
		}
		corValue += Math.min(pointsSkills[winger+1][3]-pointsSkills[winger][3]-1, ptweek);
	}
	if (passing > 0) {
		var ptweek = 0
		if (wkpassing > yweek) {
			ptweek = (wkpassing - yweek)*pointsYears[years];
			var cut = (wkpassing - yweek);
			var cutyears = years - 1;
			while (cut > 0) {
				ptweek += Math.min(cut,16) * pointsYears[cutyears];
				cut -= 16;
				cutyears -= 1;
			}
		} else {
			ptweek = wkpassing*pointsYears[years];
		}
		corValue += Math.min(pointsSkills[passing+1][4]-pointsSkills[passing][4]-1, ptweek);
	}
	if (scoring > 0) {
		var ptweek = 0
		if (wkscoring > yweek) {
			ptweek = (wkscoring - yweek)*pointsYears[years];
			var cut = (wkscoring - yweek);
			var cutyears = years - 1;
			while (cut > 0) {
				ptweek += Math.min(cut,16) * pointsYears[cutyears];
				cut -= 16;
				cutyears -= 1;
			}
		} else {
			ptweek = wkscoring*pointsYears[years];
		}
		corValue += Math.min(pointsSkills[scoring+1][5]-pointsSkills[scoring][5]-1, ptweek);
	}
	
	var pointdiff = 0;
	if (years < 28) {
		// compute the HTMS
		var pointYear = pointsYears[years];
		pointdiff = ((112 - days) / 7) * pointYear;
		for (var i = years + 1; i < 28; i++) {
			pointdiff += 16 * pointsYears[i];
		}
	}
	else {
		// substract
		pointdiff = yweek * pointsYears[years];
		for (var i = years; i > 28; i--) {
			pointdiff += 16 * pointsYears[i];
		}
		pointdiff = -pointdiff;
	}
	var potential = actValue + pointdiff;
	var corPotential = corValue + pointdiff;
	
	return [actValue, Math.round(potential), Math.round(corValue), Math.round(corPotential)];
}