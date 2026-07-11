<?php

declare(strict_types=1);

use App\Controllers\PageController;
use App\Controllers\RbacController;
use App\Core\Router;

/** @var Router $router */

$router->get('/pengurusan-akses', [RbacController::class, 'index']);
$router->get('/about', [PageController::class, 'page_about']);
$router->get('/analisis-pat-t4-uasa-t1,2,3', [PageController::class, 'page_analisis_pat_t4_uasa_t1_2_3']);
$router->get('/analisis-ppt', [PageController::class, 'page_analisis_ppt']);
$router->get('/bank-soalan-uasa-ppt-pat-selaras', [PageController::class, 'page_bank_soalan_uasa_ppt_pat_selaras']);
$router->get('/bil-kelas-gambar', [PageController::class, 'page_bil_kelas_gambar']);
$router->get('/buletin-sekolah', [PageController::class, 'page_buletin_sekolah']);
$router->get('/contact', [PageController::class, 'page_contact']);
$router->post('/contact', [PageController::class, 'page_contact']);
$router->get('/courses', [PageController::class, 'page_courses']);
$router->get('/cuti-perayaan', [PageController::class, 'page_cuti_perayaan']);
$router->get('/enrolmen-murid', [PageController::class, 'page_enrolmen_murid']);
$router->get('/guru-apk', [PageController::class, 'page_guru_apk']);
$router->get('/jawatankuasa-pibg', [PageController::class, 'page_jawatankuasa_pibg']);
$router->get('/kalendar-akademik', [PageController::class, 'page_kalendar_akademik']);
$router->get('/kecemerlangan-program-akademik', [PageController::class, 'page_kecemerlangan_program_akademik']);
$router->get('/kelab-persatuan', [PageController::class, 'page_kelab_persatuan']);
$router->get('/keputusan', [PageController::class, 'page_keputusan']);
$router->get('/lencana-lagu-sekolah', [PageController::class, 'page_lencana_lagu_sekolah']);
$router->get('/misi-visi-sekolah', [PageController::class, 'page_misi_visi_sekolah']);
$router->get('/news-details', [PageController::class, 'page_news_details']);
$router->get('/news', [PageController::class, 'page_news']);
$router->get('/pelan-sekolah', [PageController::class, 'page_pelan_sekolah']);
$router->get('/pemimpin-murid', [PageController::class, 'page_pemimpin_murid']);
$router->get('/penggubal-soalan-upsa-uasa', [PageController::class, 'page_penggubal_soalan_upsa_uasa']);
$router->get('/pengurusan-tertinggi', [PageController::class, 'page_pengurusan_tertinggi']);
$router->get('/pentaksiran-peperiksaan', [PageController::class, 'page_pentaksiran_peperiksaan']);
$router->get('/peraturan-sekolah', [PageController::class, 'page_peraturan_sekolah']);
$router->get('/pilihan-mata-pelajaran', [PageController::class, 'page_pilihan_mata_pelajaran']);
$router->get('/pra-sekolah', [PageController::class, 'page_pra_sekolah']);
$router->get('/profil-sekolah', [PageController::class, 'page_profil_sekolah']);
$router->get('/pusat-sumber', [PageController::class, 'page_pusat_sumber']);
$router->get('/sejarah-sekolah', [PageController::class, 'page_sejarah_sekolah']);
$router->get('/senarai-pengetua', [PageController::class, 'page_senarai_pengetua']);
$router->get('/staff', [PageController::class, 'page_staff']);
$router->get('/unit-badan-beruniform', [PageController::class, 'page_unit_badan_beruniform']);
$router->get('/unit-bimbingan-kaunseling', [PageController::class, 'page_unit_bimbingan_kaunseling']);
$router->get('/', [PageController::class, 'home']);
