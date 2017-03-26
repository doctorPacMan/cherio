var AppDuel = function(){return this.initialize.apply(this,arguments)};
AppDuel.prototype = {
initialize: function() {
	var form = document.getElementById('knblsform'),
		btns = form.querySelectorAll('fieldset > button[name="spell"]');
	
	form.addEventListener('submit',this.submit.bind(this));
	for(var i=0;i<btns.length;i++) btns[i].addEventListener('click',this.click.bind(this));

	console.log(btns);
},
submit: function(e) {
	e.preventDefault();
	e.stopImmediatePropagation();
	
	console.log(e.target);
	
	var data = new FormData(e.target);
	console.log(data);
},
click: function(e) {
	//e.preventDefault();
	//e.stopImmediatePropagation();
	console.log(e.target.value);
	ajaxRequest('./?spell='+e.target.value,function(){},function(){});
}
}
document.addEventListener('DOMContentLoaded',function(){new AppDuel})