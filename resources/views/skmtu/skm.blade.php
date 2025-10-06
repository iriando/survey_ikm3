@extends('layouts.app')

@section('content')

<main class="main">

    <!-- Service Details Section -->
    <section id="service-details" class="service-details section">

        <div class="container">

            <div class="row gy-5">

            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">

                <div class="service-box">
                <h4>Serices List</h4>
                <div class="services-list">
                    <a href="#"><i class="bi bi-arrow-right-circle"></i><span>Isi biodata</span></a>
                    <a href="#" class="active"><i class="bi bi-arrow-right-circle"></i><span>Isi survey IKM</span></a>
                    {{-- <a href="#"><i class="bi bi-arrow-right-circle"></i><span>Isi survey IPK</span></a> --}}
                </div>
                </div><!-- End Services List -->

                {{-- <div class="service-box">
                <h4>Download Catalog</h4>
                <div class="download-catalog">
                    <a href="#"><i class="bi bi-filetype-pdf"></i><span>Catalog PDF</span></a>
                    <a href="#"><i class="bi bi-file-earmark-word"></i><span>Catalog DOC</span></a>
                </div>
                </div><!-- End Services List -->

                <div class="help-box d-flex flex-column justify-content-center align-items-center">
                <i class="bi bi-headset help-icon"></i>
                <h4>Have a Question?</h4>
                <p class="d-flex align-items-center mt-2 mb-0"><i class="bi bi-telephone me-2"></i> <span>+1 5589 55488 55</span></p>
                <p class="d-flex align-items-center mt-1 mb-0"><i class="bi bi-envelope me-2"></i> <a href="mailto:contact@example.com">contact@example.com</a></p>
                </div> --}}

            </div>

            <div class="col-lg-8 ps-lg-5" data-aos="fade-up" data-aos-delay="200">
                <div class="card-body p-4">

                    <h2 class="mb-4 text-center">Survei Kepuasan Masyarakat</h2>

                    <style>
                        /* Kalau radio dipilih, labelnya jadi hijau */
                        .btn-check:checked + .btn {
                            background-color: #198754; /* warna hijau bootstrap */
                            border-color: #198754;
                            color: #fff;
                        }
                    </style>

                    <form action="{{ route('skmtu.skm', $responden->id) }}" method="POST">
                        @csrf

                        @foreach($pertanyaans as $pertanyaan)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <p><strong>{{ $pertanyaan->teks_pertanyaan }}</strong></p>
                                    @foreach($pertanyaan->pilihanJawabans as $index => $jawaban)
                                        <div class="form-check-inline mb-2">
                                            <input class="btn-check"
                                                type="radio"
                                                id="{{ $pertanyaan->unsur->kd_unsur }}_{{ $index }}"
                                                name="jawaban[{{ $pertanyaan->unsur->kd_unsur }}]"
                                                value="{{ $jawaban->np }}"
                                                required
                                                autocomplete="off">
                                            <label for="{{ $pertanyaan->unsur->kd_unsur }}_{{ $index }}"
                                                class="btn btn-primary">
                                                {{ $jawaban->teks_pilihan }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        <button type="submit" class="btn btn-success w-100">Kirim Jawaban</button>
                    </form>

                    {{-- <form action="{{ route('skmpelayanan.skm', $responden->id) }}" method="POST">
                        @csrf

                        @foreach($pertanyaans as $pertanyaan)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <p><strong>{{ $pertanyaan->teks_pertanyaan }}</strong></p>
                                    @foreach($pertanyaan->pilihanJawabans as $jawaban)
                                        <div class="form-check-inline">
                                            <input class="btn-check" type="radio" id="{{ $pertanyaan->unsur->kd_unsur }}" name="jawaban[{{ $pertanyaan->unsur->kd_unsur }}]" value="{{ $jawaban->np }}" required autocomplete="off">
                                            <label for="{{ $pertanyaan->unsur->kd_unsur }}" class="btn btn-primary">{{ $jawaban->teks_pilihan }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        <button type="submit" class="btn btn-success w-100">Kirim Jawaban</button>
                    </form> --}}

            </div>

            </div>

        </div>

    </section><!-- /Service Details Section -->

</main>

@endsection
