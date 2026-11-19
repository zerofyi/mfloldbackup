function scaleWheel(scalePercentage) {
    const wheels = document.querySelectorAll('.wheel-container');
    wheels.forEach((wheelContainer) => {
        const originalSize = 300; // Original size of the wheel (in px)
        const scaledSize = (originalSize * scalePercentage) / 100;

        // Scale the wheel container
        wheelContainer.style.width = `${scaledSize}px`;
        wheelContainer.style.height = `${scaledSize}px`;
        wheelContainer.style.padding = `${(10 * scalePercentage) / 100}px`;
        wheelContainer.style.margin = `${(30 * scalePercentage) / 100}px auto 0`;

        // Scale the wheel
        const wheel = wheelContainer.querySelector('.wheel');
        wheel.style.width = `${scaledSize}px`;
        wheel.style.height = `${scaledSize}px`;
        wheel.style.borderWidth = `${(1 * scalePercentage) / 100}px`;

        // Scale the sectors
        // const sectors = wheel.querySelectorAll('.sector');
        // sectors.forEach((sector) => {
        //     sector.style.paddingLeft = `${(60 * scalePercentage) / 100}%`;
        //     sector.style.paddingTop = `${(5 * scalePercentage) / 100}%`;
        // });

        // Scale sector content
        const contents = wheel.querySelectorAll('.sector .content');
        contents.forEach((content) => {
            content.style.transform =
                `translateY(-${(80 * scalePercentage) / 100}px) translateX(${(30 * scalePercentage) / 100}px) rotate(60deg)`;
            // content.style.minHeight = `${(60 * scalePercentage) / 100}px`;
            // content.style.minWidth = `${(100 * scalePercentage) / 100}px`;
        });

        // Scale text inside sectors
        // const numbers = wheel.querySelectorAll('.sector .number');
        // numbers.forEach((number) => {
        //     number.style.fontSize = `${(15 * scalePercentage) / 100}px`;
        // });

        // Scale the spin button
        const spinButton = wheelContainer.querySelector('.spin');
        if (spinButton) {
            spinButton.style.width = `${(70 * scalePercentage) / 100}px`;
            spinButton.style.height = `${(70 * scalePercentage) / 100}px`;
            spinButton.style.top = `calc(50% - ${(35 * scalePercentage) / 100}px)`;
            spinButton.style.left = `calc(50% - ${(35 * scalePercentage) / 100}px)`;
        }

        // Scale the arrow
        // const arrow = wheelContainer.querySelector('.arrow');
        // if (arrow) {
        //     arrow.style.borderWidth = `${(55 * scalePercentage) / 100}px ${(20 * scalePercentage) / 100}px 0 ${(20 * scalePercentage) / 100}px`;
        // }
    });
}

scaleWheel(83);


// MAIN GAME LOGIC START ----------->

