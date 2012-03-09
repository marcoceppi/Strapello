      <footer class="footer">
        <p class="pull-right"><a href="#">Back to top</a></p>
	<p>We're pissing off Trello! This page made {$API_COUNT} calls to the Trello API.<br>
    <i class="icon-heart"></i>Entire codebase is Open Source (<a href="http://www.gnu.org/licenses/agpl.html" target="_blank">AGPLv3</a>) and available on <a href="http://github.com/marcoceppi/Strapello">GitHub</a></p>
      </footer>
    </div>
    <script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/flot/jquery.flot.min.js"></script>
    <script src="assets/flot/jquery.flot.stack.min.js"></script>
    <script src="assets/js/app.js"></script>
    <script type="text/javascript">
    {literal}
    $(function(){ $('a[rel=tooltip]').tooltip({placement: 'bottom'}); });
    {/literal}
	{$JS}
    </script>
  </body>
</html>
