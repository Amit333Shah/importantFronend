<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap Form Example</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2>Upload Form</h2>
        <form method="POST" action="{{route('addBaner')}}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name">
            </div>
            <div class="form-group">
                <label for="image">Upload Image</label>
                <input type="file" class="form-control-file form-control"  name="logo" id="image" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <form action="{{route('addFeature')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="title">Title:</label>
        <input type="text" name="titleH" id="titleH" required>
    
        <label for="description">Description:</label>
        <textarea name="desH" id="desH" required></textarea>
    
        <label for="image">Image:</label>
        <input type="file" name="img" id="img" required>
    
        <button type="submit">Save</button>
    </form>


    {{--  Table show --}}
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Image</th>
            <th>action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($titles as $index => $title)
            <tr>
                <td>{{ $title }}</td>
                <td>{{ $descriptions[$index] }}</td>
                <td>
                    <img src="{{ asset('storage/' . $images[$index]) }}" alt="Image" style="width: 100px; height: auto;">
                </td>
                <td>                    <button class="btn btn-primary edit-btn" data-index="{{ $index }}">Edit</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
