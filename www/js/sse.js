var es = new EventSource("./?echo");
var es_listener = function (event) {
	var s = '['+event.type+']';
	s += (typeof event.data !== 'undefined') ? event.data : '';
	console.log(s);
};
es.addEventListener("open", es_listener);
es.addEventListener("message", es_listener);
es.addEventListener("error", es_listener);
