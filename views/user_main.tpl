{include file="header.tpl" title="Strapello Breakdown for {$user.fullName}"}
  <body data-spy="scroll" data-target=".navbar">
    <div class="container">
      <header class="jumbotron masthead" id="overview">
        <div class="inner">
          <h1><img src="https://trello-avatars.s3.amazonaws.com/{$user.avatarHash}/170.png">&nbsp;{$user.fullName}<small>{$user.bio}</small></h1>
        </div>
      <br>
	  <div class="navbar"><div class="navbar-inner"><div class="container">
		<a class="brand" href="#">Strapello</a>
		<ul class="nav">
		  <!-- <li><a href="#progress">Progress</a></li> -->
		  <li><a href="#breakdown">Breakdown</a></li>
		  <li><a href="#tasks">Tasks</a></li>
		</ul>
		<ul class="nav pull-right">
		  <li><a href="#connect" id="trelloConnect">Connect with Trello</a></li>
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
            <div class="bar" style="width:{$percent.done}%;"></div>
            <div class="bar progress-info active" style="width:{$percent.doing}%;"></div>
            </div>
            <span>{$percent.done}% complete with {$total.doing} items in progress ({$percent.doing}%) and {$total.todo} items remaining.</span>
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
		{foreach from=$boards item=board}
            <li><a href="{$board.data.url}" target="_blank" rel="tooltip" title="Open Board in Trello" alt="Open Board in Trello"><i class="icon-th-large"></i></a> <a href="report/board/{$board.data.id}">{$board.data.name}</a> ( {$board.stats.done} / {$board.stats.total} )</li>
		{/foreach}
          </ul>
        </div>
        <div class="span4 well">
          <h3>Todo</h3>
          <ul>
          {foreach from=$todo item=item}
            <li><a href="{$item.url}" target="_blank" rel="tooltip" title="Open this Card in Trello" alt="Open this Card in Trello"><i class="icon-list-alt"></i></a> {$item.shortname}</li>
          {/foreach}
          </ul>
        </div>
        <div class="span4 well">
          <h3>Next</h3>
          <ul>
          {foreach from=$next item=item}
            <li><a href="{$item.url}" target="_blank" rel="tooltip" title="Open this Card in Trello" alt="Open this Card in Trello"><i class="icon-list-alt"></i></a> {$item.shortname}</li>
          {/foreach}
          </ul>
        </div>
        <div class="span4 well" style="float:right;">
          <h3>In Progress</h3>
          <ul>
          {foreach from=$inprogress item=item}
            <li><a href="{$item.url}" target="_blank" rel="tooltip" title="Open this Card in Trello" alt="Open this Card in Trello"><i class="icon-list-alt"></i></a> {$item.shortname}</li>
          {/foreach}
          </ul>
        </div>
      </div>
    </section>
{include file="footer.tpl"}
