function ajaxRequest(url, onSuccess, onFailure) {

	var xhr = new XMLHttpRequest(),
		body = undefined;
	//var body = 'name='+encodeURIComponent(name)+'&surname='+encodeURIComponent(surname);
	
	xhr.open('POST', url, true);
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhr.onreadystatechange = function(e) {
		var xhr = e.target;
		if(xhr.readyState!=4) return;// console.log(xhr.readyState, xhr.status);
		else console.log(xhr.responseText);
		//else if(xhr.status == 200) return onSuccess();
		//else onFailure();			
	};
	xhr.send(body);
}