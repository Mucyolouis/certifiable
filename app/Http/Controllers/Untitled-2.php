<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marriage Certificate</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .certificate {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 2px solid #000;
        }
        .header, .footer {
            text-align: center;
            margin-bottom: 20px;
        }
        .content {
            margin-bottom: 20px;
        }
        .spouse-info {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="header">
            <h1>Marriage Certificate</h1>
            <p>Certificate Number: {{ $marriage->certificate_number }}</p>
            <p>Marriage Date: {{ $marriage->marriage_date->format('F d, Y') }}</p>
        </div>
        
        <div class="content">
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
        </div>
    </div>
</body>
</html>