<?php
    session_start();
    include '../connect.php';
    if(isset($_SESSION['username'])) {
?>
<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <style>
        .notifications {
            flex: 1 1 auto;
            background: var(--btn-info);
            text-align: center;
            height: fit-content;
            /* Centered Text */
            padding: 20px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
            border-radius: 26px;
            border-width: 100px;
            min-width: 220px;
            min-height: 500px;
            max-width: 1000px;
            margin: 0 auto;
        }

        .notifications ul{
            margin-bottom: 1vh;
            padding-left: 0;
            list-style:circle;
            text-align: start;
            padding-left: 3vw;
            padding-right: 2vw;
        }
        .notifications li{
            line-break: normal;
            word-wrap: break-word;
            margin-bottom: 2vh;
            text-decoration: none;

        }
        .notifications li:hover{
            background:var(--btn-primary-hover);
        }
    </style>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php include 'navbar.php' ?>
    <main>
        <div class="notifications">
            <h2>Home</h2>
            Home Page
        </div>
    </main>
</body>
</html>
<?php
        
    }
    else {
        header("Location: index.php");
        exit();
    }
?>