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

    <title>Meghalaya Future Lottery Result - {{ $game->date }} - {{ $game->slot }}</title>
</head>

<style type="text/css">
    * {
        font-family: Verdana, Arial, sans-serif;
    }
    body{

        border: solid 1px black;
        padding: 20px;
    }
    .title{
        font-size: 50px;
        font-weight: 900;
    }
    .sub-title{
        font-size: 25px;
        font-weight: 700;
    }
    .semi-title{
        font-size: 35px;
        font-weight: 800;
    }
    /* table{
        font-size: x-small;
    } */
    tfoot tr td{
        font-weight: bold;
        /* font-size: x-small; */
    }
    .gray {
        background-color: lightgray
    }
    .result-holder{
        text-align: center;
    }
    .mb-20{
        margin-bottom: 20px;
    }
    .mb-10{
        margin-bottom: 10px;
    }
    .winners{
        font-size: 20px;
        font-weight: 700;
    }
    .c-blue{
        color: blue;
    }
    .c-green{
        color: green;
    }
    .watermark{
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        opacity: 0.1;
    }
</style>

</head>
<body>
    <div class="watermark"><img src="{{ public_path('image/logo.png') }}" width="850"></div>
  <table width="100%">
    <tr>
        <td valign="top" align="left"><img src="{{ public_path('image/logo.png') }}" width="150"></td>
        <td style="text-align: center;">
            <div class="sub-title">LOTTERY RESULT</div>
            <div class="title c-blue">MEGHALAYA</div>
            <div class="sub-title c-green">FUTURE LOTTERY</div>
        </td>
        <td valign="top" align="right"><img src="{{ public_path('image/logo.png') }}" width="150"></td>
    </tr>
  </table>

  <br/>

  <table width="100%">
    <tr>
        <td align="left">
            <div class="semi-title">{{ \Carbon\Carbon::parse($game->date)->format('d/m/Y') }}</div>
        </td>
        <td align="right">
            <div class="semi-title">{{ $game->slot }}</div>
        </td>
    </tr>
  </table>

  </br>

  <div class="result-holder">
    <div class="first-prize title mb-20">1st Prize / {{ $game->first }}</div>
    <div class="second-prize mb-20">
        <div class="semi-title mb-10">2nd Prize</div>
        <div class="winners">{{ $game->second_winners }}</div>
    </div>

    <div class="third-prize mb-20">
        <div class="semi-title mb-10">3rd Prize</div>
        <div class="winners">{{ $game->third_winners }}</div>
    </div>

    <div class="fourth-prize mb-20">
        <div class="semi-title mb-10">4th Prize</div>
        <div class="winners">{{ $game->fourth_winners }}</div>
    </div>

    <div class="fifth-prize mb-20">
        <div class="semi-title mb-10">5th Prize</div>
        <div class="winners">{{ $game->fifth_winners }}</div>
    </div>
</br>
</br>
    <div class="footer">www.meghalayafuturelottery.in</div>
  </div>

</body>
</html>

