<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marriage Certificate</title>
    <style>
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
            padding: 20px;
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

        .subtitle, .united, .details, .witness {
            font-family: 'Lato', sans-serif;
            margin: 10px 0;
        }

        .names {
            font-family: 'Great Vibes', cursive;
            font-size: 36px;
            margin: 10px 0;
        }

        .ampersand {
            margin: 0 20px;
        }

        .united {
            font-size: 24px;
            font-style: italic;
            margin: 10px 0;
        }

        .details, .witness {
            margin-top: 10px;
        }

        .signature {
            font-family: 'Great Vibes', cursive;
            font-size: 12px;
        }

        /* Floral decorations */
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

        .witnesses {
            margin-top: 10px;
            text-align: center;
        }

        .witnesses h2 {
            font-family: 'Great Vibes', cursive;
            font-size: 28px;
            color: #5f9ea0;
            margin-bottom: 10px;
            text-align: center;
        }

        .witness-names {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }

        .witness {
            font-family: 'Lato', sans-serif;
            font-size: 18px;
            padding: 10px;
            border: 1px solid #5f9ea0;
            border-radius: 5px;
            width: 20%;
            max-width: 100px;
            text-align: center;
        }

    </style>
</head>
<body>
    <div class="certificate">
        <h1 class="title">Marriage Certificate</h1>
        <p class="subtitle">This is to certify that</p>
        {{-- <p>Marriage Date: {{ $marriage->marriage_date->format('F d, Y') }}</p> --}}
        <div class="names">
            <span class="bride">{{ $spouse1->firstname }} {{ $spouse1->lastname }}</span>
            <span class="ampersand">&</span>
            <span class="groom">{{ $spouse2->firstname }} {{ $spouse2->lastname }}</span>
        </div>
        
        <p class="united">Were United in Holy Matrimony</p>
        <div class="details">
            <p>on <span class="date"><u>{{ $marriage->created_at->format('F j, Y') }} </u></span></p>
            <p>At <span class="location">{{ $officiant->church->name }} Church</span></p>
            <p>By <span class="officiant"><b>Pastor </b> {{ $officiant->firstname }} {{ $officiant->lastname }}</span></p>
        </div>

        <div class="witnesses">
            <h2>Witnesses:</h2>
            <div class="witness-names">
                <div class="witness">{{ $spouse1->god_parent }}</div>
                <div class="witness">{{ $spouse2->god_parent }}</div>
            </div>
        </div>
    
        {{-- <div class="content">
            <h2>Spouse 1 Information:</h2>
            <div class="spouse-info">
                <p>Username: {{ $spouse1->username }}</p>
                <p>First Name: {{ $spouse1->firstname }}</p>
                <p>Last Name: {{ $spouse1->lastname }}</p>
                <p>Email: {{ $spouse1->email }}</p>
                <p>Date of Birth: {{ $spouse1->date_of_birth }}</p>
                <p>Phone: {{ $spouse1->phone }}</p>
                <p>Mother's Name: {{ $spouse1->mother_name }}</p>
                <p>Father's Name: {{ $spouse1->father_name }}</p>
                <p>God Parent: {{ $spouse1->god_parent }}</p>
                <p>Church ID: {{ $spouse1->church_id }}</p>
                <p>Baptized: {{ $spouse1->baptized ? 'Yes' : 'No' }}</p>
                <p>Baptized By: {{ $spouse1->baptized_by }}</p>
                <p>Ministry ID: {{ $spouse1->ministry_id }}</p>
                <p>Active Status: {{ $spouse1->active_status }}</p>
                <p>Marital Status: {{ $spouse1->marital_status }}</p>
            </div>

            <h2>Spouse 2 Information:</h2>
            <div class="spouse-info">
                <p>Username: {{ $spouse2->username }}</p>
                <p>First Name: {{ $spouse2->firstname }}</p>
                <p>Last Name: {{ $spouse2->lastname }}</p>
                <p>Email: {{ $spouse2->email }}</p>
                <p>Date of Birth: {{ $spouse2->date_of_birth }}</p>
                <p>Phone: {{ $spouse2->phone }}</p>
                <p>Mother's Name: {{ $spouse2->mother_name }}</p>
                <p>Father's Name: {{ $spouse2->father_name }}</p>
                <p>God Parent: {{ $spouse2->god_parent }}</p>
                <p>Church ID: {{ $spouse2->church_id }}</p>
                <p>Baptized: {{ $spouse2->baptized ? 'Yes' : 'No' }}</p>
                <p>Baptized By: {{ $spouse2->baptized_by }}</p>
                <p>Ministry ID: {{ $spouse2->ministry_id }}</p>
                <p>Active Status: {{ $spouse2->active_status }}</p>
                <p>Marital Status: {{ $spouse2->marital_status }}</p>
            </div>
        </div>
        
        <div class="footer">
            <h2>Officiant Information:</h2>
            <p>Name: {{ $officiant->firstname }} {{ $officiant->lastname }}</p>
            <p>Email: {{ $officiant->email }}</p>
            <p>Phone: {{ $officiant->phone }}</p>
            <p>Church ID: {{ $officiant->church_id }}</p>
            <p>Ministry ID: {{ $officiant->ministry_id }}</p>
        </div> --}}
    </div>
    {{-- <div style="text-align:center; margin-top: 30px;">
        {!! DNS2D::getBarcodeHTML("$spouse1->id",'QRCODE',4,4) !!}
    </div> --}}
</body>
</html>