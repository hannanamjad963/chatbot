<?php
header("Content-Type: application/json");

$input = json_decode(file_get_contents("php://input"), true);
$userMessage = $input["message"] ?? "";

if (empty($userMessage)) {
    echo json_encode(["reply" => "Please enter a message."]);
    exit;
}

$apiKey = "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"; // Replace with your API key
$url = "https://api.openai.com/v1/chat/completions";

$systemPrompt = "You are a professional AI assistant for Codvion (https://codvion.site), a technology company specializing in:

• Web Development - Custom websites, e-commerce solutions, responsive designs, modern frameworks (React, Next.js, Node.js)
• App Development - Native and cross-platform mobile applications (iOS, Android, React Native, Flutter)
• SEO Optimization - Search engine optimization, keyword research, technical SEO, content optimization
• Embedded Systems - IoT solutions, microcontroller programming, Arduino, Raspberry Pi, custom hardware-software integration
• AI & Machine Learning - Machine learning models, NLP, computer vision, predictive analytics, AI integration

Communication Guidelines:
- Maintain a professional, helpful, and concise tone
- Provide clear, actionable information
- Keep responses brief (2-4 sentences) unless more detail is requested
- Use professional language without excessive casualness
- For pricing or project inquiries, politely direct users to contact Codvion directly
- Be knowledgeable about all services but avoid technical jargon unless appropriate

Represent Codvion as a reliable, innovative technology partner.";

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $apiKey"
]);

curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    "model" => "gpt-4o-mini",
    "messages" => [
        ["role" => "system", "content" => $systemPrompt],
        ["role" => "user", "content" => $userMessage]
    ],
    "temperature" => 0.7,
    "max_tokens" => 500
]));

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode(["reply" => "Service temporarily unavailable. Please try again."]);
    curl_close($ch);
    exit;
}

curl_close($ch);

$data = json_decode($response, true);
$reply = $data["choices"][0]["message"]["content"] ?? "I apologize, but I'm unable to process your request at the moment.";

echo json_encode(["reply" => $reply]);
?>
