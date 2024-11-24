<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Recommendation Letter</title>
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
            margin-bottom: 10px;
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

        .subtitle, .details, .blessing {
            font-family: 'Lato', sans-serif;
            margin: 10px 0;
        }

        .name {
            font-family: 'Great Vibes', cursive;
            font-size: 36px;
            margin: 10px 0;
        }

        .details {
            margin-top: 30px;
        }

        /* .certificate::before, .certificate::after {
            content: '🌸';
            font-size: 48px;
            position: absolute;
        } */

        .certificate::before {
            top: 10px;
            left: 10px;
        }

        .certificate::after {
            bottom: 10px;
            right: 10px;
        }

        .blessing {
            font-style: italic;
            margin-top: 20px;
        }

        .ministry, .church {
            font-family: 'Lato', sans-serif;
            font-weight: bold;
            color: #5f9ea0;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <h1 class="title">Recommendation Letter</h1>
        <p class="subtitle">This is to Recommend that</p>
        <div class="name">
            {{ $baptism->name }}
        </div>
        <p class="subtitle">is our member of</p>
        <p class="ministry">{{ $ministry->name }}</p>
        <div class="details">
            <p>Since <span class="date">{{ $baptism->baptized_at }}</span></p>
            
            <span class="input">
                <p>Officiated by :
                @if($pastor && $pastor->firstname && $pastor->lastname)
                  <b>Pastor</b> :  {{ $pastor->firstname }} {{ $pastor->lastname }}
                @else
                    Information not available
                @endif
            </p>
            </span>
            <p>At <span class="church">{{ $church->name }} Church</span></p>
        </div>
        <p class="blessing">May God bless and guide you on your spiritual journey.</p>
        <div style="text-align:center; margin-top: 30px;">
            {!! DNS2D::getBarcodeHTML(route('baptism.check', ['id' => $baptism->id]), 'QRCODE', 4, 4) !!}
        </div>
    </div>
</body>
</html>