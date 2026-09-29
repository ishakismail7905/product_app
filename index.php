<?php
    include 'db.php';

    // Fetch products
    $result = $conn->query("SELECT * FROM products");

    // Default fallback rate
    $rate = 0.21;

    // Third-Party API request
    $response = @file_get_contents("https://api.exchangerate.host/latest?base=MYR&symbols=USD");

    if ($response !== false) {
        $data = json_decode($response, true);

        if (isset($data['rates']['USD'])) {
            $rate = $data['rates']['USD'];
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }

        table {
            border-collapse: collapse;
            width: 60%;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        h2 {
            margin-bottom: 20px;
        }

    </style>
</head>

<body>

<h2>Product List</h2>

<table>
    <tr>
        <th>Name</th>
        <th>Price (MYR)</th>
        <th>Price (USD)</th>
    </tr>

    <?php
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {

            $usd = $row['price'] * $rate;

            echo "<tr>

                <td>" . htmlspecialchars($row['name']) . "</td>
                <td>" . number_format($row['price'], 2) . "</td>
                <td>" . number_format($usd, 2) . "</td>
            </tr>";

        }
    } else {
        echo "<tr><td colspan='3'>No products found</td></tr>";
    }
    ?>
</table>

</body>
</html>
