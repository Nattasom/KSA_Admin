<div class="header navbar navbar-fixed-top">
	<!-- BEGIN TOP NAVIGATION BAR -->
	<div class="header-inner">
		<!-- BEGIN LOGO -->
		<div class="page-logo">
            <a href="index.html">
<img src="{{Config::get('app.root_path')}}/resources/assets/img/logo_land.jpeg" height="40" style="margin: 2px 15px 0 25px;" alt="logo" />
            </a>
        </div>
        <!-- <form class="search-form search-form-header" role="form" action="index.html">
            <div class="input-icon right">
                <i class="icon-magnifier"></i>
                <input type="text" class="form-control input-sm" name="query" placeholder="Search...">
            </div>
        </form> -->
		<!-- END LOGO -->
		<!-- BEGIN RESPONSIVE MENU TOGGLER -->
		<a href="javascript:;" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
		<img src="{{Config::get('app.root_path')}}/resources/assets/img/menu-toggler.png" alt=""/>
		</a>
		<!-- END RESPONSIVE MENU TOGGLER -->
		<!-- BEGIN TOP NAVIGATION MENU -->
		<ul class="nav navbar-nav pull-right">
			<!-- BEGIN NOTIFICATION DROPDOWN -->
			<li class="dropdown" id="header_notification_bar">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
				<i class="icon-bell"></i>
				<span class="badge badge-success">
				6 </span>
				</a>
				<ul class="dropdown-menu extended notification">
					<li>
						<p>
							 You have 14 new notifications
						</p>
					</li>
					<li>
						<ul class="dropdown-menu-list scroller" style="height: 250px;">
							<li>
								<a href="#">
								<span class="label label-sm label-icon label-success">
								<i class="fa fa-plus"></i>
								</span>
								New user registered. <span class="time">
								Just now </span>
								</a>
							</li>
							<li>
								<a href="#">
								<span class="label label-sm label-icon label-danger">
								<i class="fa fa-bolt"></i>
								</span>
								Server #12 overloaded. <span class="time">
								15 mins </span>
								</a>
							</li>
							<li>
								<a href="#">
								<span class="label label-sm label-icon label-warning">
								<i class="fa fa-bell"></i>
								</span>
								Server #2 not responding. <span class="time">
								22 mins </span>
								</a>
							</li>
							<li>
								<a href="#">
								<span class="label label-sm label-icon label-info">
								<i class="fa fa-bullhorn"></i>
								</span>
								Application error. <span class="time">
								40 mins </span>
								</a>
							</li>
							<li>
								<a href="#">
								<span class="label label-sm label-icon label-danger">
								<i class="fa fa-bolt"></i>
								</span>
								Database overloaded 68%. <span class="time">
								2 hrs </span>
								</a>
							</li>
							<li>
								<a href="#">
								<span class="label label-sm label-icon label-danger">
								<i class="fa fa-bolt"></i>
								</span>
								2 user IP blocked. <span class="time">
								5 hrs </span>
								</a>
							</li>
							<li>
								<a href="#">
								<span class="label label-sm label-icon label-warning">
								<i class="fa fa-bell"></i>
								</span>
								Storage Server #4 not responding. <span class="time">
								45 mins </span>
								</a>
							</li>
							<li>
								<a href="#">
								<span class="label label-sm label-icon label-info">
								<i class="fa fa-bullhorn"></i>
								</span>
								System Error. <span class="time">
								55 mins </span>
								</a>
							</li>
							<li>
								<a href="#">
								<span class="label label-sm label-icon label-danger">
								<i class="fa fa-bolt"></i>
								</span>
								Database overloaded 68%. <span class="time">
								2 hrs </span>
								</a>
							</li>
						</ul>
					</li>
					<li class="external">
						<a href="#">See all notifications <i class="fa fa-angle-right"></i></a>
					</li>
				</ul>
			</li>
			<!-- END NOTIFICATION DROPDOWN -->
			<li class="devider">
				 &nbsp;
			</li>
			<!-- BEGIN USER LOGIN DROPDOWN -->
			<li class="dropdown user">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
				<!-- <img alt="" src="{{Config::get('app.root_path')}}/resources/assets/img/avatar3_small.jpg"/> -->
				<span class="username username-hide-on-mobile">{{Session::get('userinfo')->Username}} </span>
				<i class="fa fa-angle-down"></i>
				</a>
				<ul class="dropdown-menu">
					<li>
						<a href="#"><i class="fa fa-lock"></i> Change Password</a>
					</li>
					<li class="divider">
					</li>
					<li>
						<a href="{{url('/logout')}}"><i class="fa fa-key"></i> Log Out</a>
					</li>
				</ul>
			</li>
			<!-- END USER LOGIN DROPDOWN -->
		</ul>
		<!-- END TOP NAVIGATION MENU -->
	</div>
	<!-- END TOP NAVIGATION BAR -->
</div>