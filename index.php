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
      "No Lives Matter",
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
                    </script>
                </div>
            </div>
            <div id="lyric-column" class="offset-md-3 offset-lg-3 col-sm-12 col-md-7 col-lg-6" style="display: none;">
                <div class="row">
                    <div id="tip" class="tip mb-4">
                        <p>To start lyric, start video</p>
                    </div>
                </div>

                <div class="row">
                    <div class="progress">
                        <div id="progressbar" class="progress-bar" role="progressbar" style="width: 0%" aria-valuemin="0" aria-valuemax="100"></div>
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

        var videoCurrentTime = 0;


        var progressbarVal = 0;
        
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
        "But we let 'em divide us with votes and elections",

        "the music we bump",
        "All about shooting guns and doing drugs",
        "(Ay, whoa) the things that we want",
        "Are promoted subliminally through the songs like",
        "You need a fast car, you need designer clothes",
        "You need a rap star",
        "To tell you to start popping pills",
        "Hit the blunt and go live at the club 'til you're broke",

        "It's all controlled by the elites",
        "Pull fake news all over our screens",
        "Convincing the right to go fight with the left",
        "And distract from the fact it's each other we need, uh",
        "Divided by race and religion",
        "Segregated into teams, uh",
        "You're a white supremacists",
        "If you're not, I guess you ANTIFA",

        "%",

        "Screaming from the rooftops, beatdown, battered",
        "Turned us on each other, now no lives matter",
        "If we do what the news wants, blood gon' splatter",
        "Turn us on each other 'til no lives matter",

        "Freedom's dead, if you have an opinion, take it back (facts)",
        "People hate the president, if you don't then you trash (facts)",
        "Indoctrinate the nation using news and mainstream rap (facts)",
        "The government abuses us, it's all part of the plan (facts)",

        "And it's so confusing",
        "Black Lives Matter is a valuable movement",
        "But All Lives Matter ain't racist or stupid",
        "It's non-black humans who don't feel included",
        "All colors fall under laws that govern",
        "The whole country and we all suffer",
        "We're all broke and nobody recovers",
        "Until we accept that we're all brothers",

        "the music we make",
        "All about big booties and getting paid",
        "(Ay, whoa) we watch the news",
        "And it fills up our brains",
        "With violence, and riots, and race",
        "Like this is a race war, you need to hate more",
        "Get what you came for",
        "You need some songs about Xanax and violence",
        "So you can escape more",
        
        "What a vicious cycle we gotta break away from",
        "They control the culture, they control the paper",
        "They're indoctrinating a whole generation",
        "'Til the patriots start to hate the nation",
        "The music we love make us dumb and addicted",
        "The news that we watch is brainwashing the children",
        "The virus is riots and racist conditions",
        "Ain't problems, they're symptoms of life in this system",

        "%",
        
        "Screaming from the rooftops, beatdown, battered",
        "Turned us on each other, now no lives matter",
        "If we do what the news wants, blood gon' splatter",
        "Turn us on each other 'til no lives matter",

        "The music will make you dumb",
        "The media makes you hate",
        "And they control 'em both",
        "There ain't no escape",
        "They put the world is a state of chaos",
        "Economy crashing and massive layoffs",
        "Black against white or it's left versus right",
        "Divide and conquer and control is the payoff",

        "Screaming from the rooftops, beatdown, battered",
        "Turned us on each other, now no lives matter",
        "If we do what the news wants, blood gon' splatter",
        "Turn us on each other 'til no lives matter",
        ];
        var lyricTimes = [
            0, 0.90158,

            0.901584, 4.432019,
            4.432019, 7.91702,
            7.91702, 11.653296,
            11.653296, 14.706861,

            14.706861, 16.016417,
            16.016417, 17.912534,
            17.912534, 19.714666,
            19.714666, 21.896879,
            21.896879, 23.568848,
            23.568848, 25.37915,
            25.37915, 27.28253,
            27.28253, 29.4414,

            29.606153, 30.638613,
            30.638613, 32.656709,
            32.656709, 34.230867,
            34.230867, 36.785047,
            36.785047, 39.511661,
            39.511661, 40.802453,
            40.802453, 41.856596,
            41.856596, 44.105805,

            44.105805, 45.561356,
            45.561356, 47.036608,
            47.036608, 49.038878,
            49.038878, 51.816876,
            51.816876, 53.418981,
            53.418981, 55.551717,
            55.551717, 56.57086,
            56.57086, 59.170051,

            59.170051, 59.72928,

            59.72928, 63.415588,
            63.415588, 66.9988,
            66.9988, 70.893526,
            70.893526, 74.705458,

            76.034786, 80.146966,
            80.146966, 83.63898,
            83.63898, 87.287417,
            87.287417, 90.763817,
            90.763817, 92.023342,
            92.023342, 93.965383,
            93.965383, 95.870021,
            95.870021, 97.708071,
            97.708071, 99.683695,
            99.683695, 101.097986,
            101.097986, 103.109796,
            103.109796, 105.005789,

            105.005789, 106.389386,
            106.389386, 108.338523,
            108.338523, 109.915178,
            109.915178, 111.005903,
            111.005903, 112.388177,
            112.388177, 115.076324, 
            115.076324, 116.423285, 
            116.423285, 118.408416,
            118.408416, 119.751351,

            119.751351, 121.505166,
            121.505166, 123.242626,
            123.242626, 125.158533,
            125.158533, 127.119086,
            127.119086, 129.078189,
            129.078189, 130.803122,
            130.803122, 132.708513,
            132.708513, 134.908506,

            134.908506, 135.343258,

            135.343258, 139.097205,
            139.097205, 142.708364,
            142.708364, 146.593488,
            146.593488, 150.341938,

            150.341938, 152.946883,
            152.946883, 154.947262,
            154.947262, 156.62848,
            156.62848, 158.149178,
            158.149178, 160.405274,
            160.405274, 162.305585,
            162.305585, 163.946272,
            163.946272, 166.260784,

            166.260784, 170.447851,
            170.447851, 174.091568,
            174.091568, 177.908268,
            177.908268, 182.271852
        ];
        
        var interval = calculateInterval(lyricTimes[0], lyricTimes[1]);

        fillLyric(lyric, line.actualLine);

        // 3. This function creates an <iframe> (and YouTube player)
        //    after the API code downloads.
        var player;
        var paused = true;
        function onYouTubeIframeAPIReady() {
            player = new YT.Player('player', {
                width: '100%',
                videoId: 't86ClLM3ZGY',
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

        var intervalId;

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
