var WSPanel = function() {return this.initialize.apply(this,arguments)};
WSPanel.prototype = {
	initialize: function(id) {
		var node = document.getElementById(id),
			text = node.querySelector('textarea'),
			bttn = node.querySelectorAll('button');

		bttn[0].onclick = this.connect.bind(this);
		bttn[1].onclick = this.disconnect.bind(this);
		bttn[2].onclick = this.message.bind(this,'Hey! Socket!');

		this._ws_addr = 'ws://'+window.location.host+':889/echo';
		//this._ws_addr = 'ws://echo.websocket.org';
		//this.connect();

		console.log('WSPanel',id,text,bttn);
	},
	onSocketOpen: function(event){
		console.log('onSocketOpen',event);
		//this.message('Hey!');
	},
	onSocketClose: function(event){console.log('onSocketClose',event)},
	onSocketError: function(event){console.log('onSocketError',event)},
	onSocketMessage: function(event){
		console.log('onSocketMessage', event.data);
	},
	connect: function(){
		if(this._ws) this._ws.close();
		
		var ws = new WebSocket(this._ws_addr);
		ws.onopen = this.onSocketOpen.bind(this);
		ws.onclose = this.onSocketClose.bind(this);
		ws.onerror = this.onSocketError.bind(this);
		ws.onmessage = this.onSocketMessage.bind(this);
		return this._ws = ws;
	},
	disconnect: function(){if(this._ws) this._ws.close()},
	message: function(msg){
		if(!this._ws) return console.log('no socket');
		console.log(this.state(), 'message:', msg);
		this._ws.send(msg);
	},
	state: function() {
		return ['CONNECTING','OPEN','CLOSING','CLOSED'][this._ws.readyState];

	}
}