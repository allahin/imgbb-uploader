<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Uploader</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
    .allahyok {
        width: 350px;
    }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Image Uploader</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="image" class="form-label">Select Image:</label>
                <input type="file" class="form-control allahyok" id="image" name="image" accept="image/*">
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // API Key
            $apiKey = '';

            // API endpoint
            $apiUrl = 'https://api.imgbb.com/1/upload';

            // Return if file not uploaded or error
            if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                echo '<div class="alert alert-danger mt-3" role="alert">Error uploading image.</div>';
            } else {
                // Prepare the file
                $image = $_FILES['image']['tmp_name'];
                $imageName = $_FILES['image']['name'];

                // Prepare POST data
                $postData = array(
                    'key' => $apiKey,
                    'image' => base64_encode(file_get_contents($image)),
                    'name' => $imageName
                );

                // Make a request to the API using cURL
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $apiUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
                $response = curl_exec($ch);
                curl_close($ch);

                // Get URL from JSON
                $result = json_decode($response, true);
                $url = $result['data']['url'];

                // Show image
                echo '<div class="mt-3">
            <a href="' . $url . '">Uploaded Image</a>
        </div>
';
            }
        }
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