$(document).ready(function () {
    const audioPlayer = document.getElementById('audioPlayer');
    function initSlotMachine(reelCount, groupCount, predefinedNumbers, slotHolderId, callback) {
        var tMax = 4000, // animation time in ms
            reelHeight = 42; // Reel height in pixels

        var self = this;
        var completedAnimations = 0;

        function init() {
            // Create the groups dynamically based on groupCount
            for (let i = 0; i < groupCount; i++) {
                createGroup(i, predefinedNumbers[i]);
            }

            $('#lever-button').click(function () {
                self.triggerAllGroups(); // Call triggerAllGroups from the instance
            });
        }

        function createGroup(groupId, predefinedNumber) {
            // Create a new group and append to the board container
            var $group = $(`
                <div class="slots-container" id="group-${groupId}">
                    <div class="slot">
                        <div class="screws">
                            <div class="screw s1"></div>
                            <div class="screw s2"></div>
                            <div class="screw s3"></div>
                            <div class="screw s4"></div>
                        </div>
                        <div class="inner-container">
                            <div class="group reels-container"></div>
                        </div>
                    </div>
                </div>
            `);
            $(slotHolderId).append($group);

            // Initialize reels for the new group
            updateReels(groupId, predefinedNumber);
        }

        function generateReelNumbers() {
            return ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        }

        function updateReels(groupId, predefinedNumber) {
            var $reelsContainer = $(`#group-${groupId} .reels-container`);
            $reelsContainer.empty(); // Clear any existing reels

            // Add reels based on the reel count
            for (var i = 0; i < reelCount; i++) {
                $reelsContainer.append('<div class="reel"></div>');
            }

            // Initialize the reels with numbers
            initReels($(`#group-${groupId} .reel`));
        }

        function initReels($reels) {
            // Initialize each reel with the full set of numbers (0-9)
            $reels.each(function (i) {
                this.innerHTML = `
                    <div class="reel-holder">
                        <p>${generateReelNumbers().join('</p><p>')}</p>
                    </div>
                    <div class="reel-holder">
                        <p>${generateReelNumbers().join('</p><p>')}</p>
                    </div>
                    <div class="reel-door">${Math.floor(Math.random() * 10)}</div>
                `;
            });
        }

        function action(groupId) {
            $('.reel-door').fadeOut(100);
            var target = predefinedNumbers[groupId]; // Get the target number for this group
            var sTarget = target.toString(); // Ensure target is a string

            var numberOutput = [];
            var speeds = [];
            var r = [];

            for (var i = 0; i < reelCount; i++) {
                var intOffset = (parseInt(sTarget.charAt(i)) * reelHeight) % (reelHeight * 10);
                numberOutput[i] = intOffset >= 0 ? intOffset : reelHeight * 9; // Calculate target offset for each reel
                speeds[i] = Math.random() + 0.7; // Generate a random speed for each reel
                r[i] = (Math.random() * 10 | 0) * reelHeight; // Random start position for each reel
            }

            start = undefined; // Reset start time for animation

            animate(groupId, reelCount, numberOutput, speeds, r); // Trigger animation for this group
        }

        function animate(groupId, reelCount, numberOutput, speeds, r) {
            var $reels = $(`#group-${groupId} .reel`);
            var start;

            function animateReels(now) {
                if (!start) start = now;
                var t = now - start || 0;

                for (var i = 0; i < reelCount; ++i) {
                    $reels[i].scrollTop = (speeds[i] / tMax / 2 * (tMax - t) * (tMax - t) + numberOutput[i]) % (reelHeight * 10);
                }

                if (t < tMax) {
                    requestAnimationFrame(animateReels);
                } else {
                    start = undefined;
                    completedAnimations++;
                    // Call the callback after all animations are done
                    if (completedAnimations === groupCount) {
                        if (typeof callback === 'function') {
                            console.log('Callback is being invoked');
                            callback();  // Call the callback function after animations
                        }
                    }
                }
            }

            requestAnimationFrame(animateReels);
        }

        this.triggerAllGroups = function() {
            audioPlayer.play();
            setTimeout(function(){
                completedAnimations = 0;
                for (let i = 0; i < predefinedNumbers.length; i++) {
                    action(i); // Trigger action for each group
                }
            },3000);
        };

        // Initialize everything
        init();
    }

    function spinWheel(target, wheelId) {
        let currentTopSector = 0,
        currentRotation = 0,
        rotationNumber = 0;

        const wheel = document.getElementById(wheelId);
        let selectedIndex;

        selectedIndex = target;
        let relativeRotation = (selectedIndex - currentTopSector + 12) % 12;
        let rotation = relativeRotation * 30 + 4 * 360;

        currentRotation += rotation;
        rotationNumber++;
        wheel.style.transform = `rotate(${currentRotation}deg)`;
        currentTopSector = selectedIndex;

    }

    function playFirstTwoNumber(number, callback) {
        var num;
        if (number==="12"){num=12;}else if (number==="78"){num=11;}else if (number==="33"){num=10;}else if (number==="17"){num=9;}else if (number==="92"){num=8;}else if (number==="73"){
            num=7;}else if (number==="66"){num=6;}else if (number==="54"){num=5;}else if (number==="29"){num=4;}else if (number==="31"){num=3;}else if (number==="42"){num=2;}else if (number==="86"){num=1;}else{num=0;}
        console.log(`For number ${num}`);
        setTimeout(function(){
            spinWheel(num, "wheel-num");
        },3000);
        callback();
    }

    function playFirstLetter(letter, callback) {
        var num;
        if (letter==="X"){num=12;}else if (letter==="R"){num=11;}else if (letter==="N"){num=10;}else if (letter==="M"){num=9;}else if (letter==="L"){num=8;}else if (letter==="K"){
            num=7;}else if (letter==="F"){num=6;}else if (letter==="E"){num=5;}else if (letter==="D"){num=4;}else if (letter==="C"){num=3;}else if (letter==="B"){num=2;}else if (letter==="A"){num=1;}else{num=0;}
        console.log(`For letter ${num}`);
        setTimeout(function(){
            spinWheel(num, "wheel");
        },3000);
        callback();
    }

    function playFirstWinner(array, callback) {
        let winners = array.length;

        let slotMachine = new initSlotMachine(5, 1, array, "#firstBoardContainer", function() {
            let responseMessage = `All animations for ${winners} winners (1st) have completed!`;
            console.log(responseMessage);
            callback();
        });

        slotMachine.triggerAllGroups();
    }

    function playSecondWinners(array, callback) {
        let winners = array.length;

        let slotMachine = new initSlotMachine(5, winners, array, "#secondThirdBoardContainer", function() {
            let responseMessage = `All animations for ${winners} winners (2nd) have completed!`;
            console.log(responseMessage);
            callback();
        });

        slotMachine.triggerAllGroups();
    }

    function playThirdWinners(array, callback) {
        let winners = array.length;

        let slotMachine = new initSlotMachine(4, winners, array, "#secondThirdBoardContainer", function() {
            let responseMessage = `All animations for ${winners} winners (3rd) have completed!`;
            console.log(responseMessage);
            callback();
        });

        slotMachine.triggerAllGroups();
    }

    function playFourthWinners(array, callback) {
        let winners = array.length;

        let slotMachine = new initSlotMachine(4, winners, array, "#secondThirdBoardContainer", function() {
            let responseMessage = `All animations for ${winners} winners (4th) have completed!`;
            console.log(responseMessage);
            callback();
        });

        slotMachine.triggerAllGroups();
    }

    function playFifthWinners(array, callback) {
        let winners = array.length;

        let slotMachine = new initSlotMachine(4, winners, array, "#fifthBoardContainer", function() {
            let responseMessage = `All animations for ${winners} winners (5th) have completed!`;
            console.log(responseMessage);
            callback();
        });

        slotMachine.triggerAllGroups();
    }

    Pusher.logToConsole = true;
    var pusher = new Pusher(window.PUSHER_APP_KEY, {
        cluster: window.PUSHER_APP_CLUSTER
    });

    var channel = pusher.subscribe('live-draw-channel');
    channel.bind('live-event', function(data) {
        console.log('Event received:', data);
        fetchGameData(data.id);
    });

    function fetchGameData(id) {
        $.ajax({
            url: window.URL + id,
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                console.log('Response:', response);
                var countdown = window.COUNTDOWN;
                const dateStr = response.date;
                const [year, month, day] = dateStr.split("-");
                const formattedDate = `${day}/${month}/${year}`;
                document.getElementById('drawTime').textContent = "DRAW TIME: "+response.slot;
                document.getElementById('drawDate').textContent = "DRAW DATE: "+formattedDate;
                document.getElementById('welcomeContainer').style.display = 'none';

                let second_winners = response.second_winners.split(',').map(item => item.trim());
                let third_winners = response.third_winners.split(',').map(item => item.trim());
                let fourth_winners = response.fourth_winners.split(',').map(item => item.trim());
                let fifth_winners = response.fifth_winners.split(',').map(item => item.trim());

                const countdownElement = document.getElementById('count-down');
                const mCountdownElement = document.getElementById('m-count-down');
                const hideElement = document.getElementById('hideContainer');
                const hidePrize = document.getElementById('hidePrize');
                const hideDigits = document.getElementById('hideDigits');
                const gameBoard = document.getElementById('gameBoard');
                const firstGame = document.getElementById('firstGame');
                const secondThirdGame = document.getElementById('secondThirdGame');
                const secondThirdBoardContainer = document.getElementById('secondThirdBoardContainer');
                const fifthGame = document.getElementById('fifthGame');
                const fifthBoardContainer = document.getElementById('fifthBoardContainer');
                const endScreen = document.getElementById('endScreen');

                const gameStage = document.getElementById('gameStage');
                const gameStageText = document.getElementById('gameStageText');

                runFirstBatch();

                function runFirstBatch() {
                    const str = response.first;
                    const regex = /^(\d{2})([A-Z])\s(\d+)$/;
                    const matches = str.match(regex);

                    if (matches) {
                        const part1 = matches[1];
                        const part2 = matches[2];
                        const part3 = matches[3];
                        const firstWinner = [part3];

                        console.log(part1); // "AB"
                        console.log(part2); // "5"
                        console.log(part3); // "14528"

                        const interval = setInterval(() => {
                            countdownElement.textContent = getNewCount(countdown);
                            if (countdown === 0) {
                                clearInterval(interval);
                                firstGame.style.display = 'flex';
                                gameBoard.style.display = 'flex';
                                hideElement.style.display = 'none';
                                hidePrize.textContent = '2nd Prize';
                                hideDigits.textContent = 'ON 5 DIGITS WITH 10 TIMES';

                                playFirstTwoNumber(part1, function() {
                                    playFirstLetter(part2, function() {
                                        playFirstWinner(firstWinner, function () {
                                            startZoomSequence();
                                            setTimeout(function(){
                                                countdown = window.GAP;
                                                gameBoard.style.display = 'none';
                                                firstGame.style.display = 'none';
                                                firstGame.innerHTML = '';
                                                hideElement.style.display = 'flex';
                                                runSecondBatch();
                                            },10000);
                                        });
                                    });
                                });
                            }
                            countdown--;
                        }, 1000);

                    } else {
                        console.log("String format is incorrect");
                    }
                }

                function runSecondBatch() {
                    gameStage.textContent = '2nd';
                    gameStageText.textContent = 'ON 5 DIGITS WITH 10 TIMES';
                    const interval2 = setInterval(() => {
                        countdownElement.textContent = getNewCount(countdown);
                        if (countdown === 0) {
                            clearInterval(interval2);
                            secondThirdGame.style.display = 'flex';
                            gameBoard.style.display = 'flex';
                            hideElement.style.display = 'none';
                            hidePrize.textContent = '3rd Prize';
                            hideDigits.textContent = 'ON 4 DIGITS WITH 10 TIMES';
                            playSecondWinners(second_winners, function() {
                                setTimeout(function(){
                                    countdown = window.GAP;
                                    gameBoard.style.display = 'none';
                                    secondThirdGame.style.display = 'none';
                                    secondThirdBoardContainer.innerHTML = '';
                                    hideElement.style.display = 'flex';
                                    runThirdBatch();
                                },10000);
                            });
                        }
                        countdown--;
                    }, 1000);
                }

                function runThirdBatch() {
                    gameStage.textContent = '3rd';
                    gameStageText.textContent = 'ON 4 DIGITS WITH 10 TIMES';
                    const interval3 = setInterval(() => {
                        countdownElement.textContent = getNewCount(countdown);
                        if (countdown === 0) {
                            clearInterval(interval3);
                            secondThirdGame.style.display = 'flex';
                            gameBoard.style.display = 'flex';
                            hideElement.style.display = 'none';
                            hidePrize.textContent = '4th Prize';
                            hideDigits.textContent = 'ON 4 DIGITS WITH 10 TIMES';
                            playThirdWinners(third_winners, function() {
                                setTimeout(function(){
                                    countdown = window.GAP;
                                    gameBoard.style.display = 'none';
                                    secondThirdGame.style.display = 'none';
                                    secondThirdBoardContainer.innerHTML = '';
                                    hideElement.style.display = 'flex';
                                    runFourthBatch();
                                },10000);
                            });
                        }
                        countdown--;
                    }, 1000);
                }

                function runFourthBatch() {
                    gameStage.textContent = '4th';
                    gameStageText.textContent = 'ON 4 DIGITS WITH 10 TIMES';
                    const interval4 = setInterval(() => {
                        countdownElement.textContent = getNewCount(countdown);
                        if (countdown === 0) {
                            clearInterval(interval4);
                            secondThirdGame.style.display = 'flex';
                            gameBoard.style.display = 'flex';
                            hideElement.style.display = 'none';
                            hidePrize.textContent = '5th Prize';
                            hideDigits.textContent = 'ON 4 DIGITS WITH 100 TIMES';
                            playFourthWinners(fourth_winners, function() {
                                setTimeout(function(){
                                    countdown = window.GAP;
                                    gameBoard.style.display = 'none';
                                    secondThirdGame.style.display = 'none';
                                    secondThirdBoardContainer.innerHTML = '';
                                    hideElement.style.display = 'flex';
                                    runFifthBatch();
                                },10000);
                            });
                        }
                        countdown--;
                    }, 1000);
                }

                function runFifthBatch() {
                    gameStage.textContent = '5th';
                    gameStageText.textContent = 'ON 4 DIGITS WITH 100 TIMES';
                    const interval5 = setInterval(() => {
                        countdownElement.textContent = getNewCount(countdown);
                        if (countdown === 0) {
                            clearInterval(interval5);
                            const dividedArrays = chunkArray(fifth_winners, 20);
                            console.log(dividedArrays);
                            fifthGame.style.display = 'flex';
                            mCountdownElement.textContent = "SET";
                            countdownElement.textContent = 1;

                            function processArraySequentially(index) {
                                if (index >= dividedArrays.length) {
                                    hideElement.style.display = 'none';
                                    gameBoard.style.display = 'none';
                                    endScreen.style.display = 'flex';
                                    setTimeout(function () {
                                        $('#check-box-container').css('display', 'none');
                                        document.getElementById('drawTxtT').textContent = 'LIVE DRAW COMPLETED';
                                        document.getElementById('welcomeContainer').style.display = 'flex';
                                    }, 5000);
                                    return;
                                }
                                setTimeout(function () {
                                    gameBoard.style.display = 'flex';
                                    fifthBoardContainer.innerHTML = '';
                                    hideElement.style.display = 'none';
                                    countdownElement.textContent = index + 2;

                                    playFifthWinners(dividedArrays[index], function() {
                                        setTimeout(function() {
                                            gameBoard.style.display = 'none';
                                            hideElement.style.display = 'flex';
                                            processArraySequentially(index + 1);
                                        }, 10000); // Wait 10 seconds before moving to the next batch
                                    });

                                }, 3000);

                            }
                            processArraySequentially(0);
                        }
                        countdown--;
                    }, 1000);
                }

                const chunkArray = (arr, chunkSize) => {
                    const result = [];
                    for (let i = 0; i < arr.length; i += chunkSize) {
                        result.push(arr.slice(i, i + chunkSize));
                    }
                    return result;
                }

            },
            error: function(xhr) {
                // Error handling logic
            },
            complete: function() {
                // Completion logic
            }
        });
    }

    function getNewCount(countdown) {
        if (countdown < 10) {
            var newCount = "0" + countdown;
        } else {
            var newCount = countdown;
        }
        return newCount;
    }

    $('#mainBtn').click(function () {
        // fetchGameData(5);
        // startZoomSequence();
    });

    $('#audionCheck').click(function () {
        audioPlayer.play();
        $('#AllCk').prop('disabled', false);
    });

    $('#AllCk').click(function () {
        if ($('#ck').prop('checked')) {
            $('#check-box-container').css('display', 'none');
        } else {
            alert("Run audio check");
        }
    });

    const gameBoard = document.getElementById('gameBoard');
    const firstGame = document.getElementById('firstGame');

    const steps = [
        { scale: 2, focusX: 0, focusY: 0 },        // Zoom into top-left (Dog)
        { scale: 2, focusX: 0, focusY: 320 },     // Pan to Cat
        { scale: 3, focusX: 110, focusY: -110 },     // Pan to Sticker 1
    ];

    let currentStep = 0;

    function startZoomSequence() {
        if (currentStep < steps.length) {
            const { scale, focusX, focusY } = steps[currentStep];
            applyZoomAndPan(scale, focusX, focusY);

            // Move to the next step after a delay
            currentStep++;
            setTimeout(startZoomSequence, 1500); // Adjust delay as needed
        } else {
            // Reset everything after the sequence
            setTimeout(resetZoom, 1500);
        }
    }

    function applyZoomAndPan(scale, focusX, focusY) {
        const containerWidth = gameBoard.offsetWidth;
        const containerHeight = gameBoard.offsetHeight;

        // Calculate the offset to keep the focus point visible
        const offsetX = (containerWidth / 2) - focusX * scale;
        const offsetY = (containerHeight / 2) - focusY * scale;

        // Apply transformations
        firstGame.style.transform = `scale(${scale})`;
        firstGame.style.left = `${offsetX}px`;
        firstGame.style.top = `${offsetY}px`;
    }

    function resetZoom() {
        // Reset transformations
        firstGame.style.transform = 'scale(1)';
        firstGame.style.left = '0px';
        firstGame.style.top = '0px';

        // Reset the step counter
        currentStep = 0;
    }
});
