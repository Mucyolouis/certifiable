
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Baptism Certificate</title>
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

        .name {
            font-family: 'Great Vibes', cursive;
            font-size: 36px;
            margin: 20px 0;
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
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <h1 class="title">Baptism Certificate</h1>
        <div class="subtitle">GICUMBI Diocese - TUMBA Parish</div>
        <p class="subtitle">This is to certify that</p>
        <div class="name">
            {{ $baptism->firstname }} {{ $baptism->lastname }}
        </div>
        <p class="subtitle">Was baptized in the Christian faith</p>
        <div class="details">
            <div class="form-row">
                <label>Baptized by:</label>
                <span class="input">
                    @if($pastor && $pastor->firstname && $pastor->lastname)
                      <b>Pastor</b> :  {{ $pastor->firstname }} {{ $pastor->lastname }}
                    @else
                        Information not available
                    @endif
                </span>
            </div>
            <div class="form-row">
                <label>Church:</label>
                <span class="input">{{ $church->name }}</span>
            </div>
            <div class="form-row">
                <label>Date:</label>
                <span class="input">{{ $baptism->baptized_at }}</span>
            </div>
            <!-- Add more form rows for other fields as needed -->
        </div>
        <div class="footer">
            <p>Done at {{ $baptism->baptized_at }}</p>
            
        </div>
        <div style="text-align:center; margin-top: 30px;">
            {!! DNS2D::getBarcodeHTML(route('baptism.check', ['id' => $baptism->id]), 'QRCODE', 4, 4) !!}
        </div>
    </div>
</body>
</html>