<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BarangModel;

class Chat extends BaseController
{
    public function tanyaAi()
    {
        $pesanUser = $this->request->getPost('pesan');
        $apiKey = getenv('GEMINI_API_KEY') ?: env('GEMINI_API_KEY');

        // Pakai v1beta dengan model gemini-1.5-flash
        // Ini adalah kombinasi yang paling sering berhasil untuk API Key baru
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $apiKey;

        $data = [
            "contents" => [
                [
                    "parts" => [
                        ["text" => "Nama kamu adalah Ventura Assistant. Kamu membantu penyewaan alat kamping. Jawab singkat saja."]
                    ]
                ],
                [
                    "parts" => [
                        ["text" => $pesanUser]
                    ]
                ]
            ],
            "generationConfig" => [
                "temperature" => 0.7,
                "maxOutputTokens" => 200
            ]
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $result = json_decode($response, true);

        if ($httpCode !== 200) {
            $errorMsg = $result['error']['message'] ?? 'Unknown Error';
            return $this->response->setJSON(['jawaban' => "Waduh, Google bilang: " . $errorMsg]);
        }

        $jawabanAi = $result['candidates'][0]['content']['parts'][0]['text'] ?? "Lagi error dikit nih, coba tanya lagi ya!";

        return $this->response->setJSON(['jawaban' => $jawabanAi]);
    }
}
