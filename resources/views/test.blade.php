<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Live Draw Test</title>
    @vite('resources/js/app.js')  <!-- Vite Script -->
</head>
<body>
    <h1>Live Draw Listener</h1>
    <h2>Draw ID: <span id="draw-id">Waiting for event...</span></h2>

    <script>
        window.addEventListener('load', function() {
            //console.log(window.Echo);  // Check if Echo is loaded

            if (window.Echo) {
                Echo.channel(`live-draw-channel`)
                    .listen('LiveDraw', (e) => {
                        console.log("a");
                    });
            } else {
                console.error('Echo not initialized');
            }
        });
    </script>
</body>
</html>
