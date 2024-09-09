<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Marriage Certificate</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Great+Vibes&family=Lato&display=swap');

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }

        .certificate {
            width: 800px;
            padding: 40px;
            background-color: white;
            border: 2px solid #5f9ea0;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
            position: relative;
        }

        .title {
            font-family: 'Great Vibes', cursive;
            color: #5f9ea0;
            font-size: 48px;
            margin-bottom: 20px;
            position: relative;
        }

        .title::before, .title::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 100px;
            height: 2px;
            background-color: #5f9ea0;
        }

        .title::before {
            left: -110px;
        }

        .title::after {
            right: -110px;
        }

        .subtitle, .united, .details, .witness {
            font-family: 'Lato', sans-serif;
            margin: 10px 0;
        }

        .names {
            font-family: 'Great Vibes', cursive;
            font-size: 36px;
            margin: 20px 0;
        }

        .ampersand {
            margin: 0 20px;
        }

        .united {
            font-size: 24px;
            font-style: italic;
            margin: 20px 0;
        }

        .details, .witness {
            margin-top: 30px;
        }

        .signature {
            font-family: 'Great Vibes', cursive;
            font-size: 24px;
        }

        /* Floral decorations */
        .certificate::before, .certificate::after {
            content: '🌸';
            font-size: 48px;
            position: absolute;
        }

        .certificate::before {
            top: 10px;
            left: 10px;
        }

        .certificate::after {
            bottom: 10px;
            right: 10px;
        }

        .witnesses {
            margin-top: 40px;
            text-align: center;
        }

        .witnesses h2 {
            font-family: 'Great Vibes', cursive;
            font-size: 28px;
            color: #5f9ea0;
            margin-bottom: 20px;
        }

        .witness-names {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }

        .witness {
            font-family: 'Lato', sans-serif;
            font-size: 18px;
            padding: 10px;
            border: 1px solid #5f9ea0;
            border-radius: 5px;
            width: 40%;
            max-width: 200px;
        }

    </style>
</head>
<body>
    <div class="certificate">
        <h1 class="title">Marriage Certificate</h1>
        <p class="subtitle">This is to certify that</p>
        <div class="names">
            <span class="bride">{{ $user->firstname }} {{ $user->lastname }}</span>
            <span class="ampersand">&</span>
            <span class="groom">{{ $spouse->firstname }} {{ $spouse->lastname }}</span>
        </div>
        <p class="united">Were United in Holy Matrimony</p>
        <div class="details">
            <p>on <span class="date">{{ $marriage_date->format('F j, Y') }}</span></p>
            <p>At <span class="location">{{ $officiant->church->name }}</span></p>
            <p>By <span class="officiant"><b>Pastor </b> {{ $officiant->firstname }} {{ $officiant->lastname }}</span></p>
        </div>
        <div class="witnesses">
            <h2>Witnesses:</h2>
            <div class="witness-names">
                <div class="witness">{{ $user->god_parent }}</div>
                <div class="witness">{{ $spouse->god_parent }}</div>
            </div>
        </div>
        <div style="text-align:center; margin-top: 30px;">
            {!! DNS2D::getBarcodeHTML("$user->id",'QRCODE',4,4) !!}
        </div>
    <!-- Add more details as needed -->
</body>
</html>