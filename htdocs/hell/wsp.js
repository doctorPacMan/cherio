var WSPanel = function() {return this.initialize.apply(this,arguments)};
WSPanel.prototype = {
	initialize: function(id) {
		var node = document.getElementById(id),
			text = node.querySelector('textarea'),
			bttn = node.querySelectorAll('button');

		bttn[0].onclick = this.connect.bind(this);
		bttn[1].onclick = this.disconnect.bind(this);
		bttn[2].onclick = this.message.bind(this);

		this._ws = new WebSocket('ws://'+window.location.host+':889');
		this._ws.onopen = this.onSocketOpen.bind(this);
		this._ws.onclose = this.onSocketClose.bind(this);
		this._ws.onerror = this.onSocketError.bind(this);
		this._ws.onmessage = this.onSocketMessage.bind(this);
		console.log('WSPanel',id,text,bttn);
	},
	onSocketOpen: function(event){console.log('onSocketOpen',event);},
	onSocketClose: function(event){console.log('onSocketClose',event);},
	onSocketError: function(event){console.log('onSocketError',event);},
	onSocketMessage: function(event){console.log('onSocketMessage',event);},
	connect: function(){},
	disconnect: function(){},
	message: function(){}
}