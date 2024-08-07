<?php
if (!session_status()) {
    session_start();
    include 'connect.php';
}

include 'post.php';

if (isset($_SESSION['email'])) {
?>
<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <style>
        /* Existing styles... */
        /* Add or update the following CSS styles */
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            width: 100%;
            max-width: 1200px; /* Adjust based on your needs */
            margin: 0 auto;
            overflow-y: auto; /* Enables vertical scrolling */
        }

        .notifications {
            background: var(--btn-info);
            text-align: center;
            padding: 20px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
            border-radius: 10px; /* Rounded corners */
            max-width: 100%;
            width: 100%;
            margin-bottom: 20px;
        }

        .post-form, .post {
    background: var(--btn-info);
    text-align: center;
    padding: 20px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
    border-radius: 10px; /* Rounded corners */
    width: 100%;
    max-width: 800px; /* Adjust based on your needs */
    margin-bottom: 20px;
    position: relative;
}

.post-form textarea, .post-form input {
    border-radius: 5px; /* Rounded corners for inputs */
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #131827; /* Border color */
    background-color: #fff;
    width: calc(100% - 22px); /* Adjust width to fit padding and border */
    color: #131827; /* Text color */
}

.post-form button {
    border: none;
    background: #131827; /* Button background color */
    color: #fff;
    padding: 10px;
    border-radius: 5px; /* Rounded corners for buttons */
    cursor: pointer;
    font-size: 1rem;
    width: 100%;
    transition: background-color 0.2s;
}

.post-form button:hover {
    background: #0d1a2b; /* Darker shade for hover */
}

.post {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: left;
    margin-bottom: 10px;
}

.post form {
    width: 100%;
    margin-top: 10px;
}

.post form textarea, .post form input {
    width: 100%;
    margin-bottom: 10px;
    border-radius: 5px; /* Rounded corners for inputs */
    padding: 10px;
    border: 1px solid #131827; /* Border color */
    background-color: #fff;
    color: #131827; /* Text color */
}

.edit-form {
    display: none; /* Hidden by default */
    position: absolute; /* Position absolutely inside .post */
    top: 10px;
    left: 0;
    right: 0;
    background: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 10px; /* Rounded corners */
    padding: 15px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
    z-index: 10; /* Ensure it sits above other content */
}

.edit-form.show {
    display: block; /* Show when editing */
}

.edit-form textarea, .edit-form input {
    border-radius: 5px; /* Rounded corners for inputs */
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #131827; /* Border color */
    background-color: #fff;
    width: calc(100% - 22px); /* Adjust width to fit padding and border */
    color: #131827; /* Text color */
}

.edit-form button {
    border: none;
    background: #131827; /* Button background color */
    color: #fff;
    padding: 10px;
    border-radius: 5px; /* Rounded corners for buttons */
    cursor: pointer;
    font-size: 1rem;
    width: 100%;
    transition: background-color 0.2s;
}

.edit-form button:hover {
    background: #0d1a2b; /* Darker shade for hover */
}

button.edit-btn {
    border: none;
    background: #131827; /* Button background color */
    color: #fff;
    padding: 8px;
    border-radius: 5px; /* Rounded corners */
    cursor: pointer;
    font-size: 0.9rem;
    transition: background-color 0.2s;
}

button.edit-btn:hover {
    background: #0d1a2b; /* Darker shade for hover */
}

    </style>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <main class="container">
        <div class="notifications">
            <h2>Home Page</h2>
            <p><b>Hello, <?= htmlspecialchars($_SESSION['first_name']) ?></b></p>
            <img src="<?= htmlspecialchars($_SESSION['profile_image']) ?>" class="img-thumbnail">
        </div>
        <div class="post-form">
            <form method="POST" action="post.php">
                <input type="text" name="content" required placeholder="Write something...">
                <input type="number" name="numeric_input1" required placeholder="Numeric Input 1">
                <input type="number" name="numeric_input2" required placeholder="Numeric Input 2">
                <button type="submit" name="create_post">Post</button>
            </form>
        </div>
        <?php
            $posts = getPosts();
            $currentUserEmail = $_SESSION['email']; // Current user's email

            foreach ($posts as $post) {
                // Check if the current user's email matches the post's author email
                $canEdit = ($post['email'] == $currentUserEmail);
                $editFormId = "edit-form-{$post['post_id']}";

                echo "<div class='post'>";
                echo "<p><b>{$post['first_name']}</b>: {$post['content']}</p>";
                echo "<p>Number 1: {$post['numeric_input1']}</p>";
                echo "<p>Number 2: {$post['numeric_input2']}</p>";

                if ($canEdit) {
                    // Edit button
                    echo "<button class='edit-btn' onclick=\"document.getElementById('$editFormId').classList.toggle('show')\">Edit</button>";

                    // Edit form
                    echo "<div id='$editFormId' class='edit-form'>";
                    echo "<form method='POST' action='post.php'>";
                    echo "<input type='text' name='content' value='{$post['content']}'>";
                    echo "<input type='number' name='numeric_input1' value='{$post['numeric_input1']}' required>";
                    echo "<input type='number' name='numeric_input2' value='{$post['numeric_input2']}' required>";
                    echo "<input type='hidden' name='post_id' value='{$post['post_id']}'>";
                    echo "<button type='submit' name='edit_post'>Save</button>";
                    echo "</form>";
                    echo "</div>";
                }
                echo "</div>";
            }
        ?>
    </main>
</body>
</html>
<?php
} else {
    header("Location: index.php");
    exit();
}
