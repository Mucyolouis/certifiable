

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Users Report</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-3">
        <div class="text-center">
            <h2>Marriages List</h2>
        </div>
        
            <table class="table table-bordered">
                <thead>
                    <tr>
                    <th scope="col">S#</th>
                    <th scope="col">Bride Names</th>
                    <th scope="col">Groom Names</th>
                    <th scope="col">Address</th>
                    <th scope="col">Mother Names</th>
                    <th scope="col">Father Names</th>
                    <th scope="col">Church</th>
                    <th scope="col">Clergy</th>
                    <th scope="col">Marriage Date</th>
                    <th scope="col">Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($marriage as $marriage)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $marriage->bride_first_name }} {{ $marriage->bride_last_name }}</td>
                        <td>{{ $marriage->groom_first_name }} {{ $marriage->groom_last_name }}</td>
                        
                        <td>{{ $marriage->address }}</td>
                        <td>{{ $marriage->god_mother_name }}</td>
                        <td>{{ $marriage->god_father_name }}</td>
                        <td>{{ $marriage->church_name }}</td>
                        <td>{{ $marriage->clergy_name ?? 'N/A' }}</td>
    
                        <td>{{ $marriage->date }}</td>
                        <td>{{ $marriage->created_at }}</td>
    
                        
                    </tr>
                    @empty
                        <td colspan="10">
                            <span class="text-danger">
                                <strong>No Marriage Request Found!</strong>
                            </span>
                        </td>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Include Bootstrap JS (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
