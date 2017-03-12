var SSEPanel = function() {return this.initialize.apply(this,arguments)};
SSEPanel.prototype = {
	initialize: function(id) {
		var node = document.getElementById(id);
		
		this._id = id;
		this._text = node.querySelector('textarea');
		this._node_stat = node.querySelector('b');
		
		var bttn = node.querySelectorAll('button');
		this._bttn_connect = bttn[0];
		this._bttn_connect.onclick = this.connectToggle.bind(this);
		
		this.clientid = Math.random().toString(36).substr(2);
		this._es_addr = 'http://'+window.location.host+'/sse/?echo&cid='+this.clientid;

		//this.trace('initialize',this._es_addr);
		this._state = null;
		this._readystatechange();
		//this.connect();
	},
	text: function(txt) {
		this._text.value += '['+timeStr()+'] '+(txt)+'\r\n';
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
	_readystatechange: function(event) {
		//return console.log('state: '+this.state()+' '+(event?event.type:''));
		var ost = this._state,
			st = this.state();
		if(st === ost) return;
		else this._state = st;
		
		//console.log('onReadyStateChange',ost+' > '+st);
		this._node_stat.innerText = !ost ? st : (ost+' > '+st);

		if(st=='CONNECTING') this._bttn_connect.value = 'Connecting';
		else if(st==='OPEN') this._bttn_connect.value = 'Disconnect';
		else this._bttn_connect.value = 'Connect';
		this._bttn_connect[st==='CONNECTING'?'setAttribute':'removeAttribute']('disabled','disabled');
		this._bttn_connect.childNodes[0].nodeValue = this._bttn_connect.value;

	},
	_messagerecieved:function(e) {
		//return console.log(this.state(), event.type);
		var data_msg = e.data,
			data_str = data_msg.replace(/\r\n/g,'\n'),
			data_arr = data_str.split('\n');

		var data_obj = {}, tmp;
		data_arr.forEach(function(itm){tmp=itm.split('=');data_obj[tmp[0]] = tmp[1]});
		
		if(this.clientid!==data_obj.cid) return;
		
		this.text(data_obj.time+' '+data_obj.runtime);
		//console.log('message', data_obj);
		
		//console.log('message',data_arr.join('; '));
	},
	connect: function(){
		if(this._es) this.disconnect();
		
		var es = new EventSource(this._es_addr);
		es.addEventListener("open",this._readystatechange.bind(this));
		es.addEventListener("error",this._readystatechange.bind(this));
		es.addEventListener("message",this._messagerecieved.bind(this));
		this._es = es;
		return this;
	},
	disconnect: function(){
		if(this._es) this._es.close();
		this._readystatechange();
		delete this._es;
		return this;
	},
	message: function(msg){
		if(!this._ws) return console.log('no socket');
		//console.log(this.state(), 'message:', msg);
		this._ws.send(msg);
	},
	state: function() {
		if(!this._es) return 'CLOSED';
		else return ['CONNECTING','OPEN','CLOSED'][this._es.readyState];
	},
	trace: function() {
		var args = Array.prototype.slice.call(arguments);
		args.unshift('[#'+this._id+' at '+timeStr()+']');
		console.log.apply(console,args);
	}
}