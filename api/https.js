function requestHTTPS(urlStr, dataJson, mrd) {
	//console.log(urlStr);
	var oRequest = new XMLHttpRequest();
 	var sURL = urlStr;
	try {
		oRequest.open('POST',sURL,false);
 		oRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	}
	catch(err) {
		alert('0x02:'+err.message);
	}
	oRequest.send(JSON.stringify(dataJson));
 	if (oRequest.status == 200) {
		if (mrd) {
			return JSON.parse(oRequest.responseText);
 		}
		else {
			return oRequest.responseText;
		}
	}
	else {
		return ('Error executing XMLHttpRequest call!');
  	}
}