
<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head><base href="">
		<meta charset="utf-8" />
		<title>Analysis</title>
		<meta name="description" content="Updates and statistics" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		<!--begin::Fonts-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Page Vendors Styles(used by this page)-->
		<link href="{{ asset('designs/assets/plugins/custom/fullcalendar/fullcalendar.bundle.css?v=7.0.5') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('designs/assets/plugins/custom/leaflet/leaflet.bundle.css?v=7.0.5') }}" rel="stylesheet" type="text/css" />
		<!--end::Page Vendors Styles-->
		<!--begin::Global Theme Styles(used by all pages)-->
		<link href="{{ asset('designs/assets/plugins/global/plugins.bundle.css?v=7.0.5') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('designs/assets/plugins/custom/prismjs/prismjs.bundle.css?v=7.0.5') }}" rel="stylesheet" type="text/css" />
		<link href="{{ asset('designs/assets/css/style.bundle.css?v=7.0.5') }}" rel="stylesheet" type="text/css" />
		<style>
.wrapper{padding-left:0px;}
		</style>
		<!--end::Global Theme Styles-->
		<!--begin::Layout Themes(used by all pages)-->
		<!--end::Layout Themes-->
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="" class="header-fixed header-mobile-fixed subheader-enabled page-loading" style="max-width:1000px;margin:0px auto">
		<!--begin::Main-->
		
		<div class="d-flex flex-column flex-root">
			<!--begin::Page-->
			<div class="d-flex flex-row flex-column-fluid page">
				
				<!--begin::Wrapper-->
				<div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
					
					<!--begin::Content-->
					<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
						<p class="p-3"></p>
						<!--begin::Mixed Widget 20-->
										<div class="card card-custom bgi-no-repeat gutter-b" style="height: 175px; background-color: #4AB58E; background-position: calc(100% + 1rem) bottom; background-size: 25% auto; background-image: url({{ asset('designs/assets/media/svg/patterns/rhone.svg')}})">
											<!--begin::Body-->
											<div class="card-body d-flex align-items-center">
												<div class="py-2">
													<h3 class="text-white font-weight-bolder mb-3">{{$test->name}}</h3>
													
												</div>
											</div>
											<!--end::Body-->
										</div>
										<!--end::Mixed Widget 20-->

						<div class="card card-custom gutter-b">
									<div class="card-body">
										<!--begin::Details-->
										<div class="d-flex mb-9">
											<!--begin: Pic-->
											<div class="flex-shrink-0 mr-7 mt-lg-0 mt-3">
												<div class="symbol symbol-50 symbol-lg-120">
													<img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/12/User_icon_2.svg/1024px-User_icon_2.svg.png" alt="image" />
												</div>
												<div class="symbol symbol-50 symbol-lg-120 symbol-primary d-none">
													<span class="font-size-h3 symbol-label font-weight-boldest">JM</span>
												</div>
											</div>
											<!--end::Pic-->
											<!--begin::Info-->
											<div class="flex-grow-1">
												<!--begin::Title-->
												<div class="d-flex justify-content-between flex-wrap mt-1">
													<div class="d-flex mr-3">
														<a href="#" class="text-dark-75 text-hover-primary font-size-h5 font-weight-bold mr-3">{{$user->name}}</a>
														<a href="#">
															<i class="flaticon2-correct text-success font-size-h5"></i>
														</a>
													</div>
													<div class="my-lg-0 my-3">
														<a href="#" class="btn  btn-info font-weight-bolder btn-lg stext-uppercase">Overall : {{$param_percent['score']}} / 100</a>
													</div>
												</div>
												<!--end::Title-->
												<!--begin::Content-->
												<div class="d-flex flex-wrap justify-content-between mt-1">
													<div class="d-flex flex-column flex-grow-1 pr-8">
														<div class="d-flex flex-wrap mb-4">
															<a href="#" class="text-dark-50 text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
															<i class="flaticon2-new-email mr-2 font-size-lg"></i>{{$user->email}}</a>
															
														</div>
													</div>
												</div>
												<!--end::Content-->
											</div>
											<!--end::Info-->
										</div>
										<!--end::Details-->
										<div class="separator separator-solid"></div>
										<!--begin::Items-->
										<div class="d-flex align-items-center flex-wrap mt-8">
											<!--begin::Item-->
											<div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
												<span class="mr-4">
													<i class="flaticon-pie-chart display-4 text-muted font-weight-bold"></i>
												</span>
												<div class="d-flex flex-column text-dark-75">
													<span class="font-weight-bolder font-size-sm">Leximic Dextirity</span>
													<span class="font-weight-bolder text-success font-size-h5">
													<span id="ld" >{{$param_percent['leximic-dextirity']}}</span> / 100</span>
												</div>
											</div>
											<!--end::Item-->
											<!--begin::Item-->
											<div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
												<span class="mr-4">
													<i class="flaticon-pie-chart display-4 text-muted font-weight-bold"></i>
												</span>
												<div class="d-flex flex-column text-dark-75">
													<span class="font-weight-bolder font-size-sm">Grammatical proficiency</span>
													<span class="font-weight-bolder text-success font-size-h5">
													<span id="gp" >{{$param_percent['grammatical-proficiency']}} </span>/ 100</span>
												</div>
											</div>
											<!--end::Item-->
											<!--begin::Item-->
											<div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
												<span class="mr-4">
													<i class="flaticon-pie-chart display-4 text-muted font-weight-bold"></i>
												</span>
												<div class="d-flex flex-column text-dark-75">
													<span class="font-weight-bolder font-size-sm">Pronunciation</span>
													<span class="font-weight-bolder text-success font-size-h5">
													<span id="pr" >{{$param_percent['pronunciation']}} </span>/ 100</span>
												</div>
											</div>
											<!--end::Item-->
											<!--begin::Item-->
											<div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
												<span class="mr-4">
													<i class="flaticon-pie-chart display-4 text-muted font-weight-bold"></i>
												</span>
												<div class="d-flex flex-column flex-lg-fill">
													<span class="text-dark-75 font-weight-bolder font-size-sm">Fluency</span>
													<span class="font-weight-bolder text-success font-size-h5">
													<span id="fl" >{{$param_percent['fluency']}}</span> / 100</span>
												</div>
											</div>
											<!--end::Item-->
											<!--begin::Item-->
											<div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
												<span class="mr-4">
													<i class="flaticon-pie-chart display-4 text-muted font-weight-bold"></i>
												</span>
												<div class="d-flex flex-column">
													<span class="text-dark-75 font-weight-bolder font-size-sm">Understanding and completeness</span>
													<span class="font-weight-bolder text-success font-size-h5">
													<span id="uc" >{{$param_percent['understanding-and-completeness']}}</span> / 100</span>
												</div>
											</div>
											<!--end::Item-->
											
										</div>
										<!--begin::Items-->
									</div>
								</div>

						<div class="row">
							<div class="col-12 col-xl-6">
								

							</div>
							<div class="col-12 ">
								<div class="card card-custom gutter-b">
											<div class="card-header">
												<div class="card-title">
													<h3 class="card-label">Language Test</h3>
												</div>
											</div>
											<div class="card-body">
												<!--begin::Chart-->
												<div id="chart_4"></div>
												<!--end::Chart-->
											</div>
										</div>

							</div>
						</div>
						
						<!--begin::Engage Widget 5-->
										<div class="card card-custom card-stretch gutter-b">
											<div class="card-body d-flex p-0">
												<div class="flex-grow-1 bg-dark p-12 pb-30 card-rounded flex-grow-1 bgi-no-repeat" style="background-color: #1B283F;background-position: right bottom; background-size: 15% auto; background-image: url({{ asset('designs/assets/media/svg/humans/custom-10.svg')}})">
													<h3 class="text-inverse-info pb-5 font-weight-bolder">Comments</h3>
													<p class="text-inverse-info pb-5 font-size-h6">
														{!! $result->first()->comment !!}
													</p>
												</div>
											</div>
										</div>
										<!--end::Engage Widget 5-->
						<div class="card card-custom gutter-b">
						 <div class="card-header">
						  <div class="card-title">
						   <h3 class="card-label">
						    User Webcam
						    <small>captures</small>
						   </h3>
						  </div>
						 </div>
						 <div class="card-body">
						 	<div class="row">
						 	@for($i=0;$i<9;$i++)
						 		@if(Storage::disk('s3')->exists('webcam/'.$test->id.'/'.$user->id.'_'.$test->id.'_'.$i.'.jpg'))
						 			<div class="col-6 col-md-3"><img src="{{ Storage::disk('s3')->url('webcam/'.$test->id.'/'.$user->id.'_'.$test->id.'_'.$i.'.jpg')}}" class='rounded w-100 mb-3' /></div>
						 		@endif
						 	@endfor
						 </div>
						 </div>
						</div>

					</div>
					<!--end::Content-->
					<!--begin::Footer-->
					
					<!--end::Footer-->
				</div>
				<!--end::Wrapper-->
			</div>
			<!--end::Page-->
		</div>
		<!--end::Main-->
	
	
	<script>var HOST_URL = "#";</script>
		<!--begin::Global Config(global config for global JS scripts)-->
		<script>var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1200 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#8950FC", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#8950FC", "warning": "#FFA800", "danger": "#F64E60", "light": "#F3F6F9", "dark": "#212121" }, "light": { "white": "#ffffff", "primary": "#E1E9FF", "secondary": "#ECF0F3", "success": "#C9F7F5", "info": "#EEE5FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#212121", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#ECF0F3", "gray-300": "#E5EAEE", "gray-400": "#D6D6E0", "gray-500": "#B5B5C3", "gray-600": "#80808F", "gray-700": "#464E5F", "gray-800": "#1B283F", "gray-900": "#212121" } }, "font-family": "Poppins" };</script>
		<!--end::Global Config-->
		<!--begin::Global Theme Bundle(used by all pages)-->
		<script src="{{ asset('designs/assets/plugins/global/plugins.bundle.js?v=7.0.5') }}"></script>
		<script src="{{ asset('designs/assets/plugins/custom/prismjs/prismjs.bundle.js?v=7.0.5') }}"></script>
		<script src="{{ asset('designs/assets/js/scripts.bundle.js?v=7.0.5') }}"></script>
		<!--end::Global Theme Bundle-->
		<!--begin::Page Scripts(used by this page)-->
		<script src="{{ asset('designs/assets/js/pages/features/charts/apexcharts.js?v=7.0.5') }}"></script>
	</body>
	<!--end::Body-->
</html>