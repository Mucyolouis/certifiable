

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $baptism->name }} Baptism Certificate</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Custom styling for the certificate */
        .certificate {
            border: 2px solid #333; /* Add a border */
            padding: 5px; /* Add padding */
            /* border-radius: 10px; Rounded corners */
            background-color: #f9f9f9; /* Light gray background */
        }
        .certificate h3 {
            font-weight: bold;
            text-align: center;
            margin-bottom: 5px;
        }
        .certificate p {
            margin-bottom: 5px;
        }
        .certificate ul {
            list-style-type: none;
            padding-left: 0;
        }
    </style>
</head>
<body>
    <div class="container mt-3">
        <div class="card certificate">
            <div class="card-body">
                <h3>Recommendation Letter</h3>
                <p>This is to Recommend that</p>
                <h4 class="text-center mb-4">{{ $baptism->name }}</h4>
                <p>is our member of church <strong>{{ $baptism->date }}</strong></p>
                <p>at <strong>{{ $baptism->church_name }} Church</strong></p>
                
                {{-- <p>Witnessed by:</p>
                <ul>
                    <li>
                        Father:{{ $baptism->father_name }}
                    </li>
                    <li>
                        Mother:{{ $baptism->mother_name }}
                    </li>
                    <li>
                        Godparent:{{ $baptism->godparent}}
                    </li>
                </ul> --}}
                <p class="text-center mt-4">May God bless and guide you on your spiritual journey.</p>

                <div style="text-align:center; margin-top: 30px;">
                    {!! DNS2D::getBarcodeHTML(route('baptism.check', ['id' => $baptism->id]), 'QRCODE', 4, 4) !!}
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
