<?php

if( !defined('IN_APP') ) { die('DANGE WILL ROBINSON'); }

class user extends App
{
	public static function init($user)
	{
		
		 
		$js = <<<'HEREWEGO'
    <?php end($changes); $first_key = strtotime(key($changes)) * 1000; $total = current($changes); $first_total = $total['total']; reset($changes); ?>
    $(function ()
    {
      var todo = [];
      var next = [];
      var inprogress = [];
      var done = [];
      var postponed = [];
      
      var stack = true, bars = true, lines = false, steps = false, max = <?php echo strtotime('2012-04-01') * 1000; ?>;
      var burndown = [[<?php echo $first_key; ?>, <?php echo $first_total; ?>], [max, 0]];
      
      <?php
        foreach( $changes as $date => $change )
        {
          $megaseconds = strtotime($date) * 1000;
          unset($change['total']);
          foreach( $change as $type => $val )
          {
            echo $type . '.push([' . $megaseconds . ', ' . $val . ']);';
          }
        }
      ?>
      
      $.plot($("#placeholder"),
      [ {data: todo, label: "Todo"}, 
        {data: next, label: "Next"}, 
        {data: inprogress, label: "Doing"}, 
        {data: done, label: "Done!"}, 
        {data: postponed, label:"On Hold"}, 
        {data: burndown, label:"Burndown", stack: false, color: 'black', lines: {show: true, fill: false, steps: false}, bars: {show: false}}
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
      $('a[rel=tooltip]').tooltip({placement: 'bottom'});
    });
HEREWEGO;
	}
}
