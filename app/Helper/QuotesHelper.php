<?php

namespace App\Helper;

class QuotesHelper
{
    protected static $quotes = [
        "secure" => [
            "Kebahagiaan bukanlah sesuatu yang sudah jadi. Itu datang dari tindakanmu sendiri. – Dalai Lama",
            "Hidup adalah 10% apa yang terjadi pada kita dan 90% bagaimana kita meresponnya. – Charles R. Swindoll",
            "Jadilah dirimu sendiri; orang lain sudah ada yang punya. – Oscar Wilde",
            "Pendidikan adalah senjata paling ampuh untuk mengubah dunia. – Nelson Mandela",
            "Hidup adalah seni menggambar tanpa penghapus. – John W. Gardner",
            "Keberanian adalah ketika kamu tahu kamu akan kalah, tapi tetap mencoba juga. – Harper Lee",
            "Percaya pada dirimu dan semua yang kamu miliki. Ketahuilah bahwa ada sesuatu di dalam dirimu yang lebih besar dari rintangan apa pun. – Christian D. Larson",
            "Setiap hari mungkin bukanlah hari yang baik, tapi selalu ada sesuatu yang baik di setiap hari. – Alice Morse Earle",
            "Kesuksesan bukanlah kunci kebahagiaan. Kebahagiaan adalah kunci kesuksesan. – Albert Schweitzer",
            "Jangan biarkan perilaku orang lain menghancurkan kedamaianmu. – Dalai Lama",
            "Jika kamu ingin bahagia, jadilah. – Leo Tolstoy",
            "Senyuman adalah bahasa universal dari kebaikan. – William Arthur Ward",
            "Rahasia untuk maju adalah memulai. – Mark Twain",
            "Kebahagiaan bergantung pada diri kita sendiri. – Aristoteles",
            "Sukses adalah perjalanan, bukan tujuan. – Arthur Ashe",
            "Hidup yang baik adalah hidup yang diinspirasi oleh cinta dan dipandu oleh pengetahuan. – Bertrand Russell",
            "Optimisme adalah keyakinan yang mengarah pada pencapaian. – Helen Keller",
            "Satu-satunya cara melakukan pekerjaan hebat adalah mencintai apa yang kamu lakukan. – Steve Jobs",
            "Kebahagiaan hanyalah nyata ketika dibagi. – Christopher McCandless",
            "Bersyukur mengubah apa yang kita miliki menjadi cukup. – Aesop",
            "Kebahagiaan bukanlah sesuatu yang kamu tunda untuk masa depan; itu adalah sesuatu yang kamu rancang untuk masa kini. – Jim Rohn",
            "Hidup itu singkat. Tersenyumlah selagi masih ada gigi. – Mallory Hopkins",
            "Waktu yang kamu nikmati saat terbuang, tidak pernah benar-benar terbuang. – John Lennon",
            "Jangan menghitung hari, buatlah hari itu berarti. – Muhammad Ali",
            "Kebahagiaan adalah arah, bukan tempat. – Sydney J. Harris",
            "Berhenti menunggu hari Jumat, musim panas, atau kebahagiaan. Kebahagiaan dicapai ketika kamu berhenti menunggu dan membuat momen sekarang menyenangkan. – Unknown",
            "Hal-hal kecil? Hal-hal kecil justru besar. – Jon Kabat-Zinn",
            "Hidup bukanlah tentang menunggu badai reda, tetapi belajar menari di tengah hujan. – Vivian Greene",
            "Senyumanmu akan memberimu wajah yang positif yang akan membuat orang merasa nyaman di sekitarmu. – Les Brown",
            "Masa depan tergantung pada apa yang kamu lakukan hari ini. – Mahatma Gandhi",
            "Pendidikan adalah paspor menuju masa depan, karena hari esok adalah milik mereka yang mempersiapkan diri hari ini. – Malcolm X",
            "Mimpi adalah benih dari kenyataan. – Napoleon Hill",
            "Belajar bukanlah kewajiban, tetapi kesempatan. – Albert Einstein",
            "Sukses bukanlah tentang seberapa tinggi kamu naik, tapi seberapa besar kamu membawa dunia lebih baik. – Roy T. Bennett",
            "Kebaikan adalah bahasa yang dapat didengar oleh tuli dan dilihat oleh buta. – Mark Twain",
            "Kehidupan adalah bunga di mana cinta adalah madunya. – Victor Hugo",
            "Setiap orang membawa senyum dengan cara mereka sendiri. – Mother Teresa",
            "Hidup bahagia dimulai dengan pikiran positif. – Unknown",
            "Senyum adalah cahaya yang keluar dari wajahmu untuk mencerahkan hati orang lain. – Unknown",
            "Satu tindakan kebaikan kecil bernilai lebih dari seribu niat besar. – Unknown",
            "Jangan biarkan kemarin mengambil terlalu banyak dari hari ini. – Will Rogers",
            "Hidup sederhana adalah seni terbesar. – Unknown",
            "Semakin kamu berbagi kebahagiaan, semakin banyak yang kembali kepadamu. – Unknown",
            "Senyuman yang tulus bisa mengubah dunia. – Unknown",
            "Bersyukurlah atas apa yang kamu miliki; kamu akan memiliki lebih banyak. – Oprah Winfrey",
            "Tantangan membuat hidup menarik; mengatasinya membuat hidup bermakna. – Joshua J. Marine",
            "Hidup adalah hadiah, jangan sia-siakan. – Unknown",
            "Setiap hari adalah kesempatan baru untuk bahagia. – Unknown",
            "Cinta dan kebaikan tidak pernah sia-sia. – Unknown"
        ],
        "insecure" => [
            "Tidak apa-apa untuk merasa tidak baik-baik saja. – Unknown",
            "Kesulitan sering mempersiapkan orang biasa untuk takdir yang luar biasa. – C.S. Lewis",
            "Dalam setiap kesulitan terdapat kesempatan. – Albert Einstein",
            "Kegelapan tidak bisa mengusir kegelapan; hanya cahaya yang bisa melakukannya. – Martin Luther King Jr.",
            "Luka adalah tempat cahaya masuk ke dalam dirimu. – Rumi",
            "Apa pun yang kamu alami sekarang, itu tidak akan selamanya. – Unknown",
            "Badai pasti berlalu. – Unknown",
            "Tidak ada malam yang begitu panjang hingga menolak pagi. – Victor Hugo",
            "Keberanian bukan berarti tidak takut, melainkan melangkah meski takut. – Nelson Mandela",
            "Saat kamu lelah, belajarlah untuk beristirahat, bukan menyerah. – Banksy",
            "Setiap orang memiliki cerita yang tidak kita ketahui. – Unknown",
            "Kesedihan adalah bagian dari kehidupan, bukan tanda kelemahan. – Unknown",
            "Kamu tidak sendirian, meskipun terasa begitu. – Unknown",
            "Hidup tidak harus sempurna untuk menjadi indah. – Annette Funicello",
            "Satu langkah kecil mungkin adalah langkah terbesar dalam hidupmu. – Unknown",
            "Hidup adalah tentang jatuh dan bangun kembali. – Nelson Mandela",
            "Jangan takut untuk mencari bantuan. – Unknown",
            "Hidupmu penting. – Unknown",
            "Kadang, menangis adalah tanda kekuatan, bukan kelemahan. – Unknown",
            "Kesedihan bukanlah akhir dari cerita. – Unknown",
            "Kamu lebih kuat dari yang kamu kira. – Unknown",
            "Harapan adalah hal dengan sayap. – Emily Dickinson",
            "Kamu berharga, bahkan saat kamu merasa tidak. – Unknown",
            "Ada pelangi setelah hujan. – Unknown",
            "Berhentilah menghukum diri sendiri atas hal-hal di luar kendali. – Unknown",
            "Setiap hari baru adalah kesempatan baru. – Unknown",
            "Kamu tidak gagal hanya karena merasa gagal. – Unknown",
            "Kesepian hanyalah perasaan, bukan kenyataan. – Unknown",
            "Kegelapan hanyalah sementara. – Unknown",
            "Tidak ada yang salah untuk meminta pertolongan. – Unknown",
            "Setiap luka akan sembuh dengan waktu. – Unknown",
            "Harapan kecil bisa menyalakan cahaya besar. – Unknown",
            "Kamu cukup, persis seperti dirimu sekarang. – Unknown",
            "Kamu bukan apa yang kamu alami, kamu adalah apa yang kamu pilih untuk menjadi. – Unknown",
            "Hidup tidak menunggu badai reda, tapi belajar menari di tengah hujan. – Vivian Greene",
            "Masa-masa sulit melahirkan orang-orang tangguh. – Unknown",
            "Bahkan di malam tergelap, bintang tetap bersinar. – J.K. Rowling",
            "Rasa sakit adalah sementara, kebanggaan adalah selamanya. – Unknown",
            "Tidak ada satu orang pun di dunia ini yang tidak berharga. – Unknown",
            "Kamu penting lebih dari yang kamu tahu. – Unknown",
            "Jangan menyerah, hal-hal indah butuh waktu. – Unknown",
            "Setiap napas adalah tanda bahwa kamu masih punya kesempatan. – Unknown",
            "Tidak ada salahnya melambat, asalkan kamu tidak berhenti. – Confucius",
            "Hari ini mungkin berat, tapi kamu sudah berhasil melewati 100% hari terburukmu. – Unknown",
            "Kamu punya hak untuk merasa, tapi jangan biarkan itu mengendalikanmu. – Unknown",
            "Kamu tidak rusak; kamu sedang belajar sembuh. – Unknown",
            "Tidak apa-apa untuk minta bantuan. – Unknown",
            "Kesulitan hari ini bisa menjadi kekuatan di masa depan. – Unknown",
            "Kamu masih di sini, dan itu adalah kemenangan. – Unknown",
            "Tetaplah bernapas, itu sudah cukup untuk saat ini. – Unknown"
        ]
    ];


    public static function get(string $status): array
    {
        return static::$quotes[$status] ?? [];
    }

    public static function random(string $status): ?string
    {
        $quotes = static::get($status);
        return $quotes ? $quotes[array_rand($quotes)] : null;
    }
}
