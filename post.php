<?php
session_start();
include 'connect.php'; // Ensure connection is included

function createPost($userEmail, $content, $numericInput1, $numericInput2)
{
    global $conn;

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Retrieve user_id from email
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->bind_param("s", $userEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $userId = $user['user_id'];
    $stmt->close();

    // Create post
    $stmt = $conn->prepare("INSERT INTO posts (user_id, content, numeric_input1, numeric_input2) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isii", $userId, $content, $numericInput1, $numericInput2);
    $stmt->execute();
    $stmt->close();
}

function getPosts()
{
    global $conn;
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $query = "SELECT posts.*, users.email, users.first_name FROM posts JOIN users ON posts.user_id = users.user_id ORDER BY created_at DESC";
    $result = $conn->query($query);

    if (!$result) {
        die("Error executing query: " . $conn->error);
    }

    return $result->fetch_all(MYSQLI_ASSOC);
}

function editPost($postId, $content, $numericInput1, $numericInput2)
{
    global $conn;
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $stmt = $conn->prepare("UPDATE posts SET content = ?, numeric_input1 = ?, numeric_input2 = ? WHERE post_id = ?");
    $stmt->bind_param("siii", $content, $numericInput1, $numericInput2, $postId);
    $stmt->execute();
    $stmt->close();
}

function deletePost($postId)
{
    global $conn;
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $stmt = $conn->prepare("DELETE FROM posts WHERE post_id = ?");
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create_post'])) {
        $content = trim($_POST['content']);
        $numericInput1 = (int) $_POST['numeric_input1'];
        $numericInput2 = (int) $_POST['numeric_input2'];
        $userEmail = $_SESSION['email'];

        // Sanitize and validate input
        if (empty($content) || $numericInput1 < 0 || $numericInput2 < 0) {
            die("Invalid input.");
        }

        createPost($userEmail, $content, $numericInput1, $numericInput2);
    } elseif (isset($_POST['edit_post'])) {
        $postId = (int) $_POST['post_id'];
        $content = trim($_POST['content']);
        $numericInput1 = (int) $_POST['numeric_input1'];
        $numericInput2 = (int) $_POST['numeric_input2'];

        // Check if the post exists and if the user is the author of the post
        $stmt = $conn->prepare("SELECT users.email FROM posts JOIN users ON posts.user_id = users.user_id WHERE post_id = ?");
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $result = $stmt->get_result();
        $post = $result->fetch_assoc();
        $stmt->close();

        if ($post && $post['email'] == $_SESSION['email']) {
            // Sanitize and validate input
            if (empty($content) || $numericInput1 < 0 || $numericInput2 < 0) {
                die("Invalid input.");
            }

            editPost($postId, $content, $numericInput1, $numericInput2);
        } else {
            die("Unauthorized.");
        }
    } elseif (isset($_POST['delete_post'])) {
        $postId = (int) $_POST['post_id'];

        // Check if the post exists and if the user is the author of the post
        $stmt = $conn->prepare("SELECT users.email FROM posts JOIN users ON posts.user_id = users.user_id WHERE post_id = ?");
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $result = $stmt->get_result();
        $post = $result->fetch_assoc();
        $stmt->close();

        if ($post && $post['email'] == $_SESSION['email']) {
            deletePost($postId);
        } else {
            die("Unauthorized.");
        }
    }
    
    header("Location: home.php");
    exit();
}
?>
