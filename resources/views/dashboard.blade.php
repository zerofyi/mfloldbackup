<x-app-layout>
    <link rel="stylesheet" href="{{ asset('style/dashboard.css') }}">
    <div class="main-container">
        <div class="form-container bg-white dark:bg-gray-800">
            <form id="resultForm" action="{{ route('result.generate') }}" method="POST">
                @csrf
                <div>
                    <x-text-input type="date" name="date" id="datePicker" required />
                </div>
                <div>
                    <select name="slot" id="slot" required class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                        <option value="01:55 PM">01:55 PM</option>
                        <option value="06:55 PM">06:55 PM</option>
                        <option value="08:55 PM">08:55 PM</option>
                    </select>
                </div>
                <div class="input-group">
                    <select name="series_number" required class="w-20 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                        <option value="86">86</option><option value="42">42</option><option value="31">31</option>
                        <option value="29">29</option><option value="54">54</option><option value="66">66</option>
                        <option value="73">73</option><option value="92">92</option><option value="17">17</option>
                        <option value="33">33</option><option value="78">78</option><option value="12">12</option>
                    </select>
                    <select name="series_letter" required class="w-20 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                        <option value="A">A</option><option value="B">B</option><option value="C">C</option>
                        <option value="D">D</option><option value="E">E</option><option value="F">F</option>
                        <option value="K">K</option><option value="L">L</option><option value="M">M</option>
                        <option value="N">N</option><option value="R">R</option><option value="X">X</option>
                    </select>
                    <x-text-input type="number" name="winner" class="w-80" placeholder="12345" required/>
                </div>
                <div>
                    <x-text-input type="number" name="secret" id="secret" placeholder="Enter secret key" required/>
                </div>
                <div>
                    <x-primary-button type="submit" id="submitBtn">Generate Result</x-primary-button>
                </div>
                <div id="loading" style="display:none; color: #606874;">Processing...</div>
            </form>
        </div>
        <div class="response-container bg-white dark:bg-gray-800">
            <div class="title dark:text-white text-gray-800">Response result</div>
            <div class="result-container">
                <div class="error-txt" style="color: red;"></div>
                <div class="first-winners winners">
                    <div class="data">First winner: <span class="dark:text-white text-gray-800"></span></div>
                </div>
                <div class="second-winners winners">
                    <div class="data">Second winners: <span class="dark:text-white text-gray-800"></span></div>
                </div>
                <div class="third-winners winners">
                    <div class="data">Third winners: <span class="dark:text-white text-gray-800"></span></div>
                </div>
                <div class="fourth-winners winners">
                    <div class="data">Foutrh winners: <span class="dark:text-white text-gray-800"></span></div>
                </div>
                <div class="fifth-winners winners">
                    <div class="data">Fifth winners: <span class="dark:text-white text-gray-800"></span></div>
                </div>
            </div>

            <div class="button-container">
                <div>
                    <x-text-input value="" type="hidden" name="id" id="gameId" />
                    <x-primary-button type="submit" id="publishBtn">Publish</x-primary-button>
                    <x-secondary-button type="submit" id="deleteBtn">Delete</x-secondary-button>
                </div>
                <div id="loading2" style="display:none; margin-top: 7px; color: #606874;">Processing...</div>
            </div>
        </div>
    </div>
