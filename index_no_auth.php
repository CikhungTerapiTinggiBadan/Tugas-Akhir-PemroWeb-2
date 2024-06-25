<?php
session_start();
include 'connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Fetch user's profile details
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT first_name, last_name FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Process search
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT name, amount FROM leaderboard";

if (!empty($search)) {
    $sql .= " WHERE name LIKE '%$search%'";
}

$sql .= " ORDER BY amount DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZiQiHalal</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background: url('klik.png') no-repeat center center fixed;
            background-size: cover;
            color: #333;
        }
        .header {
            background-color: #ffffff;
            padding: 20px 10%;
            border-bottom: 1px solid #ddd;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header .logo {
            float: left;
        }
        .header .logo img {
            width: 90px;
        }
        .header .search {
            float: left;
            margin-left: 20px;
            position: relative;
        }
        .header .search input {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 20px;
            width: 250px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header .nav {
            float: right;
        }
        .header .nav ul {
            list-style: none;
            margin: 12px;
            padding: 0;
            display: flex;
            align-items: center;
        }
        .header .nav li {
            margin-left: 20px;
        }
        .header .nav li a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
            padding: 8px 12px;
            transition: color 0.3s, background-color 0.3s;
            border-radius: 20px;
        }
        .header .nav li a:hover {
            color: #fff;
            background-color: #007bff;
        }
        .leaderboard-container {
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            width: 80%;
        }
        .leaderboard-container table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 16px;
        }
        .leaderboard-container th, .leaderboard-container td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .leaderboard-container th {
            background-color: #007bff;
            color: white;
        }
        .leaderboard-container tr:nth-child(even) {
            background-color: #f4f4f9;
        }
        .leaderboard-container tr:hover {
            background-color: #f1f1f1;
        }
        .leaderboard-container td {
            color: #555;
        }
        .leaderboard-container td:first-child {
            font-weight: bold;
        }
        .add-button {
            position: fixed;
            bottom: 0;
            display: block;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            border: none;
            width: 100%;
        }
        .add-button:hover {
            background-color: #218838;
        }
        .lorem-container {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
            margin-left: 50px;
            margin-right: 50px;
            margin-bottom: 50px;
            gap: 20px;
        }
        .lorem-container .column {
            width: 48%;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .lorem-container .column img {
            display: block;
            margin: 0 auto;
            max-width: 100%;
            height: auto; 
            border-radius: 10px;
        }
        .botbox {
            background-color: white;
            position: relative;
            height: 490px;
            width: 100%;
            bottom: 0;
        }     
        .bottext {
            margin-top: 0px;
            margin-left: 50px;
            margin-right: 80px;
            display: flex;
            justify-content: center;
            align-items: center; 
        }
        .botimg {
            margin-top: 20px;
            margin-left: 20px;
            margin-right: 80px;
            display: flex;
            justify-content: center;
            align-items: center; 
        }
        #profile-box {
            position: absolute;
            top: 50px; /* Adjust as needed based on your header height */
            left: 80%;
            transform: translateX(-50%);
            background-color: #fff;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            z-index: 999; /* Ensure it appears above other content */
            display: none; /* Initially hidden */
        }
    </style>
    <script>
        function toggleProfile() {
            var profileBox = document.getElementById('profile-box');
            if (profileBox.style.display === 'block') {
                profileBox.style.display = 'none';
            } else {
                showProfile();
            }
        }

        function showProfile() {
            var firstName = "<?php echo $user['first_name']; ?>";
            var lastName = "<?php echo $user['last_name']; ?>";

            var profileBox = document.getElementById('profile-box');
            if (profileBox) {
                profileBox.innerHTML = "Nama: " + firstName + " " + lastName;
                profileBox.style.display = "block";
            }
        }
    </script>
</head>
<body>
    <header class="header">
        <div class="logo">
            <img src="halal.png" alt="NU Care-LazisNU Logo">
        </div>
        <div class="search">
            <form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="text" name="search" placeholder="Cari Nama di Leaderboard">
            </form>
        </div>
        <nav class="nav">
            <ul>
                <li><a href="#">Campaign</a></li>
                <li><a href="#">Berita</a></li>
                <li><a href="#">Layanan</a></li>
                <li><a href="javascript:void(0);" onclick="toggleProfile();">Profil</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <!-- Kotak untuk menampilkan profil -->
    <div id="profile-box">
        <!-- Nama pengguna akan ditampilkan di sini -->
    </div>

    <div class="leaderboard-container">
        <h2>Papan Sedekah</h2>
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr><td>" . $row["name"]. "</td><td>USD " . $row["amount"]. ".00</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>Tidak ada rekaman ditemukan</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="lorem-container">
        <div class="column">
        <p>Assalamualaikum Warahmatullahi Wabarokatuh.<br><br>
