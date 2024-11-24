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

        .subtitle, .details, .footer {
            font-family: 'Lato', sans-serif;
            margin: 5px 0;
        }

        .names {
            font-family: 'Great Vibes', cursive;
            font-size: 36px;
            margin: 20px 0;
        }

        .ampersand {
            margin: 0 20px;
            color: #5f9ea0;
        }

        .details {
            margin-top: 20px;
        }

        .form-row {
            margin-bottom: 5px;
            text-align: left;
        }

        .form-row label {
            display: inline-block;
            width: 150px;
            font-weight: bold;
        }

        .form-row .input {
            border-bottom: 1px solid #5f9ea0;
            display: inline-block;
            width: calc(100% - 160px);
            font-style: italic;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
        }

        .witnesses {
            margin-top: 20px;
        }

        .witnesses-title {
            font-family: 'Great Vibes', cursive;
            color: #5f9ea0;
            font-size: 28px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <h1 class="title">Marriage Certificate</h1>
        <div class="subtitle">GICUMBI Diocese - TUMBA Parish</div>
        <p class="subtitle">This is to certify that</p>
        <div class="names">
            <span>{{ $spouse1->firstname }} {{ $spouse1->lastname }}</span>
            <span class="ampersand">&</span>
            <span>{{ $spouse2->firstname }} {{ $spouse2->lastname }}</span>
        </div>
        <p class="subtitle">Were United in Holy Matrimony</p>
        <div class="details">
            <div class="form-row">
                <label>Officiated by:</label>
                <span class="input">
                    <b>Pastor</b> {{ $officiant->firstname }} {{ $officiant->lastname }}
                </span>
            </div>
            <div class="form-row">
                <label>Church:</label>
                <span class="input">{{ $officiant->church->name }}</span>
            </div>
            <div class="form-row">
                <label>Date:</label>
                <span class="input">{{ $marriage->created_at->format('F j, Y') }}</span>
            </div>
        </div>
        <div class="witnesses">
            <h2 class="witnesses-title">Witnesses</h2>
            <div class="form-row">
                <label>For the Bride:</label>
                <span class="input">{{ $spouse1->god_parent }}</span>
            </div>
            <div class="form-row">
                <label>For the Groom:</label>
                <span class="input">{{ $spouse2->god_parent }}</span>
            </div>
        </div>
        <div class="footer">
            <p>Done at {{ $marriage->created_at->format('F j, Y') }}</p>
        </div>
        <div style="text-align:center; margin-top: 30px;">
        </div>
    </div>
</body>
</html>