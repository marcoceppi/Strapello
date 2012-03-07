{include file="header.tpl" title="Login to Ondina CP"}
  <body data-spy="scroll" data-target=".navbar">
    <div class="container">
      <header class="jumbotron masthead" id="overview">
        <div class="inner">
          <h1><img src="https://trello-avatars.s3.amazonaws.com/<?php echo $user['avatarHash']; ?>/170.png">&nbsp;<?php echo $user['fullName']; ?></h1>
        </div>
      <br>
	  <div class="navbar"><div class="navbar-inner"><div class="container">
		<a class="brand" href="#">Strapello</a>
		<ul class="nav">
		  <!-- <li><a href="#progress">Progress</a></li> -->
		  <li><a href="#breakdown">Breakdown</a></li>
		  <li><a href="#tasks">Tasks</a></li>
		</ul>
	  </div></div></div>
      </header>
    <section id="breakdown">
      <div>
        <h2>Breakdown</h2>
      </div>
      <div class="row">
        <div class="span12">
          <div id="placeholder" style="width:100%;height:400px;"></div>
        </div>
      </div>
      <br>
      <div>
      <!--  <h1>Overall Progress</h1> -->
      </div>
      <div class="row">
        <div class="span12" id="total_progress">
          <div class="progress">
            <div class="stacked">
            <div class="bar" style="width:<?php echo $percent_done; ?>%;"></div>
            <div class="bar progress-info active" style="width:<?php echo $percent_doing; ?>%;"></div>
            </div>
            <span><?php echo $percent_done; ?>% complete with <?php echo $doing; ?> items in progress (<?php echo $percent_doing; ?>%) and <?php echo count($todo);?> items remaining.</span>
          </div>
        </div>
      </div>
    </section>
    <section id="tasks">
      <div>
        <h2>Tasks</h2>
      </div>
      <div class="row">
        <div class="span4 well">
          <h3>Boards</h3>
          <ul>
          <?php foreach($boards as $board) { ?>
            <li><a href="<?php echo $board['data']['url']; ?>" target="_blank" rel="tooltip" title="Open Board in Trello" alt="Open Board in Trello"><i class="icon-th-large"></i></a> <?php echo truncate($board['data']['name'], 35); ?> ( <?php echo $board['stats']['done']; ?> / <?php echo $board['stats']['total']; ?> )</li>
          <?php } ?>
          </ul>
        </div>
        <div class="span4 well">
          <h3>Todo</h3>
          <ul>
          <?php foreach($todo as $item) { ?>
            <li><a href="<?php echo $item['url']; ?>" target="_blank" rel="tooltip" title="Open this Card in Trello" alt="Open this Card in Trello"><i class="icon-list-alt"></i></a> <?php echo truncate($item['name'], 35); ?></li>
          <?php } ?>
          </ul>
        </div>
        <div class="span4 well">
          <h3>Next</h3>
          <ul>
          <?php foreach($next as $item) { ?>
            <li><a href="<?php echo $item['url']; ?>" target="_blank"  rel="tooltip" title="Open this Card in Trello" alt="Open this Card in Trello"><i class="icon-list-alt"></i></a> <?php echo truncate($item['name'], 35); ?></li>
          <?php } ?>
          </ul>
        </div>
        <div class="span4 well" style="float:right;">
          <h3>In Progress</h3>
          <ul>
          <?php foreach($inprogress as $item) { ?>
            <li><a href="<?php echo $item['url']; ?>" target="_blank" rel="tooltip" title="Open this Card in Trello" alt="Open this Card in Trello"><i class="icon-list-alt"></i></a> <?php echo truncate($item['name'], 35); ?></li>
          <?php } ?>
          </ul>
        </div>
      </div>
    </section>
<?php
require_once('footer.tpl');
