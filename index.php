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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@800&display=swap" rel="stylesheet">
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

    <div class="container-fluid">
        <div class="row">
            <div class="offset-sm-1 offset-md-2 offset-lg-3 col-sm-10 col-md-8 col-lg-6">
                <div style="background-color: #212529; border-radius: 100%; width: 45px; height: 45px; margin-left: 48%;">
                    <svg onclick="pause()" xmlns="http://www.w3.org/2000/svg" style="color: white;" width="45" height="45" fill="currentColor" class="bi bi-pause" viewBox="0 0 16 16">
                        <path d="M6 3.5a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-1 0V4a.5.5 0 0 1 .5-.5zm4 0a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-1 0V4a.5.5 0 0 1 .5-.5z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid" style="position: absolute; top: 25%;">
        <div class="row">
            <div class="offset-sm-1 offset-md-2 offset-lg-3 col-sm-10 col-md-8 col-lg-6">
                <div class="progress">
                    <div id="progressbar" class="progress-bar" role="progressbar" style="width: 0%" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div id="lyrics" class="lyrics">
                </div>
            </div>
        </div>
    </div>

    <script>
        changeBackgroundColor(randomRGB());

        $('#btn_change_background').click(function() {
            changeBackgroundColor(randomRGB());
        });

        function randomRGB() {
            var white = [255, 255, 255];
            var black = [0, 0, 0];

            for(x = 0; x != 1; x++) {
                var rgb = [Math.floor(Math.random() * 256), Math.floor(Math.random() * 256), Math.floor(Math.random() * 256)];
                if(rgb === white || rgb === black)
                    x = 1;
            }
            
            return rgb;
        }

        function changeBackgroundColor(rgb) {
            document.body.style.backgroundColor = 'rgb(' + rgb[0] + ',' + rgb[1] + ',' + rgb[2] + ')';
        }
    </script>

    <script>
        var progressbarVal = 0;

        var paused = false;

        var activeLine = 0;
        var lyric = [
        "%",
        "Hip Hop died, it's full of guys who cannot even rap (facts),",
        "Media dividing us by colors, white or black (facts),",
        "If you believe in Jesus, these days Christians get attacked (facts),",
        "If you don't hate police then everybody thinks you're wack,",
        "And everything's so connected",
        "Black Lives Matter got so aggressive",
        "White folks who agree can't support the message",
        "Both sides go to war 'cause they don't respect it",
        "Our social climate from the global tension",
        "Turned to total violence and a whole depression",
        "We could unify and then all go against them",
        "But we let 'em divide us with votes and elections"
        ];
        var lyricTime = [
            0.5,
            3.6,
            3.6,
            3.6,
            3.6,
            1.45,
            1.65,
            1.75,
            1.75,
            1.7,
            1.73,
            1.75,
            2.4
        ];
        
        var interval = lyricTime[activeLine] * 10;
        var intervalId;

        fillLyric(lyric, activeLine);
        startInterval(interval);

        function startInterval(interval) {
            // Store the id of the interval so we can clear it later
            intervalId = setInterval(function() {
                if(!paused) {
                    if(lyric.length != activeLine) {
                        document.getElementById("progressbar").style.width = progressbarVal + "%";
                        progressbarVal++;

                        if(progressbarVal >= 100) {
                            activeLine++;
                            progressbarVal = 0;
                            nextLine();
                        }
                    } else {
                        document.getElementById("progressbar").style.width = "0%";
                    }
                }
            }, interval);
        }

        function nextLine() {
            fillLyric(lyric, activeLine);
            
            interval = lyricTime[activeLine] * 10;

            // clear the existing interval
            clearInterval(intervalId);
            // just start a new one
            startInterval(interval);
        }

        function fillLyric(lyric, activeLine) {
            var HTMLLyrics = '';

            var x = 0;
            var current;
            var next;
            var last;
            lyric.forEach(function(item) {
                switch (true) {
                    case (next == x):
                        HTMLLyrics += '<p>' + item + '</p>';
                        break;
                    
                    case (x == next + 1 || last == x):
                        HTMLLyrics += '<p class="last">' + item + '</p>';
                        break;

                    case (x < activeLine || x > activeLine + 2):
                        HTMLLyrics += '<p style="display: none;">' + item + '</p>';
                        break;

                    case (x == activeLine + 2):
                        if(item == "%") {
                            last = x + 1;
                        } else {
                            HTMLLyrics += '<p class="last">' + item + '</p>';
                        }
                        break;

                    case (lyric[activeLine] == item):
                        if(item == "%") {
                            current = x + 1;
                            HTMLLyrics += '<p class="active"></p>';
                        } else {
                            HTMLLyrics += '<p class="active">' + item + '</p>';
                        }
                        break;

                    default:
                        if(item == "%") {
                            next = x + 1;
                        } else {
                            HTMLLyrics += '<p>' + item + '</p>';
                        }
                        break;
                }

                x++;
            });

            document.getElementById("lyrics").innerHTML = HTMLLyrics;
        }

        function pause() {
            paused = !paused;
        }
    </script>

</body>
</html>
