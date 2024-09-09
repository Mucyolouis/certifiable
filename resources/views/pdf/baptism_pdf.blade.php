

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Baptisms Report</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-3">
        <div class="text-center">
            <h2>Baptisms List</h2>
        </div>
        <table class="table table-responsive table-bordered">
            
            <thead>
                <tr>
                <th scope="col">S#</th>
                <th scope="col">Names</th>
                <th scope="col">Address</th>
                <th scope="col">Mother Names</th>
                <th scope="col">Father Names</th>
                <th scope="col">God Parent</th>
                <th scope="col">Church</th>
                <th scope="col">Baptismal Date</th>
                <th scope="col">Created At</th>
                
                </tr>
            </thead>
            <tbody>
                @forelse ($baptism as $baptism)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $baptism->name }}</td>
                    <td>{{ $baptism->address }}</td>
                    <td>{{ $baptism->mother_name }}</td>
                    <td>{{ $baptism->father_name }}</td>
                    <td>{{ $baptism->godparent }}</td>
                    <td>{{ $baptism->church_name }}</td>
                    <td>{{ $baptism->date }}</td>
                    <td>{{ $baptism->created_at }}</td>
                </tr>
                @empty
                    <td colspan="10">
                        <span class="text-danger">
                            <strong>No Baptism Found!</strong>
                        </span>
                    </td>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Include Bootstrap JS (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
