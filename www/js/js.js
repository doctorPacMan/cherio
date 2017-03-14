function sendAjax(src,data,callback) {
	
	var body = 'data='+encodeURIComponent(data);
	//body += '&foo='+encodeURIComponent('fu');

	var xhr = new XMLHttpRequest();
	xhr.open('POST', src, true);
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=utf-8');
	xhr.onreadystatechange = function() {
		if (xhr.readyState!=4 || xhr.status!=200) return;
		var text = xhr.responseText,
			json = JSON.parse(text);
		if(typeof callback == 'function') callback(json);
		else console.log(json);
	}
	xhr.send(body);
}