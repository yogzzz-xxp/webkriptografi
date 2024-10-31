<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Base64 Encode/Decode</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f0f0;
        }
        .container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            box-sizing: border-box;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            background-color: #007BFF;
            color: white;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .result {
            margin-top: 20px;
            padding: 10px;
            background-color: #e9ecef;
            border-radius: 4px;
            word-wrap: break-word;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Base64 Encode/Decode</h1>

    <form method="POST">
        <div class="form-group">
            <label for="input-text">Teks untuk Encode:</label>
            <textarea name="input_text" placeholder="Masukkan teks untuk di-encode..."></textarea>
        </div>
        
        <button type="submit" name="encode">Encode ke Base64</button>

        <div class="result">
            <?php
                if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['encode'])) {
                    $inputText = $_POST['input_text'];
                    $encodedText = base64_encode($inputText);

                    echo "Hasil Encode: " . htmlspecialchars($encodedText);

                    // Simpan ke database
                    simpanKeDatabase($inputText, $encodedText, "");
                }
            ?>
        </div>

        <div class="form-group">
            <label for="encoded-text">Base64 untuk Decode:</label>
            <textarea name="encoded_text" placeholder="Masukkan Base64 untuk di-decode..."></textarea>
        </div>

        <button type="submit" name="decode">Decode dari Base64</button>

        <div class="result">
            <?php
                if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['decode'])) {
                    $encodedText = $_POST['encoded_text'];
                    $decodedText = "";

                    if (base64_decode($encodedText, true) === false) {
                        echo "String Base64 tidak valid";
                    } else {
                        $decodedText = base64_decode($encodedText);
                        echo "Hasil Decode: " . htmlspecialchars($decodedText);

                        // Simpan ke database
                        simpanKeDatabase("", $encodedText, $decodedText);
                    }
                }
            ?>
        </div>
    </form>
</div>

<?php
// Fungsi untuk menyimpan data ke database
function simpanKeDatabase($inputText, $encodedText, $decodedText) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "encrypt";

    // Membuat koneksi
    $conn = new mysqli($servername, $username, $password, $database);

    // Cek koneksi
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Siapkan dan bind
    $stmt = $conn->prepare("INSERT INTO data (input_text, encoded_text, decoded_text) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $inputText, $encodedText, $decodedText);

    // Eksekusi statement
    if ($stmt->execute()) {
        echo "<p>Data berhasil disimpan!</p>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }

    // Tutup koneksi
    $stmt->close();
    $conn->close();
}
?>

</body>
</html>
