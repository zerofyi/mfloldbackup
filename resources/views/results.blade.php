<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="title" content="Meghalaya Future Lottery">
    <meta name="description" content="Meghalaya Future Lottery is a trusted platform for live lottery results, where users can easily access up-to-date lottery results (Sambad) for the Meghalaya Future Lottery. The site features results for daily draws at 12 PM, 5 PM, and 9 PM, allowing users to view past results by date and time slot for their convenience. Stay updated with the latest lottery outcomes and never miss a draw!">
    <meta name="keywords" content="Meghalaya Future Lottery, Meghalaya Future Lottery Sambad, Meghalaya Future 12PM Sambad, Meghalaya Future 5PM Lottery Sambad, Meghalaya Future 9PM Lottery Sambad, Assam Lottery Sambad, Sambad Live Lottery, Assam Lottery Results Today, Live Meghalaya Future Sambad Results, Daily Meghalaya Future Lottery Results, Meghalaya Future Lottery Draws, Sambad Assam Lottery 12PM, Sambad Assam Lottery 5PM, Sambad Assam Lottery 9PM, Assam Lottery Daily Sambad, Meghalaya Future lottery sambad">
    <meta name="robots" content="index, nofollow">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="language" content="English">

    <title>Meghalaya Future Lottery Sambad</title>
</head>
<style>
    body{
        padding: 0;
        margin: 0;
        font-family: sans-serif;
        background-color: rgb(236, 236, 236);
    }
    body header{
        background-color: #fcb900;
        padding: 20px 15px;
    }
    body header nav{
        display: flex;
        align-items: center;
        gap: 15px;
    }
    body header nav img{
        display: block;
        width: 70px;
    }
    body header nav a{
        font-size: 30px;
        font-weight: 700;
        color: #cc007a;
        text-decoration: none;
    }
    body main{
        padding: 15px;
    }
    body main .main-container .title{
        font-size: 30px;
        margin-top: 5px;
        margin-bottom: 15px;
    }
    body main .main-container .today-result{
        background-color: white;
        padding: 15px;
        border-radius: 5px;
        display: flex;
        flex-direction: column;
        gap: 5px;
        margin-bottom: 15px;
        text-decoration: none;
        color: black;
        text-align: center;
    }
    body main .main-container .today-result .time{
        font-size: 40px;
        font-weight: 700;
        margin-bottom: 5px;
    }
    body main .main-container .today-result .button{
        background-color: #cc007a;
        padding: 15px 15px;
        text-align: center;
        font-size: 16px;
        color: white;
        margin-top: 10px;
        border-radius: 5px;
        text-decoration: none;
    }
    body main .main-container .title{
        font-size: 25px;
        margin-top: 15px;
    }
    body main .main-container .old-result-container{
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 5px;
    }
    body main .main-container .old-result-container .old-result{
        text-decoration: none;
        color: black;
        display: flex;
        flex-direction: column;
        gap: 10px;
        align-items: center;
        margin-top: 15px;
        padding: 15px;
        background-color: white;
        border-radius: 5px;
    }
    body main .main-container .old-result-container .old-result .slot{
        font-size: 25px;
        font-weight: 700;
        text-align: center;
    }
    body main .main-container .old-result-container .old-result .description span{
        font-weight: 700;
        font-size: 20px;
    }
    body main .main-container .show-all-result{
        margin-top: 50px;
        padding: 50px;
        background-color: #fcb900;
        text-align: center;
        border-radius: 5px;
    }
    body main .main-container .show-all-result a{
        text-decoration: none;
        color: black;
        font-size: 20px;
    }
</style>
<body>
    <header>
        <nav>
            <img src="{{ asset('image/logo.png') }}" alt="">
            <a class="title" href="/">Meghalaya Future Lottery</a>
        </nav>
    </header>
    @php
        use Carbon\Carbon;
        date_default_timezone_set('Asia/Kolkata');
        $today = Carbon::today()->toDateString();
        use Illuminate\Support\Facades\Request;
    @endphp
    <main>
        <div class="main-container">
            @if (Request::is('*all*'))
            <div class="title">All results</div>
            <div class="old-result-container">
                @foreach ($games as $game)
                <a class="old-result" href="{{ route('result.download', $game->id) }}">
                    <div class="slot">{{ $game->slot }}</div>
                    <div class="date">{{ \Carbon\Carbon::parse($game->date)->format('d/m/Y') }}</div>
                </a>
                @endforeach
            </div>
            @else
            {{-- <div class="title">Today's result</div> --}}
            @foreach ($games as $game)
                @if ($game->date == $today)
                <a href="{{ route('result.download', $game->id) }}" class="today-result">
                    <div class="time">{{ $game->slot }}</div>
                    <div class="date">Date: {{ \Carbon\Carbon::parse($game->date)->format('d/m/Y') }}</div>
                    <div class="button">click here to view result</div>
                </a>
                @endif
            @endforeach
            <div class="show-all-result">
                <a href="all">
                    click here to show all result
                </a>
            </div>
            @endif
        </div>
    </main>

    <footer></footer>
</body>
</html>
