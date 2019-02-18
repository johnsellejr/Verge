<html>
<head>

<link href="<?php echo $this->make_route('/css/bootstrap.min.css') ?>"
	rel="stylesheet" type="text/css" />
<link href="<?php echo $this->make_route('/css/master.css') ?>"
	rel="stylesheet" type="text/css" />
<link href="<?php echo $this->make_route('/css/bootstrapresponsive.min.css') ?>"
	rel="stylesheet" type="text/css" />

<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js">
</script>
<![endif]-->
<meta name="viewport" content="width=device-width,initial-scale=1.0">

</head>

<body>
	<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<a class="btn btn-navbar" data-toggle="collapse"
					datatarget=".nav-collapse"> <span class="icon-bar"></span> <span
					class="icon-bar"></span> <span class="icon-bar"></span>
				</a> <a class="brand"
					href="<?php echo $this->make_route('/')?>">Verge</a>
				<div class="nav-collapse">
					<ul class="nav">
					<li><a href="<?php echo $this->make_route('/') ?>">Home</a></li>
					<?php if (User::is_authenticated()) { ?>
					<li><a href="<?php echo $this->make_route('/user/' . User::current_user()) ?>">My Profile</a></li>
					<li><a href="<?php echo $this->make_route('/logout') ?>">Logout</a></li>
					<?php } else { ?>
					<li><a href="<?php echo $this->make_route('/signup') ?>">Signup</a></li>
					<li><a href="<?php echo $this->make_route('/login') ?>">Login</a></li>
					<?php } ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
	<?php include($this->content); ?>
	<?php echo $this->display_alert('error'); ?>
	<?php echo $this->display_alert('success'); ?>
	</div>
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo $this->make_route('/js/bootstrap.min.js') ?>"></script>
	<script type="text/javascript" src="<?php echo $this->make_route('/js/master.js') ?>"></script>
</body>

</html>