Dalam Surat Al-Ahzab ayat 70, Allah SWT qola: ya ayyuhal lazina amanu, ya ayyuhal lazina amanu ittaqullah, wa qulu qaulan sadida. Hei orang-orang yang beriman, bertakwalah kamu sekalian kepada Allah dan katakanlah perkataan yang benar. Wa qulu qaulan sadida, katakanlah oleh kamu sekalian qaulan sadida, perkataan yang benar.Jadi perintah Allah SWT agar kita bertakwa dan berkata benar. Berkata benar itu artinya jangan dusta, jangan bohong, jangan menipu, jangan ngibul, harus betul-betul berkata yang jujur, berkata yang benar, tidak boleh kita berdusta ataupun berbohong.
<br><br>Jangankan dalam urusan besar dan serius, dalam urusan kecil hingga gurauan, candaan juga dilarang oleh Rasulullah SAW untuk berbohong. Rasulullah SAW pernah pernah bersabda sebagaimana diriwayatkan oleh Imam Ahmad dalam kitab Musnad-nya dan juga diriwayatkan oleh para perawi yang lainnya. Bahwasanya beliau pernah mengatakan, au kama qolannabiyyu shallallahu alaihi wassallam, la yu'minul abdu al-imana kullahu hatta yatrukal kaziba fil muzaha. Jadi Nabi mengatakan tidak sempurna iman seorang hamba sehingga dia tinggalkan bohong dalam canda atau gurauan.
Jadi baru dikatakan iman seseorang hamba itu bisa sempurna kalau dia sudah bisa melakukan tidak berbohong walaupun dalam candaan dan gurauan, subhanallah.
<br><br>Ahibbail kiram, bergurau dan bercanda saja tidak boleh berbohong, jadi bergurau dan bercanda saja tidak boleh berbohong, apalagi dalam mengurus umat, bangsa, dan negara. Jadi, sekali lagi kita ingatkan, berdasarkan amanat dari Allah dan Rasulnya, jangan bohong, sekali lagi jangan bohong, sekali lagi jangan bohong. Inilah yang ingin kami sampaikan untuk segenap umat Islam yang hadir pada acara Reuni Akbar Mujahid ini, bahkan untuk seluruh rakyat Indonesia yang ikut berpartisipasi menghadiri acara ini.</p>
            <img src="yateam.jpg" >
        </div>
        <div class="column">
            <img src="afrika.jpg">
            <p>
<br>Sengaja khotbah tersebut diputar kembali oleh panitia reuni ini untuk mengingatkan kita semua bahwa aksi lahir dari pertarungan ideologi. Pertarungan ideologi antara akidah tentang ayat suci di atas ayat konstitusi, melawan propaganda tentang ayat konstitusi di atas ayat suci.
<br><br>Jadi, dari Aksi yang pernah digelar pada tahun 2016, tidak lain dan tidak bukan aksi tersebut lahir dari pertarungan ideologi, yaitu antara pertarungan akidah dan propaganda. Ayat suci di atas ayat konstitusi adalah akidah yang tinggi lagi mulia. Sedang kebalikannya, ayat konstitusi di atas ayat suci adalah propaganda busuk dari kalangan anti-agama.Saudaraku seiman dan seakidah, saudaraku sebangsa dan se-Tanah Air, tanamkan dalam jiwa dan sanubarimu yang paling dalam bahwa ayat suci adalah wahyu ilahi yang maha tinggi dan wajib ditaati sehingga tidak boleh direvisi, apalagi diganti. Sedang konstitusi adalah produk akal insani yang wajib tunduk kepada ayat suci karena ayat suci merupakan wahyu ilahi.
<br><br>Jadi, selama ayat konstitusi seiring dan sejalan dengan ayat suci, maka wajib kita patuhi. Namun, jika ayat konstitusi melawan dan bertentangan dengan ayat suci, konstitusi tersebut diamendemen dan diperbaiki. Direvisi dan diperbaiki, diluruskan agar senapas dan senyawa dengan ayat suci yang merupakan wahyu ilahi. Ayat konstitusi yang mana pun, ayat konstitusi yang mana pun, baik berupa Undang-Undang Dasar yang dibuat oleh MPR RI atau berupa Undang-Undang yang dibuat oleh DPR RI, ataupun berupa aturan lainnya yang dibuat oleh presiden atau para menterinya, ataupun yang dibuat oleh kepala daerah, baik tingkat I maupun tingkat II, maka wajib kita kawal dan kita jaga serta kita rawat agar tidak bertentangan dengan ayat suci. Insyaallah, kalau kita selalu mengawal, selalu menjaga, dan merawat ayat-ayat konstitusi dengan rawatan yang benar, maka ayat konstitusi akan selalu seiring, sejalan dengan ayat-ayat suci. Insyaallah.</p>
        </div>
    </div>

    <div class="botbox">
        <div class="bottext">
            <p><img class="botimg" src="HalalLogo.png"> </p>
            <a href="#" id="bottomtext">
                <p>ZiQiHalal</a> adalah sebuah lembaga zakat islam yang didirikan pada Juni tahun 2024 oleh 2 tokoh ternama bernama Ir. Dr. Drs. H. Mr. Sir. R. Ziadan Rizqitta Suryantoro S.Pd., M.Pd., S.M., M.M., S.TI., M.TI., dan 
                Shidqi Naufal. Didirikannya lembaga ini, diharapkan dapat menarik perhatian masyarakat bukan hanya Indonesia saja, bahkan hingga masyarakat Dunia. Berapapun nominal yang anda sumbangkan akan disalurkan kepada orang yang membutuhkan.
                Untuk mulai berdonasi, anda perlu masuk ke akun <a href="#" id="bottomtext">ZiQiHalal</a> dan apabila belum memiliki, anda dapat mendaftar.
            </p>
            <hr>    
        </div>
    </div>

    <form action="sedekah.php" method="GET">
        <button type="submit" class="add-button"><h1>Sedekah Sekarang</h1></button>
    </form>

    <?php $conn->close(); ?>
</body>
</html>