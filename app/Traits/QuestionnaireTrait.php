<?php

namespace App\Traits;

use App\Models\Questionnaire;
use Illuminate\Support\Facades\DB;

trait QuestionnaireTrait
{
    protected function convertToAlphabet(string $type, $answers)
    {
        $result = Questionnaire::where('type', $type)->orderBy('order')->get();
        // return $result;

        $alphabetAnswers = [];
        foreach ($answers as $key => $item) {
            foreach ($result[$key]->answers as $alpha => $text) {
                if (strtolower($text['text']) == strtolower($item)) {
                    $alphabetAnswers[] = $alpha;
                }
            }
        }

        return $alphabetAnswers;
    }

    protected function firstInsecureTest(array $jawaban)
    {
        $ans = DB::table('ai_generated')->where('key', implode('', $jawaban))->first();

        if ($ans->answer == null) {
            $pertanyaan = 'Bagian 1: Kekuatan Super (Strength-Spotter) 
                            N
                            o Pertanyaan Opsi Jawaban (Pilih yang paling mendekati) 
                            1 Di waktu luang, aku paling 
                            suka... A. Membaca/menonton hal baru yang menarik 
                            (Pembelajar);B. Menyendiri untuk menggambar/menulis/bermain 
                            musik (Kreator);C. Ngobrol atau berkumpul dengan teman (Sosialis);D. Merapikan kamar atau merencanakan jadwal 
                            (Perencana) 
                            2 Ketika menghadapi masalah 
                            yang sulit, aku...A. Mencari banyak referensi dan tutorial (Pemecah 
                            Masalah);B. Memikirkan solusi yang tidak biasa (Inovator);C. Langsung menelepon teman untuk minta saran 
                            (Kolaborator);D. Membuat daftar langkah-langkah untuk 
                            menyelesaikannya (Strategis) 
                            3 Aku merasa paling bangga 
                            jika...A. Berhasil memahami konsep yang rumit (Pemikir 
                            Mendalam);B. Karyaku (gambar, tulisan, proyek) dipuji orang 
                            (Seniman);C. Aku bisa membantu teman yang kesusahan 
                            (Penolong) ;D. Semua tugas terselesaikan tepat waktu dengan 
                            rapi (Teliti) 
                            4 Dalam kerja kelompok, peran 
                            alamiku adalah...A. Si pencari fakta dan data (Peneliti);B. Si pencetus ide gila (Idea Generator);C. Si perekat tim yang menjaga suasana (Mediator);D. Si pengatur waktu dan pembagi tugas (Manajer 
                            Proyek) 
                            5 Kekuatan terbesarku adalah... A. Rasa ingin tahuku yang tinggi (Curiosity);B. Imajinasiku yang liar (Imagination);C. Kemampuanku memahami perasaan orang;D. Konsistensi dan ketekunanku (Perseverance) ';
            $carahitung = '"Kode Jawaban","Nama","""Kekuatan Super""","Deskripsi & Penyampaian Hasil (Untuk Siswa)"
                            "A.","Pembelajar/Pemikir","The Sage (Sang Bijak)","Kekuatan Supermu adalah Rasa Ingin Tahu! Otakmu adalah senjatanya. Kamu adalah pembelajar sejati yang bersemangat saat menemukan hal baru dan memahami bagaimana sesuatu bekerja. Dunia need people like you!"
                            "B.","Kreator/Inovator","The Artisan (Sang Perajin)","Kekuatan Supermu adalah Kreativitas! Kamu punya kemampuan untuk melihat dunia dengan cara yang berbeda dan menciptakan sesuatu dari imajinasimu. Ide-idemu adalah sumber dayamu yang berharga."
                            "C.","Sosialis/Kolaborator","The Guardian (Sang Penjaga)","Kekuatan Supermu adalah Empati dan Kerja Sama! Kamu adalah ahli dalam memahami perasaan orang dan menyatukan tim. Kehangatan dan dukunganmu membuat orang lain merasa kuat."
                            "D.","Perencana/Strategis","The Architect (Sang Arsitek)","Kekuatan Supermu adalah Ketelitian dan Organisasi! Kamu hebat dalam merencanakan, menyusun strategi, dan memastikan semuanya berjalan lancar. Kamu adalah orang yang membuat rencana besar menjadi kenyataan."';
            $jawab = json_encode($jawaban);
            $perintah = "saya punya daftar pertanyaan ini $pertanyaan. dengan cara penghitungan $carahitung. Hasilkan jenis kepribadian siswa dengan jawaban no. 1-5 $jawab ini berdasarkan cara hitung tersebut. output dalam bentuk JSON (parsed) berikut (hanya contoh, berikan berdasarkan analisamu sendiri) json````main_archtype : 'nama persona utama yang paling dominan',secondary_archtype : 'nama persona kedua yang melengkapi'||null,archtype_character : 'tipe karakter persona. contoh : Mental Baja hanya satu tipe'
    archtype_habits : 'kebiasaan persona contoh: Kebebasan dan Kreasi hanya 2 tipe dengan dan bukan &',archtype_description : 'deskripsi persona',archtype_power : 'kekuartan persona contoh: Rasa ingin tahu yang tak terkalahkan berbentuk deskriptif maksimal 8 kata'``` tanpa tambahan apapun dengan bahasa antara teman-teman bagi siswa SMA namun jangan terlalu panjang";

            $hasil = $this->sendGemini($perintah, 600);
            $hasil = str_replace('```', '', $hasil);
            $hasil = str_replace('json', '', $hasil);
            DB::table('ai_generated')->where('key', implode('', $jawaban))->update(['answer' => $hasil]);
        } else {
            $hasil = $ans->answer;
        }
        $hasil = json_decode($hasil);

        return $hasil;
    }

