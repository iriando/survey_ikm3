@extends('layouts.app')

@section('content')

<main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section">
        <div class="hero-bg">
            <img src="img/hero-bg-light.webp" alt="">
        </div>
        <div class="container text-center">
            <div class="d-flex flex-column justify-content-center align-items-center">
            <h1 data-aos="fade-up">Selamat datang di</h1>
            <h1><span>Survei Kepuasan Masyarakat</span></h1>
            <p data-aos="fade-up" data-aos-delay="100">Kantor Regional XIV BKN Manokwari<br></p>
            <div class="d-flex" data-aos="fade-up" data-aos-delay="200">
                <a href="{{ route('skmtu.biodata') }}" class="btn-get-started" style="background-color: #25af1e; color: white;">SKM Bagian Tata Usaha</a>
                {{-- <a href="https://www.youtube.com/watch?v=Y7f98aduVJ8" class="glightbox btn-watch-video d-flex align-items-center"><i class="bi bi-play-circle"></i><span>Watch Video</span></a> --}}
            </div>
            <img src="img/front-img.png" class="img-fluid hero-img" alt="" data-aos="zoom-out" data-aos-delay="300">
            </div>
        </div>
    </section><!-- /Hero Section -->

</main>

@endsection
