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