<?php
// Set time limit to indefinite execution 
set_time_limit(0);

// Set the ip and port we will listen on 
$address = '127.0.0.1';
$port = 8000;

// Create a TCP Stream socket
$sock = socket_create(AF_INET, SOCK_STREAM, 0);
echo 'PHP Socket Server started at ' . $address . ' ' . $port . "\n";

// Bind the socket to an address/port
socket_bind($sock, $address, $port) or die('Could not bind to address');
// Start listening for connections
socket_listen($sock);

//loop and listen
while (true) {
    /* Accept incoming requests and handle them as child processes */
    $client = socket_accept($sock);

    // Read the input from the client – 1024 bytes
    $input = socket_read($client, 5000);
    echo strlen($input) . "\n";
    $lines = explode(chr(13), $input);
    $file = '';
    foreach ($lines as $line) {
        $dline = filter_var($line, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_LOW);
        if ($dline) {
            $file .= $dline . PHP_EOL;
            //echo $dline. "\n";
        }
    }
    //echo ord($input). "\n";
    //echo ord(substr($input, -1)). "\n";
    file_put_contents('C:\\messages\\new\\' . uniqid('test', true) . '.txt', $file);
}

// Close the client (child) socket 
socket_close($client);

// Close the master sockets 
socket_close($sock);
