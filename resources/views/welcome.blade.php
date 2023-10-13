<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <style>
        /*! normalize.css v8.0.1 | MIT License | github.com/necolas/normalize.css */
    </style>
    <style>
        /* Style for the card container */
        .card {
            width: 100%;
            border: 1px solid #ccc;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Style for the card header containing buttons */
        .card-header {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            background-color: #f0f0f0;
        }

        /* Style for the top left button */
        .top-left-button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 15px 10px;
            cursor: pointer;
        }

        /* Style for the top right button */
        .top-right-button {
            background-color: #ff5722;
            color: #fff;
            border: none;
            padding: 15px 10px;
            cursor: pointer;
        }

        /* Style for the image preview area */
        .image-preview {
            width: 300px;
            /* Set the desired width of the container */
            height: 300px;
            /* Set the desired height of the container */
            border: 1px solid #ccc;
            display: flex;
            justify-content: center;
            /* Center horizontally */
            align-items: center;
            /* Center vertically */
        }

        /* Style for the image container */
        .image-container {
            max-width: 100%;
            max-height: 100%;
            margin: auto;
            /* Center the image within its container */
        }
        .thumbnail {
            width: 100px; /* Adjust the width of the thumbnail as needed */
            height: 100px; /* Adjust the height of the thumbnail as needed */
            margin: 10px;
            display: inline-block;
        }

        .file-name {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="card">
        <h1 style="text-align:center">Laravel OCR</h1>
    </div>
    <div class="card">
        <div class="card-header">
            <a type="button" href="{{ route('integrate-google-drive') }}" class="top-left-button">Integrate Google
                Drive</a>
            @php
                $link = isset($imageLink) ? $imageLink : 'NoImage';
            @endphp 
            
        </div>
    <!-- preview images and select to convert  -->
    <div class="container">
    <h1>Images in Drive:</h1>
    <form action="{{ route('convert-image-from-drive') }}" method="post">
        @csrf
        <div class="thumbnails">
            @if (isset($imageFiles) && count($imageFiles) > 0)
                @foreach ($imageFiles as $imageFile)
                    <div class="thumbnail">
                        <input type="checkbox" name="selectedImages[]" value="{{ $imageFile->webContentLink }}" id="image{{ $imageFile->getId() }}">
                        <label for="image{{ $imageFile->getId() }}">
                            <img height="50px" width="50px"  src="{{ $imageFile->webContentLink }}" alt="{{ $imageFile->getName() }}" class="thumbnail-image">
                            <div class="file-name">{{ $imageFile->getName() }}</div>
                        </label>
                    </div>
                @endforeach
            @else
                <p>No image files found in the Data folder.</p>
            @endif
        </div>
      
        <button style="text-align:center" type="submit" href="{{ route('convert-image-from-drive') }}"
                class="top-right-button">Convert Selected Image to Text</a>
    </form>
</div>



</div>


</body>

</html>
