{include file='_intro.tpl'}
	<script src="/js/wsp.js"></script>
	<script src="/js/sse.js"></script>

	<div class="wspanel" id="wspanel1">
		<b>CLOSED</b>
		<textarea placeholder=""></textarea>
		<button>Connect</button>
		<button>Message</button>
	</div>
	<div class="wspanel" id="wspanel2">
		<b>CLOSED</b>
		<textarea placeholder=""></textarea>
		<button>Connect</button>
		<button>Message</button>
	</div>
	<div class="wspanel" id="wspanel3">
		<b>CLOSED</b>
		<textarea placeholder=""></textarea>
		<button>Connect</button>
		<button>Message</button>
	</div>

<script>
document.addEventListener('DOMContentLoaded',function(){
	new SSEPanel('wspanel1');
	new SSEPanel('wspanel2');
	new SSEPanel('wspanel3');
});
</script>
{include file='_outro.tpl'}
