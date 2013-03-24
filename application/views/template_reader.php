<!DOCTYPE html>
<html>
<head>
	<title>Kurkuma Feedreader</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
    <link href="/css/style.css" rel="stylesheet">
</head>
<body>

<div class="navbar navbar-inverse navbar-fixed-top">
  <div class="navbar-inner">
	<div class="container">
	  <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
	  </button>
	  <a class="brand" href="/">Kurkuma</a>
	  <div class="nav-collapse collapse">
		<ul class="nav">
		  <li><a id="mark-feed-read"><i class="icon-ok icon-white"></i> Mark as Read</a></li>
		  <li><a id="add-feed"><i class="icon-plus icon-white"></i> Add Feed</a></li>
		</ul>
	  </div><!--/.nav-collapse -->
	</div>
  </div>
</div>

<ul id="feed-list-container" class="nav nav-list affix" style="width: 150px;">
	<li id="all"><a>All articles</a></li>
	<li class="nav-header">Feeds</li>
	<span id="feed-list"></span>
</ul>

<div class="container">
	<?=$content?>
</div>

<div id="modal-add-feed" class="modal hide " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header"><h3>Add Feed</h3></div>
	<div class="modal-body">
		<form class="form-horizontal">
			<div class="control-group">
				<label class="control-label" for="url">Feed URL</label>
				<div class="controls">
					<input type="text" id="url" name="url" placeholder="">
				</div>
			</div>
		</form>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
		<button class="btn btn-primary">Add Feed</button>
	</div>
</div>

<script src="/js/jquery.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/underscore.min.js"></script>
<script src="/js/backbone.js"></script>
<script src="/js/main.js"></script>
</body>
</html>
