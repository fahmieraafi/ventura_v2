public function tanya()
{
$pesanUser = $this->request->getPost('pesan');
$apiKey = env('GEMINI_API_KEY');

// Kita kasih 'kepribadian' ke AI-nya lewat system instruction
$prompt = [
"contents" => [
[
"parts" => [
["text" => "Kamu adalah asisten ahli kamping dari aplikasi Ventura. Tugasmu menjawab pertanyaan tentang gunung di Indonesia dan perlengkapan kamping dengan ramah."]
],
"role" => "model"
],
[
"parts" => [
["text" => $pesanUser]
],
"role" => "user"
]
]
];

// Kirim ke Google API pakai cURL atau Library HTTP
// ... proses kirim dan ambil balasan ...
}