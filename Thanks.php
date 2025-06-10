<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terima Kasih</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }
        
        .container {
            width: 80%;
            max-width: 600px;
            background-color: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .container:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        h1 {
            font-size: 40px;
            color:rgba(24, 127, 20, 0.41);
            margin-bottom: 20px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        p {
            font-size: 18px;
            color: #555;
            margin-bottom: 30px;
        }

        b {
            color: #2c3e50;
        }

        a {
            display: inline-block;
            padding: 12px 25px;
            background-color:rgb(18, 113, 72);
            color: white;
            font-size: 16px;
            text-decoration: none;
            border-radius: 30px;
            transition: background-color 0.3s ease;
        }

        a:hover {
            background-color:rgb(0, 0, 0);
        }


        .thank-you-message {
            animation: fadeIn 2s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

    </style>
</head>
<body>

<div class="container thank-you-message">
    <h1>Terima Kasih!</h1>
    <p><b>Terima kasih atas review yang luar biasa! Kami harap Anda akan terus berbagi pengalaman membaca Anda untuk membantu banyak pembaca lainnya!</b></p>
    <a href="Beranda.php">Kembali ke Halaman Utama</a>
</div>

</body>
</html>
