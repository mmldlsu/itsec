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
            .container {
                display: flex;
                flex-direction: column;
                align-items: center;
                padding: 20px;
                width: 100%;
                max-width: 1200px;
                margin: 0 auto;
                overflow-y: auto;
            }

            .notifications {
                background: var(--btn-info);
                text-align: center;
                padding: 20px;
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
                border-radius: 10px;
                max-width: 100%;
                width: 100%;
                margin-bottom: 20px;
            }

            .post-form,
            .post {
                background: var(--btn-info);
                text-align: center;
                padding: 20px;
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
                border-radius: 10px;
                width: 100%;
                max-width: 800px;
                margin-bottom: 20px;
                position: relative;
            }

            .post-form textarea,
            .post-form input {
                border-radius: 5px;
                padding: 10px;
                margin-bottom: 10px;
                border: 1px solid #131827;
                background-color: #fff;
                width: calc(100% - 22px);
                color: #131827;
            }

            .post-form button {
                border: none;
                background: #131827;
                color: #fff;
                padding: 10px;
                border-radius: 5px;
                cursor: pointer;
                font-size: 1rem;
                width: 100%;
                transition: background-color 0.2s;
            }

            .post-form button:hover {
                background: #0d1a2b;
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

            .post form textarea,
            .post form input {
                width: 100%;
                margin-bottom: 10px;
                border-radius: 5px;
                padding: 10px;
                border: 1px solid #131827;
                background-color: #fff;
                color: #131827;
            }

            .button-container {
                display: flex;
                gap: 10px;
                align-items: center;
                justify-content: center;
            }

            button.edit-btn {
                border: none;
                background: #131827;
                color: #fff;
                padding: 8px 12px;
                border-radius: 5px;
                cursor: pointer;
                font-size: 0.9rem;
                transition: background-color 0.2s;
                height: 40px;
                display: flex;
                align-items: center;
                text-align: center;
            }

            button.edit-btn:hover {
                background: #0d1a2b;
            }

            form.delete-form {
                margin: 0;
                display: flex;
                align-items: center;
            }

            /* Modal styles */
            .modal {
                display: none;
                position: fixed;
                z-index: 1000;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: auto;
                background-color: rgb(0, 0, 0);
                background-color: rgba(0, 0, 0, 0.4);
            }

            /* Modal content styling */
            .modal-content {
                background-color: #fefefe;
                margin: 15% auto;
                padding: 20px;
                border: 1px solid #888;
                width: 80%;
                max-width: 600px;
                border-radius: 10px;
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
            }

            .modal-content input,
            .modal-content textarea {
                width: 100%;
                border-radius: 5px;
                padding: 10px;
                margin: 5px 0;
                border: 1px solid #131827;
                background-color: #fff;
                color: #131827;
                width: calc(100% - 22px);
                box-sizing: border-box;
            }

            .modal-content button {
                border: none;
                background: #131827;
                color: #fff;
                padding: 10px;
                border-radius: 5px;
                cursor: pointer;
                font-size: 1rem;
                width: 100%;
                transition: background-color 0.2s;
                margin-top: 10px;
            }

            .modal-content button:hover {
                background: #0d1a2b;
            }
            .img-thumbnail {
                width: 200px;
                height: 200px;
                border-radius: 50%;
                object-fit: cover;
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
                <p><b>Hello, <?= htmlspecialchars($_SESSION['first_name'], ENT_QUOTES, 'UTF-8') ?></b></p>
                <img src="<?= htmlspecialchars($_SESSION['profile_image'], ENT_QUOTES, 'UTF-8') ?>" class="img-thumbnail">
            </div>
            <div class="post-form">
                <form method="POST" action="post.php">
                    <input type="text" name="content" required placeholder="Write something...">
                    <input type="number" name="numeric_input1" required placeholder="Favorite Number">
                    <input type="number" name="numeric_input2" required placeholder="Age">
                    <button type="submit" name="create_post">Post</button>
                </form>
            </div>
            <?php
            $posts = getPosts();
            $currentUserEmail = htmlspecialchars($_SESSION['email'], ENT_QUOTES, 'UTF-8'); // Current user's email
        
            foreach ($posts as $post) {
                // Check if the current user's email matches the post's author email
                $canEdit = ($post['email'] == $currentUserEmail);
                $editFormId = "edit-form-{$post['post_id']}";

                echo "<div class='post'>";
                echo "<p><b>" . htmlspecialchars($post['first_name'], ENT_QUOTES, 'UTF-8') . "</b>: " . htmlspecialchars($post['content'], ENT_QUOTES, 'UTF-8') . "</p>";
                echo "<p>Favorite Number: " . htmlspecialchars($post['numeric_input1'], ENT_QUOTES, 'UTF-8') . "</p>";
                echo "<p>Age: " . htmlspecialchars($post['numeric_input2'], ENT_QUOTES, 'UTF-8') . "</p>";

                if ($canEdit) {
                    echo "<div class='button-container'>";
                    echo "<button class='edit-btn' onclick=\"openModal('$editFormId')\">Edit</button>";
                    echo "<form method='POST' action='post.php' class='delete-form'>
                            <input type='hidden' name='post_id' value='" . htmlspecialchars($post['post_id'], ENT_QUOTES, 'UTF-8') . "'>
                            <button type='submit' name='delete_post' class='edit-btn'>Delete</button>
                          </form>";
                    echo "</div>";
        
                    echo "<div id='$editFormId' class='modal'>";
                    echo "<div class='modal-content'>";
                    echo "<span class='modal-close' onclick=\"closeModal('$editFormId')\">&times;</span>";
                    echo "<form method='POST' action='post.php'>";
                    echo "<input type='text' name='content' value='" . htmlspecialchars($post['content'], ENT_QUOTES, 'UTF-8') . "'>";
                    echo "<input type='number' name='numeric_input1' value='" . htmlspecialchars($post['numeric_input1'], ENT_QUOTES, 'UTF-8') . "' required>";
                    echo "<input type='number' name='numeric_input2' value='" . htmlspecialchars($post['numeric_input2'], ENT_QUOTES, 'UTF-8') . "' required>";
                    echo "<input type='hidden' name='post_id' value='" . htmlspecialchars($post['post_id'], ENT_QUOTES, 'UTF-8') . "'>";
                    echo "<button type='submit' name='edit_post'>Save</button>";
                    echo "</form>";
                    echo "</div>";
                    echo "</div>";
                }
                echo "</div>";
            }
            ?>
        </main>
        <script>
            function openModal(modalId) {
                document.getElementById(modalId).style.display = "block";
            }

            function closeModal(modalId) {
                document.getElementById(modalId).style.display = "none";
            }

            window.onclick = function (event) {
                if (event.target.classList.contains('modal')) {
                    event.target.style.display = "none";
                }
            }
        </script>
    </body>

    </html>
    <?php
} else {
    header("Location: index.php");
    exit();
}
?>