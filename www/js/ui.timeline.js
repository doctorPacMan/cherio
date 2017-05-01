document.addEventListener('DOMContentLoaded',function(){
	var u = document.querySelector('u.timeline');
	if(u) console.log(new uiTimeline(u));
});
document.addEventListener('DOMContentLoaded',function(){
	var u = document.querySelector('u.timecirc');
	if(u) console.log(new uiTimecircle(u));
});
var uiTimeline = function(){return this.initialize.apply(this,arguments)};
uiTimeline.prototype = {
	now: function(){return new Date().getTime()},
	initialize: function(u) {

		var now = new Date().getTime();
		this._time = new Date(this.now() - 1000*0);
		this._ends = new Date(this.now() + 1000*12);
		console.log(this._time, this._ends);

		this._node = u;
		u.addEventListener('click',this.pause.bind(this,null),false);
		//u.addEventListener('contextmenu',this.click.bind(this,null),false); 
		//this.valueRel(.522);

		//this.valueAbs(new Date(this.now() + 1000*2));


		this.duration(30);
		return u;
	},
	duration: function(s) {

		var now = new Date().getTime();
		this._time = new Date(now);
		this._ends = new Date(now + 1000*s);
		this._node.style.backgroundSize = '0% 100%';
		this._node.style.animationDuration = s+'s';

	},
	valueAbs: function(t) {

		var t = new Date(t),
			d = this._ends - this._time,
			p = this._ends - t.getTime(),
			pv = (1 - p/d),
			pr = Math.round(pv*100)/100;

		this.valueRel(pv);
		console.log(pr, t);
	},
	valueRel: function(p) {
		
		if(isNaN(p)) p = 0;
		else if(p<0) p = 0;
		else if(p<1) p = Math.round(p*100)/100;
		else if(p<100) p = Math.round(p)/100;
		else p = 1;

		var d = this._ends - this._time,
			rt = this.now() + d*p;
		
		console.log(p, new Date(rt));
		
		this._node.style.backgroundSize = Math.round(p*100)+'% 100%';
	},
	click: function(st,e) {
		e.preventDefault();
		console.log(e);

		var node = this._node,
			prnt = this._node.parentNode;
		prnt.removeChild(this._node);
		setTimeout(function(){
			prnt.appendChild(node);
		},1000);
	},
	pause: function(st,e) {
		var running = this._node.style.animationPlayState=='running',
			apstate = running ? 'paused' : 'running';
		this._node.style.animationPlayState = apstate;
		console.log(apstate);
	}
};

var uiTimecircle = function(){return this.initialize.apply(this,arguments)};
uiTimecircle.prototype = {
	now: function(){return new Date().getTime()},
	initialize: function(u) {
		
		var node = u,
			lft = document.createElement('s'),
			rgt = document.createElement('s'),
			ssr = document.createElement('s'),
			ssl = document.createElement('s'),
			txt = document.createElement('b');
			
		lft.appendChild(ssl);
		rgt.appendChild(ssr);
		node.appendChild(lft);
		node.appendChild(rgt);
		node.appendChild(txt);

		txt.innerText = 'timer';

		this.node = node;
		this.txt = txt;
		this._wl = lft;
		this._wr = rgt;
		this._sl = ssl;
		this._sr = ssr;

		//u.addEventListener('click',this.click.bind(this,null),false);
		u.onclick = this.onclick.bind(this);

		var ts = node.getAttribute('data-start'),
			tn = node.getAttribute('data-ends'),
			tt = node.getAttribute('data-timer'),
			t = parseFloat(tt);
		
		//console.log('data-timer', tt, t);
		//this.duration(tr);

		this.setInterval(ts,tn);
		//this.valueRel(.25);
		
		return u;
	},
	onclick:function(){
		//this._sl.style.animationPlayState = this._sr.style.animationPlayState = 'running';
		var running = this._sl.style.animationPlayState=='running',
			apstate = running ? 'paused' : 'running';
		this._sl.style.animationPlayState = this._sr.style.animationPlayState = apstate;
	},
	setInterval: function(ts, tn) {
		var bgn = new Date(ts*1000),
			end = new Date(tn*1000),
			now = new Date(),
			ttl = end - bgn,
			rv = (now - bgn)/ttl;
		
		console.log('now', now);
		console.log('bgn', bgn);
		console.log('end', end);
		console.log(ttl, rv);
		
		this._bgn = bgn;
		this._end = end;
		this._duration = Math.round(ttl/1000);
		//this.valueRel(rv);
		this.valueAbs(now);
		
	},
	valueAbs: function(t) {
		var t = new Date(t),
			bgn = this._bgn,
			end = this._end,
			rv = (t - bgn)/(end - bgn);
		this.valueRel(rv);
	},
	valueRel: function(v) {
		//var v = .5; 
		//this._duration = 12;
		var v = parseFloat(v),
			tt = this._duration,
			lt = 0, rt = 0, // time
			dr = 0, dl = 0; // angle
		
		if(v<=.5){
			dr = 360 * v;
			lt = tt/2;
			rt = lt - (v * tt);
		}
		else {
			lt = tt - tt*v;
			dr = 180;
			dl = 360 * (1-v);
		}

		lt = Math.round(lt*100)/100;rt = Math.round(rt*100)/100;
		dl = Math.round(dl*100)/100;dr = Math.round(dr*100)/100;

		this._sr.style.transform = 'rotate('+dr+'deg)';
		this._sl.style.transform = 'rotate('+dl+'deg)';
		this._sr.style.animationDuration = rt+'s';
		this._sl.style.animationDuration = lt+'s';
		this._sl.style.animationDelay = rt+'s';
		//this._sl.style.animationPlayState = this._sr.style.animationPlayState = 'running';
		
		console.log(tt,v,rt, lt+'|'+rt, dl+'|'+dr);

	},
	sec2time: function(sec) {
		var time='', s=sec, m, h;
		if(false) h = 0; // 000:00
		else h = Math.floor(s/3600);
		m = Math.floor((s = s - h*3600)/60);
		s = Math.floor(s - m*60);
		if(h>0)time += (h + ':');
		time += (m<10?('0'+m):m)+':'+(s<10?('0'+s):s);
		return time;
	},
	onTime: function(t) {
		if(t<=0) clearInterval(this._timer);
		this.txt.innerText = this.sec2time(t);
	},
	oncomplete: function() {
		
	},
	click: function() {
		this.duration(4);		
	},
	duration: function(t) {

		if(this._timer) clearInterval(this._timer);
		this.txt.innerText = t;
		this.node.appendChild(this._wl);
		this.node.appendChild(this._wr);

		var rt = t/2;
		this._sl.style.animationDelay = rt+'s';
		this._sl.style.animationDuration = rt+'s';
		this._sr.style.animationDuration = rt+'s';
		
		this._sl.style.animationPlayState = 'running';
		this._sr.style.animationPlayState = 'running';

		//this.dura = t;
		var cbk = this.onTime.bind(this);this.onTime(t);
		this._timer = setInterval(function(){cbk(--t)},1000);
	}
};