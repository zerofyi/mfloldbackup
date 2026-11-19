<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <style>
        :root {
            --background-url: url('{{ asset('image/background.jpg') }}');
            --banner-url: url('{{ asset('image/banner.jpg') }}');
        }
    </style>
    <link rel="stylesheet" href="{{ asset('style/app.css') }}">
    <title>Live Draw</title>
</head>

<body>
    {{-- <button id="mainBtn">RUN</button> --}}
    {{-- <div class="start-end-screen">
        <div class="text">MEGHALAYA FUTURE LOTTERY</div>
        <div class="text-2">DRAW TIME - (01:55, 06,55, 08:55) PM</div>
        <div class="text-3">LIVE DRAW STARTING SOON</div>
    </div> --}}
    <div class="welcome-container" id="welcomeContainer">
        <div class="text">MEGHALAYA FUTURE LOTTERY</div>
        <div class="text-2">DRAW TIME - (01:55, 06,55, 08:55) PM</div>
        <div class="text-3" id="drawTxtT">LIVE DRAW STARTING SOON</div>
        <div id="check-box-container">
            <button id="audionCheck">Audio Check</button>
            </br>
            </br>
            <input type="checkbox" id="ck">
            <label for="ck" style="color: white;">Audio played</label>
            </br>
            </br>
            <button id="AllCk" disabled>All checked</button>
        </div>
    </div>
    <div class="hide-container" id="hideContainer">
        <audio id="audioPlayer" src="{{ asset('audio/r3.mp3') }}" preload="auto"></audio>
        <div class="text">MEGHALAYA FUTURE LOTTERY</div>
        <div class="prize-z" id="hidePrize">1st Prize</div>
        <div class="text2" id="hideDigits">ON 5 DIGITS WITH SERIES</div>
        <div class="timer">
            <div class="block" id="m-count-down">00</div>
            <div class="block" id="count-down">59</div>
        </div>
    </div>
    <div class="main-container">
        <div class="header-container">
            <div class="tag">
                <div class="text">PLAY LIVE DRAW ON <span>YOUTUBE</span></div>
                <div class="text">MEGHALAYA LOTTERIES</div>
            </div>
            <div class="branding">
                <div class="name">MEGHALAYA FUTURE LOTTERY</div>
                <div class="time-table">DRAW TIME - (01:55, 06,55, 08:55) PM</div>
            </div>
            <div class="tag">
                <div class="text">LIVE FROM MEGHALAYA</div>
            </div>
        </div>
        <div class="body-container">
            <div class="child first-credit-container">
                <div class="name-container">
                    <div class="text">MEGHALAYA LOTTERIES</div>
                </div>
            </div>

            <div class="child board-container">
                {{-- <div class="hide-container" id="hideContainer">
                    <audio id="audioPlayer" src="{{ asset('audio/r3.mp3') }}" preload="auto"></audio>
                    <div class="text">MEGHALAYA FUTURE</br><span>LOTTERY</span></div>
                    <div class="timer">
                        <div class="block" id="m-count-down">00</div>
                        <div class="block" id="count-down">59</div>
                    </div>
                </div> --}}
                <div class="game-board-container" id="gameBoard">
                    <div class="the-board game-board-first" id="firstGame">
                        <div class="the-wheel-container">
                            <div class="the-number-wheel the-wheel-x">
                                <x-number-wheel />
                            </div>
                            <div class="the-letter-wheel the-wheel-x">
                                <x-letter-wheel />
                            </div>
                        </div>
                        <div class="the-slots-container">
                            <div class="slots-line">
                                <div class="slot-holder" id="firstBoardContainer">
                                    {{-- <x-slot /> --}}
                                </div>

                                <x-hidden-slots />
                            </div>

                            <?php for ($i = 0; $i < 4; $i++) { ?>
                                <div class="slots-line">
                                    <x-hidden-slots />
                                </div>
                            <?php } ?>

                        </div>
                    </div>

                    <div class="the-board game-board-second-third" id="secondThirdGame">
                        <div class="blank-slots-holder">
                            <?php for ($i = 0; $i < 5; $i++) { ?>
                                <x-hidden-slots />
                            <?php } ?>
                        </div>

                        <div class="slot-holder" id="secondThirdBoardContainer"></div>

                        <div class="blank-slots-holder">
                            <?php for ($i = 0; $i < 5; $i++) { ?>
                                <x-hidden-slots />
                            <?php } ?>
                        </div>
                    </div>
                    {{-- <div class="the-board game-board-third"></div> --}}
                    <div class="the-board game-board-fourth" id="fifthGame">
                        <div class="slot-holder" id="fifthBoardContainer"></div>

                        <div class="blank-slots-holder">
                            <?php for ($i = 0; $i < 5; $i++) { ?>
                                <x-hidden-slots />
                            <?php } ?>
                        </div>
                    </div>
                    {{-- <div class="the-board game-board-fifth"></div> --}}
                </div>
                <div class="end-screen" id="endScreen">
                    <div class="text">MEGHALAYA FUTURE</br><span>LOTTERY</span></div>
                    <div class="info">DOWNLOAD THE RESULT PDF FROM www.meghalayafuturelottery.com</div>
                </div>
            </div>

            <div class="child last-credit-container">
                <div class="prize-container">
                    <div class="game-slot" id="gameStage">1st</div>
                    <div class="prize">PRIZE</div>
                    <div class="prize-text" id="gameStageText">ON <span>5</span> DIGITS WITH SERIES</div>
                </div>
            </div>
        </div>
        <div class="footer-container">
            <div class="tag">
                <div class="text" id="drawDate">DRAW DATE: 00/00/0000</div>
            </div>
            <div class="logo"><img src="{{ asset('image/logo.png') }}" alt=""></div>
            <div class="tag">
                <div class="text" id="drawTime">DRAW TIME: 00:00</div>
            </div>
        </div>
    </div>
</body>

</html>
<script>
    window.PUSHER_APP_KEY = '{{ env('PUSHER_APP_KEY') }}';
    window.PUSHER_APP_CLUSTER = '{{ env('PUSHER_APP_CLUSTER') }}';
    window.URL = "{{ url('/get-result') }}/";
    window.COUNTDOWN = 59;
    window.GAP = 10;
</script>
<script src="{{ asset('js/app.js') }}"></script>
