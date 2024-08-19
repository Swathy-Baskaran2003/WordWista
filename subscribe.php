<?php
// Establish a database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mail";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process subscription form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    // Insert email into subscribers table
    $sql = "INSERT INTO subscribers (email) VALUES ('$email')";
    if ($conn->query($sql) === TRUE) {
        echo "Subscribed successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer Autoloader
require 'phpmiller/src/Exception.php';
require 'phpmiller/src/PHPMailer.php';
require 'phpmiller/src/SMTP.php';

function getSubscribers() {
    // Replace with your database credentials
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "mail";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $subscribers = [];
    $sql = "SELECT email FROM subscribers";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $subscribers[] = $row["email"];
        }
    }

    $conn->close();
    return $subscribers;
}
function getWordOfTheDay() {
    $apiKey = "1380d58b8b5c33325130c0e8f340be6bc6fba6f7bb65bfc6f";
    $apiUrl = "https://api.wordnik.com/v4/words.json/wordOfTheDay?api_key=" . $apiKey;

    $response = file_get_contents($apiUrl);
    $data = json_decode($response, true);

    return $data;
}

function getPronunciation($word) {
    $apiKey = "1380d58b8b5c33325130c0e8f340be6bc6fba6f7bb65bfc6f";
    $apiUrl = "https://api.wordnik.com/v4/word.json/" . $word . "/pronunciations?useCanonical=true&typeFormat=ahd&limit=50&api_key=" . $apiKey;

    $response = file_get_contents($apiUrl);
    $data = json_decode($response, true);

    if (!empty($data)) {
        $input = $data[0]['raw'];
        $pronunciation = "[" . substr($input, 1, -1) . "]";
        return $pronunciation;
    } else {
        return "";
    }
}


function sendWordOfTheDayEmail($wordData, $recipientEmail) {
    $to = $recipientEmail;
    $subject = 'Word of the Day';
    
    $word = $wordData['word'];
    $definition = $wordData['definitions'][0]['text'];
    $pronunciation = getPronunciation($word);
    
    $message = "Today's Word of the Day:\n\nWord: $word\nDefinition: $definition\nPronunciation: $pronunciation";

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        
        // Specify main and backup SMTP servers
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        
        // SMTP username and password
        $mail->Username = 'troubleshooters44@gmail.com';
        $mail->Password = 'cgfarrpirhywbdpl';
        
        // Enable TLS encryption
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Sender and recipient
        $mail->setFrom('troubleshooters44@gmail.com');
        $mail->addAddress($to);
        
        // Set email subject and body
        $mail->Subject = $subject;
        $mail->Body = $message;

        $mail->send();
        echo 'Email sent successfully!';
    } catch (Exception $e) {
        echo 'Email sending failed: ' . $mail->ErrorInfo;
    }
}

// Get Word of the Day data
$wordData = getWordOfTheDay();
$subscribers = getSubscribers();

foreach ($subscribers as $subscriber) {
    sendWordOfTheDayEmail($wordData, $subscriber);
}



?>
