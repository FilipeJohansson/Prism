<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="author" content="Filipe Johansson">
    <meta name="title" content="Prism">

	<link rel="icon" href="./assets/icon.png">
	<title>Prism</title>
    
	<!--CSS-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/style.css">

    <!--JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="./js/jquery-3.6.0.min.js"></script>
</head>
<body>

    <header class="mb-4">
        <div id="logo" class="float-left">
            <div id="logo_icon"></div>
        </div>
        <div class="float-right d-flex align-items-center text-decoration-none">
            <button id="btn_change_background" class="btn btn-dark">Change background color</button>
        </div>
    </header>

    <div class="container-fluid">
        <div class="row">
            <div class="offset-sm-1 offset-md-2 offset-lg-3 col-sm-10 col-md-8 col-lg-6">
                <div class="input-group mb-3">
                    <input id="input_search" type="text" class="form-control" placeholder="Search video" aria-label="Search video" aria-describedby="basic-addon2">
                    <div class="input-group-btn">
                        <button id="btn_search" class="btn btn-dark" type="submit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="mx-3" role="img" viewBox="0 0 24 24">
                                <title>Search</title>
                                <circle cx="10.5" cy="10.5" r="7.5"></circle>
                                <path d="M21 21l-5.2-5.2"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var rgb = randomRGB();
        document.body.style.backgroundColor = 'rgb(' + rgb[0] + ',' + rgb[1] + ',' + rgb[2] + ')';

        $('#btn_change_background').click(function() {
            var rgb = randomRGB();
            document.body.style.backgroundColor = 'rgb(' + rgb[0] + ',' + rgb[1] + ',' + rgb[2] + ')';
        });

        function randomRGB() {
            var rgb = [Math.floor(Math.random() * 256), Math.floor(Math.random() * 256), Math.floor(Math.random() * 256)]
            return rgb;
        }
    </script>

</body>
</html>
