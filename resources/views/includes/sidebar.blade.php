<div class="page-sidebar-wrapper">
		<div class="page-sidebar navbar-collapse collapse">
			
			<!-- BEGIN SIDEBAR MENU -->
			<!-- DOC: for circle icon style menu apply page-sidebar-menu-circle-icons class right after sidebar-toggler-wrapper -->
			<ul class="page-sidebar-menu">
				<li class="sidebar-toggler-wrapper">
					<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
					<div class="sidebar-toggler">
					</div>
					<div class="clearfix">
					</div>
					<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
				</li>
				<li class="sidebar-search-wrapper">
					<form class="search-form" role="form" action="index.html" method="get">
						<div class="input-icon right">
							<i class="icon-magnifier"></i>
							<input type="text" class="form-control" name="query" placeholder="Search...">
						</div>
					</form>
				</li>
				<!-- <li id="dashboard-menu" class="start ">
					<a href="{{url('/dashboard')}}">
					<i class="icon-home"></i>
					<span class="title">Dashboard</span>
					<span class="selected"></span>
					</a>
				</li> -->
				@foreach(Session::get('userinfo')->menu as $key=>$value)
					@if($value['HasSub']=="1")
						<li id="{{$value['ElementID']}}">
							<a href="javascript:;">
							{!! $value['PageGroupIcon'] !!}
							<span class="title">{{$value['PageGroupName']}}</span>
							<span class="arrow "></span>
							<span class="selected"></span>
							</a>
							<ul class="sub-menu">
								@foreach($value['pages'] as $k=>$v)
									@php($pageUrl='#')
									@if($v['PageUrl']!="")
										@php($pageUrl = url($v['PageUrl']))
									@endif
									<li id="{{$v['ElementID']}}">
										<a href="{{$pageUrl}}">
											{{$v['PageName']}}</a>
									</li>
								@endforeach
							</ul>
						</li>
					@else
						<li id="{{$value['ElementID']}}">
							<a href="{{url($value['pages'][0]['PageUrl'])}}">
							{!! $value['PageGroupIcon'] !!}
							<span class="title">{{$value['pages'][0]['PageName']}}</span>
							<span class="selected "></span>
							</a>
						</li>
					@endif
				@endforeach
				
			</ul>
			<!-- END SIDEBAR MENU -->
		</div>
	</div>