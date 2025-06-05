<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
        <meta name="robots" content="noindex, nofollow">

		<meta charset="utf-8" />
		<title>@yield('pageHeader') | CrynoERP</title>

		<!-- Site favicon -->
		<link
        rel="apple-touch-icon"
        sizes="180x180"
        href="{{ asset('src/images/apple-touch-icon.png') }}"
        />

        <link
        rel="icon"
        type="image/png"
        sizes="32x32"
        href="{{ asset('src/images/favicon-32x32.png') }}"
        />

        <link
        rel="icon"
        type="image/png"
        sizes="16x16"
        href="{{ asset('src/images/favicon-16x16.png') }}"
        />

		<!-- Mobile Specific Metas -->
		<meta
			name="viewport"
			content="width=device-width, initial-scale=1, maximum-scale=1"
		/>

		<!-- Google Font -->
		<link
			href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
			rel="stylesheet"
		/>

		<!-- CSS -->
        <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/core.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/icon-font.min.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/datatables/css/dataTables.bootstrap4.min.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('src/plugins/datatables/css/responsive.bootstrap4.min.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/style.css') }}" />

		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script
			async
			src="https://www.googletagmanager.com/gtag/js?id=G-GBZ3SGGX85"
		></script>
		<script
			async
			src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2973766580778258"
			crossorigin="anonymous"
		></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag() {
				dataLayer.push(arguments);
			}
			gtag("js", new Date());

			gtag("config", "G-GBZ3SGGX85");
		</script>
		<!-- Google Tag Manager -->
		<script>
			(function (w, d, s, l, i) {
				w[l] = w[l] || [];
				w[l].push({ "gtm.start": new Date().getTime(), event: "gtm.js" });
				var f = d.getElementsByTagName(s)[0],
					j = d.createElement(s),
					dl = l != "dataLayer" ? "&l=" + l : "";
				j.async = true;
				j.src = "https://www.googletagmanager.com/gtm.js?id=" + i + dl;
				f.parentNode.insertBefore(j, f);
			})(window, document, "script", "dataLayer", "GTM-NXZMQSS");
		</script>
		<!-- End Google Tag Manager -->
	</head>
	<body class="sidebar-dark header-dark">
		<div class="pre-loader">
			<div class="pre-loader-box">
				<div class="loader-logo">
					<img src="{{ asset('src\images\apple-touch-icon.png') }}" width="100px" alt="" />
				</div>
				<div class="loader-progress" id="progress_div">
					<div class="bar" id="bar1"></div>
				</div>
				<div class="percent" id="percent1">0%</div>
				<div class="loading-text">Loading...</div>
			</div>
		</div>

		<div class="header">

            <!--Header Left-->
            @include('parts.headerLeft')

            <!--Header Right-->
            @include('parts.headerRight')


		</div>

		<!--Sidebar Left-->
		@include('parts.sidebarRight')

		<!--Sidebar Right-->
        @include('parts.sidebarLeft')


		<div class="mobile-menu-overlay"></div>

		<div class="main-container">
			<div class="xs-pd-20-10 pd-ltr-20">

                <!--Page Header-->
                <div class="page-header">
                    <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="title">
                                    <h4>@yield('pageHeader')</h4>
                                </div>
                            </div>

                        </div>
                </div>


                <div class="pd-20 card-box mb-30">
                    @yield('main-section')
                </div>

                <!--Footer-->
		        @include('parts.footer')


			</div>
		</div>

        <script>
            $(document).ready(function() {
                $('table').DataTable({
                    responsive: false,  // Disable responsive extension (no row collapse)
                    paging: true,
                    searching: true,
                    ordering: true,
                    autoWidth: false,   // Disable auto column width adjustment, helps prevent wrapping
                });
            });


        </script>
        <style>
            .table-responsive {
                overflow-x: auto !important;
                white-space: nowrap;
            }

        </style>

		<!-- js -->
        <script src="{{ asset('vendors/scripts/core.js') }}"></script>
        <script src="{{ asset('vendors/scripts/script.min.js') }}"></script>
        <script src="{{ asset('vendors/scripts/process.js') }}"></script>
        <script src="{{ asset('vendors/scripts/layout-settings.js') }}"></script>
        <script src="{{ asset('src/plugins/apexcharts/apexcharts.min.js') }}"></script>
        <script src="{{ asset('src/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('src/plugins/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('src/plugins/datatables/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('src/plugins/datatables/js/responsive.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('vendors/scripts/dashboard3.js') }}"></script>

		<!-- Google Tag Manager (noscript) -->
		<noscript
			><iframe
				src="https://www.googletagmanager.com/ns.html?id=GTM-NXZMQSS"
				height="0"
				width="0"
				style="display: none; visibility: hidden"
			></iframe
		></noscript>
		<!-- End Google Tag Manager (noscript) -->

	</body>
</html>
