{include file="header.tpl" title="Strapello Board Breakdown for {$board.name}"}
  <body data-spy="scroll" data-target=".navbar">
    <div class="container">
      <header class="jumbotron masthead" id="overview">
        <div class="inner">
          <h1>{$board.name}</h1>
        </div>
      <br>
	  <div class="navbar"><div class="navbar-inner"><div class="container">
		<a class="brand" href="#">Strapello</a>
		<ul class="nav">
		  <!-- <li><a href="#progress">Progress</a></li> -->
		  <li><a href="#breakdown">Breakdown</a></li>
		  <li><a href="#members">Members</a></li>
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
            <div class="bar" style="width:{$percent.done}%;"></div>
            <div class="bar progress-info active" style="width:{$percent.doing}%;"></div>
            </div>
            <span>{$percent.done}% complete with {$total.doing} items in progress ({$percent.doing}%) and {$total.todo} items remaining.</span>
          </div>
        </div>
      </div>
    </section>
    <section id="members">
      <div>
        <h2>Members</h2>
      </div>
      <div class="row">
        {foreach from=$members item=member name=mem}
        <div class="span4 well">
          <h3><i class="icon-user"></i> <a href="user/{$member.data.username}">{$member.data.fullName}</a></h3>
          <ul>
		{foreach from=$member.stats item=val key=kind name=meminner}
            <li>{$kind}: {$val}</li>
		{/foreach}
          </ul>
        </div>
        {if $smarty.foreach.mem.iteration % 3 == 0}
        </div><div class="row">
        {/if}
        {/foreach}
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
            <li><a href="{$board.data.url}" target="_blank" rel="tooltip" title="Open Board in Trello" alt="Open Board in Trello"><i class="icon-th-large"></i></a> {$board.data.name} ( {$board.stats.done} / {$board.stats.total} )</li>
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
