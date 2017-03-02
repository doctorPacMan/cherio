var WSPanel = function() {return this.initialize.apply(this,arguments)};
WSPanel.prototype = {
	initialize: function(id) {
		var node = document.getElementById(id),
			text = node.querySelector('textarea'),
			bttn = node.querySelectorAll('button');

		bttn[0].onclick = this.connect.bind(this);
		bttn[1].onclick = this.disconnect.bind(this);
		bttn[2].onclick = this.message.bind(this);

		console.log('WSPanel',id,text,bttn);
	},
	connect: function(){},
	disconnect: function(){},
	message: function(){}
}