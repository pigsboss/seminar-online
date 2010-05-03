<!-- HUO Zhuoxi (azzuro.huo@gmail.com) -->
/*
Date: 2009-01-14
*/

function check_cellphone(phoneStr) {
	var invalidChars=/[^0-9]/g;
	if(invalidChars.test(phoneStr)) {
		return false;
	}
	if(phoneStr.length!=11) {
		return false;
	}
	return true;
}

function check_telephone(phoneStr) {
	var invalidChars=/[^0-9+-]/g;
	if(invalidChars.test(phoneStr)) {
		return false;
	}
	return true;
}