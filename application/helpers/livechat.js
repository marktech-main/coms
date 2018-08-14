var __lc = {};

__lc.license = 5061861;



(function() {
	var lc = document.createElement('script'); lc.type = 'text/javascript'; lc.async = true;
	lc.src = 'http://cdn.livechatinc.com/tracking.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(lc, s);
})();


var LC_API = LC_API || {};
LC_API.on_before_load = function() {
	console.log('on_before_load');
};
LC_API.on_after_load = function() {
	console.log('on_after_load');
};
LC_API.on_chat_state_changed = function(data) {
	console.log('Chat state changed to: ' + data.state);
};
LC_API.on_chat_window_opened = function() {
	console.log('on_chat_window_opened');
};
LC_API.on_chat_window_minimized = function() {
	/*$.ajax({
		url: '__brownies.php',
		type: 'GET',
		cache: false,
		success: function(resp) {
			console.log('response : ' + resp);
		}
	});*/
	console.log('on_chat_window_minimized');
};
LC_API.on_chat_started = function(data) {
	/* LIVECHAT TRACKER V2 */
	$.ajax({
		url: 'tracker_lc.php',
		type: 'GET',
		cache: false,
		success: function(resp) {
			console.log('response : ' + resp);
		}
	});
	$.ajax({
		url: '__brownies.php',
		type: 'GET',
		cache: false,
		success: function(resp) {
			console.log('response : ' + resp);
		}
	});
	console.log('on_chat_started');
};
LC_API.on_chat_ended = function() {
	console.log('on_chat_ended');
};
