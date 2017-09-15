<!DOCTYPE html>
<html>
<title>@yield('judul')</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="/stylesheets/w3.css">
<link rel="stylesheet" href="/stylesheets/font-awesome-4.7.0/css/font-awesome.min.css">

<body class="w3-white">
    <div class="w3-sidebar w3-blue-gray w3-bar-block w3-collapse w3-card-2 w3-animate-left" style="width:200px;display:block;">
        <button class="w3-bar-item w3 w3-button w3-large w3-hide-large">Menu &times;</button>
        <a href="#!" class="w3-bar-item w3-button w3-border-bottom">Kelola Produk</a>
        <a href="#!" class="w3-bar-item w3-button w3-border-bottom">Kelola Transaksi</a>
        <a href="#!" class="w3-bar-item w3-button w3-border-bottom">Kelola Pengguna</a>
    </div>
    <div class="w3-main" style="margin-left:200px">
        <div class="w3-bar w3-teal">
            <button class="w3-xlarge w3-bar-item w3-button w3-hover-red w3-hide-large"><i class="fa fa-bars"></i></button>
            <button class="w3-xlarge w3-bar-item w3-button w3-hover-light-gray">Nala the Cat</button>
        </div>

        <div class="w3-row-padding">
		@yield('konten')
        </div>
        <div class="w3-bar w3-teal w3-margin-top">
            <div class="w3-container w3-hover-teal">
                <button class="w3-bar-item w3-button w3-hover-teal">
			<h5>Thx to W3CSS,NodeJS and AngularJS</h5>
		</button>
            </div>
        </div>
    </div>
</body>
</html>
