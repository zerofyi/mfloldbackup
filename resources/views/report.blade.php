<x-app-layout>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9fafb;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            font-size: 24px;
            text-align: center;
            margin-bottom: 20px;
            color: #333333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #f4f4f4;
            font-weight: bold;
            color: #555555;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            color: #ffffff;
        }

        .btn-primary {
            background-color: #007bff;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn-secondary {
            background-color: #6c757d;
        }

        .btn:hover {
            opacity: 0.9;
        }
    </style>

    <div class="container">
        <h1>Games Dashboard</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Slot</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($games->sortByDesc('id') as $game)
                <tr style="background-color: {{ $game->status === 'live' ? '#d4edda' : '#fff3cd' }};">
                    <td>{{ $game->id }}</td>
                    <td>{{ $game->date }}</td>
                    <td>{{ $game->slot }}</td>
                    <td>{{ ucfirst($game->status) }}</td>
                    <td>
                        @if ($game->status === 'draft')
                        <button class="btn btn-primary publishBtn" data-id="{{ $game->id }}">Publish</button>
                        @else
                        {{-- <button class="btn btn-danger unpublishBtn" data-id="{{ $game->id }}">Unpublish</button> --}}
                        @endif
                        @if ($game->status !== 'draft')
                        <button class="btn btn-secondary replayBtn" data-id="{{ $game->id }}">Replay</button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
        const publishButtons = document.querySelectorAll('.publishBtn');
        const replayButtons = document.querySelectorAll('.replayBtn');
        const unpublishButtons = document.querySelectorAll('.unpublishBtn');

        // Handle publish button click
        publishButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();

                const gameId = button.getAttribute('data-id');
                button.disabled = true; // Disable button to prevent duplicate clicks
                button.innerHTML = 'Publishing...';

                // Show loading or other visual indicator
                console.log(`Publishing game with ID: ${gameId}`);

                $.ajax({
                    url: "{{ route('publish') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    data: {
                        id: gameId
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            alert(response.message);
                            location.reload(); // Reload the page or update the UI dynamically
                        } else {
                            alert(response.message);
                            button.disabled = false;
                            button.innerHTML = 'Publish';
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let errorHtml = '';
                            $.each(errors, function (key, value) {
                                errorHtml += value[0] + '\n';
                            });
                            alert(errorHtml);
                        }
                        button.disabled = false;
                        button.innerHTML = 'Publish';
                    },
                    complete: function () {
                        button.disabled = false;
                        button.innerHTML = 'Publish';
                    }
                });
            });
        });

        // Handle replay button click
        replayButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();

                const gameId = button.getAttribute('data-id');
                button.disabled = true; // Disable button to prevent duplicate clicks
                button.innerHTML = 'Replaying...';

                // Show loading or other visual indicator
                console.log(`Replaying game with ID: ${gameId}`);

                $.ajax({
                    url: "{{ route('replay') }}", // Replace with your actual replay route
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    data: {
                        id: gameId
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            alert(response.message);
                            location.reload(); // Reload the page or update the UI dynamically
                        } else {
                            alert(response.message);
                            button.disabled = false;
                            button.innerHTML = 'Replay';
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let errorHtml = '';
                            $.each(errors, function (key, value) {
                                errorHtml += value[0] + '\n';
                            });
                            alert(errorHtml);
                        }
                        button.disabled = false;
                        button.innerHTML = 'Replay';
                    },
                    complete: function () {
                        button.disabled = false;
                        button.innerHTML = 'Replay';
                    }
                });
            });
        });

        // Handle unpublish button click
        unpublishButtons.forEach(button => {
            button.addEventListener('click', () => {
                const gameId = button.getAttribute('data-id');
                console.log('Unpublish game with ID:', gameId);
                // Add unpublish logic here
            });
        });
    });
    </script>
</x-app-layout>
