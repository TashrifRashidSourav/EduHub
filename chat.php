<?php
session_start();
$servername = "localhost";
$username = "root"; // your database username
$password = ""; // your database password
$database = "eduhub"; // your database name
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['student_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['student_id'];
include('db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['sendMessage'])) {
        $sender_id = $user_id;
        $receiver_id = $_POST['receiver_id'];
        $message = $_POST['message'];

        $sql = "INSERT INTO chat (sender_id, receiver_id, message) VALUES ('$sender_id', '$receiver_id', '$message')";

        if ($conn->query($sql) === TRUE) {
            echo "Message sent successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        exit;
    } elseif (isset($_POST['getMessages'])) {
        $contact_id = $_POST['contact_id'];
        $sql = "SELECT chat.*, students.name AS sender_name 
                FROM chat 
                JOIN students ON chat.sender_id = students.student_id 
                WHERE (sender_id='$user_id' AND receiver_id='$contact_id') 
                OR (sender_id='$contact_id' AND receiver_id='$user_id') 
                ORDER BY timestamp ASC";
        $result = $conn->query($sql);

        $messages = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if ($row['sender_id'] == $user_id) {
                    $row['sender_name'] = 'You';
                }
                $messages[] = $row;
            }
        }

        echo json_encode($messages);
        exit;
    } elseif (isset($_POST['searchUsers'])) {
        $name = $_POST['name'];
        $sql = "SELECT student_id, name FROM students WHERE name LIKE '%$name%'";
        $result = $conn->query($sql);

        $users = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }

        echo json_encode($users);
        exit;
    } elseif (isset($_POST['getRecentContacts'])) {
        $sql = "SELECT DISTINCT CASE WHEN sender_id = '$user_id' THEN receiver_id ELSE sender_id END AS contact_id,
                students.name AS contact_name
                FROM chat
                JOIN students ON CASE WHEN sender_id = '$user_id' THEN receiver_id ELSE sender_id END = students.student_id
                WHERE sender_id = '$user_id' OR receiver_id = '$user_id'
                ORDER BY (SELECT MAX(timestamp) FROM chat WHERE (sender_id = '$user_id' AND receiver_id = contact_id) 
                OR (sender_id = contact_id AND receiver_id = '$user_id')) DESC";
        $result = $conn->query($sql);

        $contacts = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $contacts[] = $row;
            }
        }

        echo json_encode($contacts);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <title>Chat Application</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(120, 219, 255, 0.15) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        .main-content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            position: relative;
            z-index: 1;
        }

        .container {
            display: flex;
            width: 95%;
            max-width: 1400px;
            height: calc(100vh - 140px);
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 
                0 32px 64px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            position: relative;
        }

        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        }

        .sidebar {
            width: 380px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            display: flex;
            flex-direction: column;
            border-right: 1px solid rgba(0, 0, 0, 0.08);
            position: relative;
        }

        .sidebar-header {
            padding: 24px 20px 16px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .sidebar-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .sidebar-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
        }

        .sidebar-subtitle {
            font-size: 14px;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .search-bar {
            padding: 20px;
            background: rgba(248, 250, 252, 0.8);
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        }

        .search-container {
            position: relative;
        }

        .search-bar input {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: 2px solid rgba(0, 0, 0, 0.08);
            border-radius: 12px;
            font-size: 14px;
            background: white;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .search-bar input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-1px);
        }

        .search-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 16px;
        }

        .contacts {
            flex-grow: 1;
            overflow-y: auto;
            padding: 8px 0;
        }

        .contacts::-webkit-scrollbar {
            width: 6px;
        }

        .contacts::-webkit-scrollbar-track {
            background: transparent;
        }

        .contacts::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 3px;
        }

        .contact {
            padding: 16px 20px;
            cursor: pointer;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
            position: relative;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .contact:hover {
            background: linear-gradient(90deg, rgba(102, 126, 234, 0.08), transparent);
            border-left-color: rgba(102, 126, 234, 0.3);
            transform: translateX(2px);
        }

        .contact.active {
            background: linear-gradient(90deg, rgba(102, 126, 234, 0.12), transparent);
            border-left-color: #667eea;
        }

        .contact-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 16px;
            flex-shrink: 0;
        }

        .contact-info {
            flex: 1;
            min-width: 0;
        }

        .contact-name {
            font-weight: 600;
            font-size: 15px;
            color: #1e293b;
            margin-bottom: 2px;
        }

        .contact-status {
            font-size: 12px;
            color: #64748b;
        }

        .chat-box {
            flex: 1;
            background: rgba(255, 255, 255, 0.02);
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .chat-header {
            padding: 20px 24px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .chat-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 18px;
        }

        .chat-info h3 {
            font-size: 18px;
            font-weight: 600;
            color: #1e293b;
            margin: 0;
        }

        .chat-info p {
            font-size: 13px;
            color: #64748b;
            margin: 2px 0 0 0;
        }

        .messages {
            flex-grow: 1;
            padding: 24px;
            overflow-y: auto;
            background: 
                radial-gradient(circle at 10% 20%, rgba(102, 126, 234, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 90% 80%, rgba(118, 75, 162, 0.03) 0%, transparent 50%);
        }

        .messages::-webkit-scrollbar {
            width: 6px;
        }

        .messages::-webkit-scrollbar-track {
            background: transparent;
        }

        .messages::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }

        .message {
            display: flex;
            margin-bottom: 16px;
            animation: messageSlide 0.3s ease-out;
        }

        @keyframes messageSlide {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .message.sender {
            justify-content: flex-end;
        }

        .message-bubble {
            max-width: 70%;
            padding: 12px 16px;
            border-radius: 18px;
            position: relative;
            word-wrap: break-word;
        }

        .message.sender .message-bubble {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-bottom-right-radius: 6px;
        }

        .message.receiver .message-bubble {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            color: #1e293b;
            border: 1px solid rgba(0, 0, 0, 0.06);
            border-bottom-left-radius: 6px;
        }

        .sender-name {
            font-weight: 600;
            font-size: 12px;
            margin-bottom: 4px;
            opacity: 0.8;
        }

        .message-text {
            font-size: 14px;
            line-height: 1.4;
        }

        .input-box {
            display: flex;
            padding: 20px 24px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-top: 1px solid rgba(0, 0, 0, 0.08);
            gap: 12px;
            align-items: flex-end;
        }

        .input-container {
            flex: 1;
            position: relative;
        }

        .input-box input {
            width: 100%;
            padding: 14px 50px 14px 16px;
            border: 2px solid rgba(0, 0, 0, 0.08);
            border-radius: 24px;
            font-size: 14px;
            background: white;
            transition: all 0.3s ease;
            font-family: inherit;
            resize: none;
            min-height: 48px;
            max-height: 120px;
        }

        .input-box input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .send-button {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .send-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .send-button:active {
            transform: translateY(0);
        }

        .empty-chat {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            text-align: center;
            padding: 40px;
        }

        .empty-chat-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            font-size: 32px;
            color: #667eea;
        }

        .empty-chat h3 {
            font-size: 20px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 8px;
        }

        .empty-chat p {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.7);
            max-width: 300px;
        }

        @media (max-width: 768px) {
            .container {
                width: 100%;
                height: 100vh;
                border-radius: 0;
            }
            
            .sidebar {
                width: 100%;
                position: absolute;
                z-index: 10;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.open {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>
    <div class="main-content">
        <div class="container">
            <div class="sidebar">
                <div class="sidebar-header">
                    <div class="sidebar-title">Messages</div>
                    <div class="sidebar-subtitle">Stay connected with your peers</div>
                </div>
                <div class="search-bar">
                    <div class="search-container">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="searchInput" placeholder="Search users or conversations..." oninput="searchUsers()">
                    </div>
                </div>
                <div class="contacts" id="contactsList"></div>
            </div>
            
            <div class="chat-box">
                <div class="chat-header" id="chatHeader" style="display: none;">
                    <div class="chat-avatar" id="chatAvatar">?</div>
                    <div class="chat-info">
                        <h3 id="chatName">Select a contact</h3>
                        <p id="chatStatus">Online</p>
                    </div>
                </div>
                
                <div class="messages" id="messagesList">
                    <div class="empty-chat">
                        <div class="empty-chat-icon">
                            <i class="fas fa-comments"></i>
                        </div>
                        <h3>Welcome to EduHub Chat</h3>
                        <p>Select a conversation from the sidebar or search for students to start chatting</p>
                    </div>
                </div>
                
                <div class="input-box" id="inputBox" style="display: none;">
                    <div class="input-container">
                        <input type="text" id="messageInput" placeholder="Type your message...">
                    </div>
                    <button class="send-button" onclick="sendMessage()">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        let currentContactId = null;
        let currentContactName = null;

        function searchUsers() {
            const name = document.getElementById('searchInput').value;
            const formData = new FormData();
            formData.append('name', name);
            formData.append('searchUsers', true);

            fetch('chat.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(users => {
                const contactsList = document.getElementById('contactsList');
                contactsList.innerHTML = ''; // Clear previous results
                users.forEach(user => {
                    const contactDiv = document.createElement('div');
                    contactDiv.classList.add('contact');
                    contactDiv.innerHTML = `
                        <div class="contact-avatar">${user.name.charAt(0).toUpperCase()}</div>
                        <div class="contact-info">
                            <div class="contact-name">${user.name}</div>
                            <div class="contact-status">Available to chat</div>
                        </div>
                    `;
                    contactDiv.onclick = function() {
                        openChat(user.student_id, user.name);
                    };
                    contactsList.appendChild(contactDiv);
                });
            });
        }

        function openChat(receiverId, receiverName) {
            currentContactId = receiverId;
            currentContactName = receiverName;
            //created by Md Tashrif Rashid Sourav
            // Update chat header
            const chatHeader = document.getElementById('chatHeader');
            const chatAvatar = document.getElementById('chatAvatar');
            const chatName = document.getElementById('chatName');
            const inputBox = document.getElementById('inputBox');
            
            chatHeader.style.display = 'flex';
            inputBox.style.display = 'flex';
            chatAvatar.textContent = receiverName.charAt(0).toUpperCase();
            chatName.textContent = receiverName;
            
            // Update active contact
            document.querySelectorAll('.contact').forEach(contact => {
                contact.classList.remove('active');
            });
            event.currentTarget.classList.add('active');
            
            loadMessages();
        }

        function loadMessages() {
            if (currentContactId === null) return;

            const formData = new FormData();
            formData.append('contact_id', currentContactId);
            formData.append('getMessages', true);

            fetch('chat.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(messages => {
                const messagesList = document.getElementById('messagesList');
                messagesList.innerHTML = ''; // Clear previous messages
                messages.forEach(message => {
                    const messageDiv = document.createElement('div');
                    messageDiv.classList.add('message', message.sender_id == '<?php echo $user_id; ?>' ? 'sender' : 'receiver');
                    messageDiv.innerHTML = `
                        <div class="message-bubble">
                            <div class="sender-name">${message.sender_name}</div>
                            <div class="message-text">${message.message}</div>
                        </div>
                    `;
                    messagesList.appendChild(messageDiv);
                });
                messagesList.scrollTop = messagesList.scrollHeight; // Scroll to the bottom
            });
        }

        function sendMessage() {
            const messageInput = document.getElementById('messageInput');
            const message = messageInput.value.trim();
            if (message === '' || currentContactId === null) return;

            const formData = new FormData();
            formData.append('receiver_id', currentContactId);
            formData.append('message', message);
            formData.append('sendMessage', true);

            fetch('chat.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                loadMessages(); // Load messages after sending
                messageInput.value = ''; // Clear input field
            });
        }

        // Allow sending message with Enter key
        document.getElementById('messageInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });

        // Load recent contacts on page load
        document.addEventListener('DOMContentLoaded', function() {
            const formData = new FormData();
            formData.append('getRecentContacts', true);
            fetch('chat.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(contacts => {
                const contactsList = document.getElementById('contactsList');
                contacts.forEach(contact => {
                    const contactDiv = document.createElement('div');
                    contactDiv.classList.add('contact');
                    contactDiv.innerHTML = `
                        <div class="contact-avatar">${contact.contact_name.charAt(0).toUpperCase()}</div>
                        <div class="contact-info">
                            <div class="contact-name">${contact.contact_name}</div>
                            <div class="contact-status">Recent conversation</div>
                        </div>
                    `;
                    contactDiv.onclick = function() {
                        openChat(contact.contact_id, contact.contact_name);
                    };
                    contactsList.appendChild(contactDiv);
                });
            });
        });
    </script>
</body>
</html>