<h3><i>&mu;</i>MVC Team</h3>
<p>WARNING: Some names appearing here could be my imaginary friends. (ASK)</p>
{block name="contributor"}
	<p>{$role}:</p>
	<p><b>{$firstname} {$lastname}</b> &lt; {$email} &gt;</p>
	{block name="responsabilities"}
	<p>Responsabilities: 
	{block name="responsability"}
		<div style="color: black; margin-left: 5px; float: left; background-color: #FFE; border: 1px solid black; border-radius: 3px;">
		{$responsability}
		</div>
	{/block}
	</p>
	<br style="clear: both;" />
	{/block}
{/block}