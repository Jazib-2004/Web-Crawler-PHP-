<?php

// Search variables
$searchString = isset($_POST['searchString']) ? $_POST['searchString'] : '';
$searchResult = array();

// Check if the form is already submitted and a JSON file exists
$filename = 'crawler_output.json';
if (file_exists($filename)) {
    // Read the existing JSON file
    $storedData = json_decode(file_get_contents($filename), true);

    if (!empty($searchString)) {
        // Function to search for a string in nested arrays
        function recursive_array_search($needle, $haystack) {
            foreach ($haystack as $key => $value) {
                $currentKey = $key;
                if ($needle === $value || (is_array($value) && recursive_array_search($needle, $value) !== false)) {
                    return $currentKey;
                }
            }
            return false;
        }

        // Perform the search
        $searchResultKey = recursive_array_search($searchString, $storedData);
        if ($searchResultKey !== false) {
            $searchResult = array($searchResultKey => $storedData[$searchResultKey]);
        } else {
            $searchResult = array('error' => 'String not found in the stored data.');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Crawler-Search</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 8px;
        }

        label {
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"] {
            padding: 8px;
            margin-bottom: 16px;
            width: 100%;
            box-sizing: border-box;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        button:hover {
            background-color: #0056b3;
        }

        h2 {
            margin-top: 20px;
        }

        pre {
            background-color: #f5f5f5;
            padding: 20px;
            border-radius: 8px;
            overflow-x: auto;
        }
    </style>
</head>

<body>

    <h1>Search in stored file</h1>
    <form method="post" action="">
        <label for="searchString">Enter Search String:</label>
        <input type="text" name="searchString" id="searchString" required>
        <button type="submit">Search</button>
    </form>

    <?php if (!empty($searchResult)) : ?>
        <h2>Search Result:</h2>
        <pre><?= json_encode($searchResult, JSON_PRETTY_PRINT) ?></pre>
    <?php endif; ?>

</body>

</html>
