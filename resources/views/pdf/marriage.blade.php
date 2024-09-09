

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $marriage->bride_first_name }} marriage Certificate</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Include FontAwesome CSS -->
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"> --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: "Roboto", sans-serif;
            font-weight: 400;
            font-style: normal;
        }
        .certificate {
            border: 2px solid black;
            padding: 0px;
            margin: 0px;
            text-align: center;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
        }
        .names {
            font-size: 20px;
            margin: 5px 0;
        }
        .date {
            font-size: 16px;
            margin-bottom:5px;
        }
        .signature {
            font-size: 16px;
            border-top: 1px solid black;
            width: 50%;
            margin: 0 auto;
        }
        code {
            color: black;
        }
    </style>
</head>
<body>
    <div class="container mt-3">
        <div class="card certificate">
            <div class="card-header text-center">
                <i class="fas fa-cross"></i><h2>Marriage Certificate</h2>
            </div>
            <div class="card-body">
                
                    <p>This is to certify that</p>
                    <h4 class="text-center mb-2">
                        <span id="bride_name">{{ $marriage->bride_first_name }}  {{ $marriage->bride_last_name }}</span> and <span id="groom_name">{{ $marriage->groom_first_name }} {{ $marriage->groom_last_name }}</span>
                    </h4>
                    <p>was officially married on <strong>{{ $marriage->date }}</strong></p>
                    <p>at <strong>{{ $marriage->church_name }} Church</strong></p>
                    <p> by: Father <strong>{{ $marriage->clergy_name }}</strong>
                    </p>
                    <p>Witnessed by:</p>
                        <div class="row">
                            <div class="col-3">
                                <b>God Parent:</b>
                            </div>
                            <div class="col-9">
                                {{ $marriage->god_father_name }}<i>(godfather)</i><br>
                                {{ $marriage->god_mother_name }}<i>(godmother)</i>
                            </div>
                        </div>
                </div>
                <p><code>certificate generated at: {{ now() }}</code></p>
            <div class="card-footer text-center">
                <p>"Therefore go and make disciples of all nations, baptizing them in the name of the Father and of the Son and of the Holy Spirit." - Matthew 28:19</p>
               {!! DNS2D::getBarcodeHTML("$marriage->id",'QRCODE',4,4) !!}
            </div>
            </div>
            
            
        </div>
    </div>

    <!-- Include Bootstrap JS (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
