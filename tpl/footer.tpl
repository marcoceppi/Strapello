      <footer class="footer">
        <p class="pull-right"><a href="#">Back to top</a></p>
	<p>We're pissing off Trello! This page made <?php echo $api_count; ?> calls to the Trello API. <3</p>
      </footer>
    </div>
    <script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script src="tpl/bootstrap/js/bootstrap.min.js"></script>
    <script src="tpl/flot/jquery.flot.min.js"></script>
    <script src="tpl/flot/jquery.flot.stack.min.js"></script>
    <script src="tpl/bootstrap/js/app.js"></script>
    <script type="text/javascript">
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
    });
    </script>
  </body>
</html>
