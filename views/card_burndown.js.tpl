$(function ()
{
  var todo = [];
  var next = [];
  var inprogress = [];
  var done = [];
  var postponed = [];
  
  var stack = true, bars = false, lines = true, steps = false, max = '{$chart_data.max}';
  //var burndown = [[{$chart_data.first_key}, {$chart_data.total}], [max, 0]];
  
  {foreach from=$changes item=change key=date name=changes_loop}
	{foreach from=$change key=type item=val name=change_loop}
	{if $type != 'js_time'}
	{$type}.push(['{$change.js_time}', '{$val}']);
	{/if}
	{/foreach}
  {/foreach}

  {literal}
  $.plot($("#placeholder"),
  [ {data: todo, label: "Todo"}, 
	{data: next, label: "Next"}, 
	{data: inprogress, label: "Doing"}, 
	{data: done, label: "Done!"}, 
	{data: postponed, label:"On Hold"}, 
	//{data: burndown, label:"Burndown", stack: false, color: 'black', lines: {show: true, fill: false, steps: false}, bars: {show: false}}
  ],
  {
	xaxis: { mode: 'time', max: max },
	legend: { position: 'ne' },
	series:
	{
	  stack: stack,
	  lines: { show: lines, fill: true, steps: steps },
	  bars: { show: bars, barWidth: 1 }
	}
  });
});
{/literal}
