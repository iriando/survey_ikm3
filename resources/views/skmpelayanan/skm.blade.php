@extends('layouts.app')
@section('content')

<main class="main">
    <!-- Service Details Section -->
    <section id="service-details" class="service-details section">
        <div class="container">
            <div class="row gy-5">
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="service-box">
                        <h4>Formulir</h4>
                        <div class="services-list">
                            <a href="#"><i class="bi bi-arrow-right-circle"></i><span>Isi biodata</span></a>
                            <a href="#" class="active"><i class="bi bi-arrow-right-circle"></i><span>Isi survey IKM</span></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 ps-lg-5" data-aos="fade-up" data-aos-delay="200">
                    <div class="card-body p-4">
                        <h2 class="mb-4 text-center">Survei Kepuasan Masyarakat</h2>
                        <style>
                            .btn-check:checked + .btn {
                                background-color: #198754; /* warna hijau bootstrap */
                                border-color: #198754;
                                color: #fff;
                            }
                        </style>
                        <form action="{{ route('skmpelayanan.skm', $responden->id) }}" method="POST">
                            @csrf
                            @foreach($pertanyaans as $pertanyaan)
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <p><strong>{{ $pertanyaan->teks_pertanyaan }}</strong></p>
                                        @foreach($pertanyaan->pilihanJawabans as $index => $jawaban)
                                        <div class="form-check-inline mb-2">
                                            <input class="btn-check" type="radio"id="{{ $pertanyaan->unsur->kd_unsur }}_{{ $index }}"name="jawaban[{{ $pertanyaan->unsur->kd_unsur }}]"value="{{ $jawaban->np }}"requiredautocomplete="off">
                                            <label for="{{ $pertanyaan->unsur->kd_unsur }}_{{ $index }}"class="btn btn-primary">{{ $jawaban->teks_pilihan }}</label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                            <button type="submit" class="btn btn-success w-100">Kirim Jawaban</button>
                        </form>
                </div>
            </div>
        </div>
    </section><!-- /Service Details Section -->
</main>
@endsection