    public function secondInsecureTest(array $jawaban)
    {
        $hitungan = array_count_values($jawaban);
        $jumlah = [
            'A' => $hitungan['A'] ?? 0, // Auditori
            'B' => $hitungan['B'] ?? 0, // Visual
            'C' => $hitungan['C'] ?? 0, // Kinestetik
        ];
        $maksimum = max($jumlah);
        $peta_gaya = [
            'A' => 'Auditori',
            'B' => 'Visual',
            'C' => 'Kinestetik',
        ];
        $peta_deskripsi = [
            'A' => 'Gunakan kekuatanmu! Rekam penjelasan guru, bacakan catatanmu dengan suara lantang, diskusikan materi dengan teman, dan dengankan podcast edukasi.',
            'B' => 'Jadikan catatanmu berwarna! Gunakan stabilo, buat diagram, mind map, grafik, atau tonton video penjelasan animasi untuk membantumu memahami. ',
            'C' => 'Jangan hanya duduk! Belajar sambil berjalan, gunakan benda nyata untuk simulasi (contoh: buah untuk matematika), atau praktikkan langsung konsep yang dipelajari. ',
        ];
        foreach ($jumlah as $kode => $count) {
            if ($count === $maksimum) {
                $name = $peta_gaya[$kode];
                $desc = $peta_deskripsi[$kode];
            }
        }

        return ['mode' => $name, 'style' => $desc];
    }

    public function thirdInsecureTest(array $jawaban)
    {
        $ans = DB::table('ai_generated')->where('key', implode('', $jawaban))->first();

        if ($ans->answer == null) {
            $sembilan = [
                'B' => 'Kamu punya motivasi internal yang sangat kuat! Ini keren 
sekali karena bahan bakarmu datang dari dalam dirimu sendiri. Terus pertahankan 
rasa ingin tahu itu.',
                'D' => 'Kamu punya motivasi internal yang sangat kuat! Ini keren 
sekali karena bahan bakarmu datang dari dalam dirimu sendiri. Terus pertahankan 
rasa ingin tahu itu.',
                'A' => 'Kamu termotivasi oleh tujuan dan pengakuan eksternal. 
Tidak ada salahnya, tapi coba latih juga untuk menemukan kesenangan dalam 
proses belajarnya sendiri, bukan hanya hasilnya.',
                'C' => 'Kamu termotivasi oleh tujuan dan pengakuan eksternal. 
Tidak ada salahnya, tapi coba latih juga untuk menemukan kesenangan dalam 
proses belajarnya sendiri, bukan hanya hasilnya.',
            ];
            $sepuluh = [
                'A' => 'Luar biasa! Kamu memiliki Mental Baja. 
Kegagalan bagimu bukanlah akhir, tapi batu loncatan. Ini adalah kekuatan super 
terhebat yang bisa dimiliki seorang pelajar. ',
                'B' => 'Wajar sekali kadang merasa demikian. Ingat, 
tantangan adalah kesempatan untuk tumbuh. Coba ubah pertanyaannya dari 
"Mengapa aku gagal?" menjadi "Apa yang bisa pelajari dari ini?". 
',
                'C' => 'Luar biasa! Kamu memiliki Mental Baja. 
Kegagalan bagimu bukanlah akhir, tapi batu loncatan. Ini adalah kekuatan super 
terhebat yang bisa dimiliki seorang pelajar. ',
                'D' => 'Wajar sekali kadang merasa demikian. Ingat, 
tantangan adalah kesempatan untuk tumbuh. Coba ubah pertanyaannya dari 
"Mengapa aku gagal?" menjadi "Apa yang bisa pelajari dari ini?". 
',
            ];
            $ds = $sembilan[$jawaban[0]];
            $da = $sepuluh[$jawaban[1]];
            $hasil = $this->sendGemini("Kombinasikan dua motivasi ini jadi 1 pertama: $ds. kedua: $da. tujukan bagi siswa SMA. Cukup singkat text tanpa formatting apapun. Ini akan menjadi bagian Bahan Bakar siswa (yang mengapresiasi dengan ucapkah terimakasih dan hebat, contoh: kamu punya kombinasi bahan bakar internal dan resilience yang kuat) maksimal 10 kata", 50);

            $hasil = ['text' => $hasil];
            DB::table('ai_generated')->where('key', implode('', $jawaban))->update(['answer' => $hasil]);
            $hasil = $hasil['text'];
        } else {
            $hasil = $ans->answer;
            $hasil = json_decode($hasil)->text;
        }

        return $hasil;
    }

    protected function missionInsecureTest($payload)
    {
        $payload = json_encode($payload);
        $hasil = $this->sendGemini("Jadikan menjadi 2 misi mingguan bagi siswa berdasarkan persona ini $payload. outputkan dalam bentuk json```{first:{title:string,text:string},second:{title:string,text:string}}```. tiap misi maksimal 3 kalimat yang tiap kalimat maksimal 20 kata", 250);
        $hasil = str_replace('```', '', $hasil);
        $hasil = str_replace('json', '', $hasil);
        $hasil = json_decode($hasil);

        return $hasil;
    }
}
