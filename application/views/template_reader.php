<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Kurkuma Reader</title>

    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/css/style.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <!-- header bar -->
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Kurkuma</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
		    <li><a id="remove-feed">✘ Remove feed</a></li>
			<li><a id="mark-feed-read">✓ Mark as Read</a></li>
		    <li><a id="add-feed">➕ Add Feed</a></li>
          </ul>
        </div>
      </div>
    </div>

    <!-- content -->
    <div class="container-fluid">
      <div class="row">

        <!-- sidebar -->
        <div class="col-sm-3 col-md-2 sidebar">
          <ul id="feed-list-container" class="nav nav-sidebar">
            <li id="all"><a>All articles</a></li>
			<li id="faves"><a>Favourites</a></li>
          </ul>
          <ul id="feed-list" class="nav nav-sidebar">
          </ul>
        </div>

        <!-- articles -->
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <?=$content?>
		  <div id="articles-footer">❁</div>
        </div>

      </div>
    </div>

    <!-- add feed modal -->
    <div id="modal-add-feed" class="modal hide " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header"><h4 class="modal-title">Add Feed</h4></div>
          <div class="modal-body">
            <form class="form-horizontal" role="form">
			  <label class="control-label" for="url">Feed URL</label>
              <span>
                <input type="text" id="url" name="url" placeholder="">
              </span>
            </form>
          </div>
          <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
            <button class="btn btn-primary">Add Feed</button>
          </div>
        </div>
      </div>
    </div>

	<!-- article row template -->
    <div id="template-row" class="row hide">
      <a class="title"></a>
	  <!--<span class="date hidden-xs hidden-sm"></span>-->
	  <span class="feed-name hidden-xs hidden-sm"></span>
      <div class="info">
		<span class="fave"><a href="">★</a></span>
        <span class="date"></span> &mdash;
        <a href="" class="feed"></a>
      </div>
      <div class="content"></div>
	</div>

    <script src="/js/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/underscore.min.js"></script>
    <script src="/js/main.js"></script>

  </body>
</html>
