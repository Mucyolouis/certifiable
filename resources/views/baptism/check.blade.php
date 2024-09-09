<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baptism Check</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full">
        <h1 class="text-2xl font-bold mb-4">Baptism Status Check</h1>
        
        @if(isset($error))
            <p class="text-red-500 mb-4">{{ $error }}</p>
        @elseif(isset($baptism))
            <p class="mb-2"><strong>ID:</strong> {{ $baptism->id }}</p>
            <p class="mb-2"><strong>Name:</strong> {{ $baptism->name }}</p>
            <p class="mb-2"><strong>Baptism Status:</strong> 
                @if($is_baptized)
                    <span class="text-green-500">Baptized</span>
                @else
                    <span class="text-red-500">Not Baptized</span>
                @endif
            </p>
            <p class="mb-2"><strong>Certification Status:</strong> 
                @if($is_certified)
                    <span class="text-green-500">Certified Christian</span>
                @else
                    <span class="text-yellow-500">Not Certified</span>
                @endif
            </p>
        @else
            <p class="text-gray-500 mb-4">Please provide a valid ID to check baptism status.</p>
        @endif
    </div>
</body>
</html>