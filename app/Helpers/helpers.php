<?php

function loadEnv($filePath = '.env')
{
    if (! file_exists($filePath)) {
        throw new Exception('.env file not found');
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) {
            continue; // Skip comments
        }

        [$name, $value] = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);

        if (! array_key_exists($name, $_SERVER) && ! array_key_exists($name, $_ENV)) {
            putenv("$name=$value"); // Set environment variable
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

function unprocessAbleResponse(array $errors): void
{
    http_response_code(422);
    echo json_encode([
        'title' => 'Unprocessable Entity',
        'errors' => $errors,
    ], JSON_UNESCAPED_UNICODE);
}
