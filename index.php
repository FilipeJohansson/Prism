<?php
    require_once("./DAOs/MusicDAO.php");

    $musicDAO = new MusicDAO();

    // get all titles to fill autocomplete tags
    $titles = $musicDAO->getMusicsTitle();
    
    /* get a music from a title
    *  Music = {
    *      $id;
    *      $videoId;
    *      $musicTitle;
    *      $musicLyric;
    *      $musicTimes;
    *  }
    */
    $music = $musicDAO->getMusicFromTitle("No lives matter");

    if(is_int($music) || is_int($titles)) {
        if($music == -1) { /* ERROR */ }
        if($titles == -1) { /* ERROR */ }
    }

    // break lyric into an array by the separator
    $lyric = explode(';', $music->lyric);
?>

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
    <link rel="stylesheet" href="./css/jquery-ui.css">

    <!--JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="./js/jquery-3.6.0.min.js"></script>
    <script src="./js/jquery-ui.js"></script>

    <script>
        $( function() {
            var availableTags = [
                <?php
                    if($titles != -1)
                        foreach ($titles as $t)
                            echo '"' . $t . '",';    
                ?>
            ];
            $( "#input_search" ).autocomplete({
                source: availableTags
            });
        } );
    </script>
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

    <div class="container">
        <div class="row mb-3">
            <div class="offset-md-3 offset-lg-3 col-sm-12 col-md-5 col-lg-6">
                <div class="input-group mb-3">
                    <input id="input_search" type="text" class="form-control" placeholder="Search music" aria-label="Search video" aria-describedby="basic-addon2">
                    <div class="input-group-btn">
                        <button id="btn_search" class="btn btn-dark" type="submit" onclick="search()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="mx-3" role="img" viewBox="0 0 24 24">
                                <circle cx="10.5" cy="10.5" r="7.5"></circle>
                                <path d="M21 21l-5.2-5.2"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div id="video-column" class="col-sm-12 col-md-5 col-lg-6" style="display: none;">
                <div class="position-sticky" style="top: 2rem;">

                    <!-- 1. The <iframe> (video player) will replace this <div> tag. -->
                    <div class="iframe-container">
                        <div id="player"></div>
                    </div>
                    <script>
                        // 2. This code loads the IFrame Player API code asynchronously.
                        var tag = document.createElement('script');

                        tag.src = "https://www.youtube.com/iframe_api";
                        var firstScriptTag = document.getElementsByTagName('script')[0];
                        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

                        // 3. This function creates an <iframe> (and YouTube player)
                        //    after the API code downloads.
                        var player;
                        function onYouTubeIframeAPIReady() {
                            player = new YT.Player('player', {
                                width: '100%',
                                videoId: '<?php echo $music->videoId; ?>',
                                playerVars: {
                                    'playsinline': 0, 
                                    'rel': 0,
                                    'controls': 1,
                                    'showinfo': 0,
                                    'modestbranding': 1,
                                    'fs': 0,
                                    'cc_load_policy': 0,
                                },
                                events: {
                                    'onReady': function() {
                                        player.mute();
                                    },
                                    'onStateChange': stateChange,
                                }
                            });
                        }
                    </script>
                </div>
            </div>
            <div id="lyric-column" class="offset-md-3 offset-lg-3 col-sm-12 col-md-7 col-lg-6" style="display: none;">
                <div class="row">
                    <div class="progress">
                        <div id="progressbar" class="progress-bar" role="progressbar" style="width: 0%" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>

                <div class="row">
                    <div id="tip" class="tip mb-4">
                        <p>To start lyric, start video</p>
                    </div>
                </div>

                <div class="row">
                    <div id="lyrics" class="lyrics">
                    </div>
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
        var lyric = [
            <?php 
                foreach ($lyric as $l) {
                    echo '"' . $l . '",' . PHP_EOL;
                } 
            ?>
        ];
        
        var lyricTimes = [
            <?php
                echo $music->times;
            ?>
        ];

        var line = {
            actualLine: 0,
            lineChanged: function(value) {},
            set line(value) {
                if(this.actualLine != value) {
                    this.actualLine = value;
                    this.lineChanged();
                }
            },
            get line() {
                return this.actualLine;
            },
            registerListener: function(listener) {
                this.lineChanged = listener;
            }
        }

        var paused = true;
        var videoCurrentTime = 0;
        var progressbarVal = 0;

        var intervalId;
        var interval = calculateInterval(lyricTimes[0], lyricTimes[1]);

        fillLyric(lyric, line.actualLine);

        // 4. The API will call this function when the video player is ready.
        function stateChange(event) {
            if (player.isMuted() && player.getPlayerState() == -1 && paused) {
                document.getElementById("tip").style.display = "none";

                //intervalId = startInterval(interval);
                pause(false);

                player.unMute();
                player.playVideo();
            } else {
                pause();
            }
        }

        var y = false;
        setInterval(function() {
            if(player.getPlayerState() == 2) {
                pause(true);
            }

            if(!paused) {
                videoCurrentTime = player.playerInfo.currentTime;

                for(var x = 0; x <= lyricTimes.length + 1; x += 2) {
                    if(videoCurrentTime >= lyricTimes[x] && videoCurrentTime <= lyricTimes[x + 1]) {
                        if(x == 0) {
                            line.line = 0;
                        } else {
                            line.line = x / 2;
                        }
                        
                        break;
                    }

                    if(videoCurrentTime > lyricTimes[lyricTimes.length - 1]) {
                        if(!y) {
                            goToLine(line.actualLine++);
                            y = true;
                        }
                            
                        clearInterval(intervalId);
                    }
                    
                }
            }
        }, 10);

        function goToLine(line) {
            // clear the existing interval
            clearInterval(intervalId);

            var currentTime = videoCurrentTime;

            interval = calculateInterval(currentTime, lyricTimes[(line * 2) + 1]);

            fillLyric(lyric);

            var percent = calculateProgressPercent(lyricTimes[line * 2], lyricTimes[(line * 2) + 1], currentTime);

            // just start a new one
            startInterval(interval, percent);
            
        }

        function calculateProgressPercent(start, end, currentTime) {
            var diff = end - start;
            var target = currentTime - start;
            var percent = (target * 100) / diff;

            return percent;
        }

        function startInterval(interval, percent) {
            progressbarVal = percent;
            // Store the id of the interval so we can clear it later
            intervalId = setInterval(function () {
                if(!paused) {
                    document.getElementById("progressbar").style.width = progressbarVal + "%";
                    progressbarVal++;

                    if(progressbarVal >= 101) {
                        progressbarVal = 0;
                        clearInterval(intervalId);
                    }
                }
            }, interval);
        }

        function fillLyric(lyric) {
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

                    case (x < line.actualLine || x > line.actualLine + 2):
                        HTMLLyrics += '<p style="display: none;">' + item + '</p>';
                        break;

                    case (x == line.actualLine + 2):
                        if(item == "%") {
                            last = x + 1;
                        } else {
                            HTMLLyrics += '<p class="last">' + item + '</p>';
                        }
                        break;

                    case (lyric[line.actualLine] == item):
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

        function pause(value) {
            paused = value;
        }
        
        function calculateInterval(start, end) {
            var interval = (end - start) * 10;
            return interval;
        }

        line.registerListener(function(value) {
            goToLine(line.actualLine);            
        });

        window.onclick = () => {
            console.log("Linha atual:" + lyric[line.actualLine]);
            console.log("Tempo:" + player.playerInfo.currentTime);
        }
    </script>

    <script>
        function search() {
            // before: offset-md-3 offset-lg-3 col-sm-12 col-md-7 col-lg-6
            // after: col-sm-12 col-md-7 col-lg-6
            document.getElementById("lyric-column").classList.remove('offset-md-3');
            document.getElementById("lyric-column").classList.remove('offset-lg-3');
            
            document.getElementById("video-column").style.display = "block";
            document.getElementById("lyric-column").style.display = "block";
        }
    </script>

</body>
</html>
