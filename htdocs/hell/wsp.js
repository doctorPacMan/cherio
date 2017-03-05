function timeStr(str) {
	var t = new Date(),
		tm = t.getMinutes(),
		ts = t.getSeconds(),
		tt = t.getMilliseconds();
	tt = tt>=100 ? tt : (tt>=10 ? '0'+tt : '00'+tt);
	return (tm>=10?tm:('0'+tm))+':'+(ts>=10?ts:('0'+ts))+'.'+tt;
}
function stringByteLength(str) {
  // returns the byte length of an utf8 string
  var s = str.length;
  for (var i=str.length-1; i>=0; i--) {
    var code = str.charCodeAt(i);
    if (code > 0x7f && code <= 0x7ff) s++;
    else if (code > 0x7ff && code <= 0xffff) s+=2;
    if (code >= 0xDC00 && code <= 0xDFFF) i--; //trail surrogate
  }
  return s;
}
var WSPanel = function() {return this.initialize.apply(this,arguments)};
WSPanel.prototype = {
	initialize: function(id) {
		var node = document.getElementById(id);
		
		this._id = id;
		this._text = node.querySelector('textarea');
		this._cid = node.querySelector('b');
		
		var bttn = node.querySelectorAll('button');
		this._bttn_connect = bttn[0];
		this._bttn_connect.onclick = this.connectToggle.bind(this);
		this._bttn_mgsauth = bttn[1];
		this._bttn_mgsauth.onclick = this.message.bind(this,'auth');
		this._bttn_message = bttn[2];
		this._bttn_message.onclick = this.message.bind(this,'Превед вебсокет!');
		
		//this._ws_addr = 'ws://'+window.location.host+':889/echo';
		this._ws_addr = 'ws://login:psswd@'+window.location.host+':889/echo';
		//this._ws_addr = 'ws://echo.websocket.org';
		this._ws_username = null;
		this._ws_userpass = null;

		this.trace('initialize',this._ws_addr);
		this._readystatechange();
		//this.connect();
	},
	text: function(txt) {
		this._text.value += '['+timeStr()+'] '+(txt)+'\r\n';
	},
	_readystatechange: function(event) {
		
		var st = this.state();
		if(st===this._state) return;
		else {
			this._cid.innerText = !this._state ? st : this._state+' > '+st;
			this._state = st;
		}
		
		if(st=='CONNECTING') this._bttn_connect.value = 'Connecting';
		else if(st==='OPEN') this._bttn_connect.value = 'Disconnect';
		else this._bttn_connect.value = 'Connect';

		this._bttn_connect.childNodes[0].nodeValue = this._bttn_connect.value;
		this._bttn_connect[st==='CONNECTING'?'setAttribute':'removeAttribute']('disabled','disabled');
		this._bttn_message[st!=='OPEN'?'setAttribute':'removeAttribute']('disabled','disabled');
		this._bttn_mgsauth[st!=='OPEN'?'setAttribute':'removeAttribute']('disabled','disabled');
		
		//this.text(st);
		//console.log('onReadyStateChange', this._state);
	},
	onSocketOpen: function() {
		console.log('onSocketOpen');
		//this.message('auth');
	},
	onSocketMessage: function(event){
		console.log('onSocketMessage', event.data);
		
		var res_message = event.data || '',
			bts = stringByteLength(res_message);
		this.text('received '+bts+'bytes message');
		
		var msjson = {}, action = 'none', msdata = {};
		try{msjson = JSON.parse(res_message)}catch(e){console.error('JSON.parse 0 error:\r\n'+res_message+'')};
		//try{msdata = JSON.parse(msjson.data)}catch(e){console.error('JSON.parse 1 error:\r\n'+msjson.data+'')};
		msdata = msjson.data;
		if(msjson.action) action = msjson.action;

		this.trace(action, msdata);
		this._readystatechange();
	},
	connectToggle: function() {
		var st = this.state();
		return st==='OPEN' ? this.disconnect() : this.connect();
	},
	connect: function(){
		if(this._ws) this.disconnect();
		var ws = new WebSocket(this._ws_addr);
		ws.onopen = this._readystatechange.bind(this);
		ws.onclose = this._readystatechange.bind(this);
		ws.onerror = this._readystatechange.bind(this);
		ws.addEventListener('open',this.onSocketOpen.bind(this));
		ws.addEventListener('message',this.onSocketMessage.bind(this));
		this._ws = ws;
		this._readystatechange();
		return this;
	},
	disconnect: function(){
		if(this._ws) this._ws.close();
		delete this._ws;
		return this;
	},
	message: function(msg){
		if(!this._ws) return console.log('no socket');
		//console.log(this.state(), 'message:', msg);
		this._ws.send(msg);
	},
	state: function() {
		if(!this._ws) return 'CLOSED';
		else return ['CONNECTING','OPEN','CLOSING','CLOSED'][this._ws.readyState];

	},
	trace: function() {
		var args = Array.prototype.slice.call(arguments);
		args.unshift('[#'+this._id+' at '+timeStr()+']');
		console.log.apply(console,args);
	}
}