</x-app-layout>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
    const indiaTime = new Date().toLocaleDateString('en-CA', {
        timeZone: 'Asia/Kolkata',
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
    }).replace(/\//g, '-');

    document.getElementById('datePicker').value = indiaTime;

    $(document).ready(function() {

        // Delete Button Click Event
        $('#deleteBtn').on('click', function(e) {
            e.preventDefault(); // Prevent default action

            if (confirm('Are you sure you want to delete this result?')) {
                $('#deleteBtn').attr('disabled', true); // Disable button during request
                $('#loading2').show(); // Show loading indicator
                $('.error-txt').html(''); // Clear any previous errors

                $.ajax({
                    url: "{{ route('delete.result') }}", // Delete route
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}" // Add CSRF token for security
                    },
                    data: {
                        id: $('#gameId').val(), // Game ID input value
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            alert(response.message); // Show success message
                            location.reload(); // Refresh the page after successful delete
                        } else {
                            $('.error-txt').html(response.message); // Show error message
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let errorHtml = '<div style="color: red;">';
                            $.each(errors, function(key, value) {
                                errorHtml += '<p>' + value[0] + '</p>';
                            });
                            errorHtml += '</div>';
                            $('.error-txt').html(errorHtml); // Show validation errors
                        }
                    },
                    complete: function() {
                        $('#loading2').hide(); // Hide loading indicator
                        $('#deleteBtn').attr('disabled', false); // Re-enable button
                    }
                });
            }
        });

        $('#publishBtn').on('click', function(e) {
            e.preventDefault();

            //$('#publishBtn').attr('disabled', true);
            $('#loading2').show();
            $('.error-txt').html('');

            $.ajax({
                url: "{{ route('publish') }}", // Form action URL
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                data: {
                    id: $('#gameId').val(),
                },
                success: function(response) {
                    if (response.status === 'success') {
                        alert(response.message);
                    } else {
                        $('.error-txt').html(response.message);
                        $('#publishBtn').attr('disabled', false);
                    }
                },
                error: function(xhr) {
                    // Handle validation errors
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let errorHtml = '<div style="color: red;">';
                        $.each(errors, function(key, value) {
                            errorHtml += '<p>' + value[0] + '</p>';
                        });
                        errorHtml += '</div>';
                        $('.error-txt').html(errorHtml);

                        // Re-enable the submit button if there's an error
                        $('#publishBtn').attr('disabled', false);
                    }
                },
                complete: function() {
                    // Hide the loading state regardless of success or error
                    $('#loading2').hide();
                }
            });
        });

        $('#resultForm').on('submit', function(e) {
            e.preventDefault(); // Prevent the form from submitting normally

            // Disable the submit button and show the loading state
            $('#submitBtn').attr('disabled', true);
            $('#loading').show();

            // Clear the result container
            $('.error-txt').html('');

            // Make the AJAX request
            $.ajax({
                url: $(this).attr('action'), // Form action URL
                method: 'POST',
                data: $(this).serialize(), // Serialize form data
                success: function(response) {
                    if (response.status === 'success') {
                        $('.winners').show();
                        // Append the result to the result container
                        $('.first-winners .data span').html(response.data.first);

                        let secondWinners = response.data.second;
                        let formattedsecondWinners = secondWinners.join(', ');
                        $('.second-winners .data span').html(formattedsecondWinners);

                        let thirdWinners = response.data.third;
                        let formattedthirdWinners = thirdWinners.join(', ');
                        $('.third-winners .data span').html(formattedthirdWinners);

                        let fourthWinners = response.data.fourth;
                        let formattedfourthWinners = fourthWinners.join(', ');
                        $('.fourth-winners .data span').html(formattedfourthWinners);

                        let fifthWinners = response.data.fifth;
                        let formattedfifthWinners = fifthWinners.join(', ');
                        $('.fifth-winners .data span').html(formattedfifthWinners);
                        // Keep the form disabled after successful submission
                        $('#gameId').val(response.data.id);
                        $('.button-container').show();
                    } else {
                        $('.error-txt').html(response.message);
                        $('#submitBtn').attr('disabled', false);
                    }
                },
                error: function(xhr) {
                    // Handle validation errors
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let errorHtml = '<div style="color: red;">';
                        $.each(errors, function(key, value) {
                            errorHtml += '<p>' + value[0] + '</p>';
                        });
                        errorHtml += '</div>';
                        $('.error-txt').html(errorHtml);

                        // Re-enable the submit button if there's an error
                        $('#submitBtn').attr('disabled', false);
                    }
                },
                complete: function() {
                    // Hide the loading state regardless of success or error
                    $('#loading').hide();
                }
            });
        });
    });
</script>
