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
                <a href="#" class="active"><i class="bi bi-arrow-right-circle"></i><span>Isi biodata</span></a>
                <a href="#"><i class="bi bi-arrow-right-circle"></i><span>Isi survey IKM</span></a>
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
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('skmtu.biodata') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        {{-- Nama --}}
                        <div class="col-md-6">
                            <label class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>

                        {{-- layanan --}}
                        <div class="col-md-6">
                            <label class="form-label">Jenis Layanan</label>
                            <select name="j_layanantu" class="form-select" required>
                                <option value="" selected disabled>Pilih Jenis Layanan</option>
                                @foreach($layanans as $layanan)
                                    <option value="{{ $layanan->j_layanan }}">{{ $layanan->j_layanan }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    {{-- Submit --}}
                    <div class="text-left mt-4">
                        <button type="submit" class="btn btn-success px-4">Lanjut</button>
                    </div>
                </form>
            </div>
          </div>

        </div>

      </div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const pnsRadio = document.getElementById('pns');
                const nonAsnRadio = document.getElementById('nonasn');
                const jabatanContainer = document.getElementById('jabatan-container');
                const instansiContainer = document.getElementById('instansi-container');
                const jabatanSelect = document.getElementById('jabatan');
                const instansiSelect = document.getElementById('instansi');

                function toggleFields() {
                    if (pnsRadio.checked) {
                        jabatanContainer.style.display = 'block';
                        instansiContainer.style.display = 'block';
                    } else {
                        jabatanContainer.style.display = 'none';
                        instansiContainer.style.display = 'none';
                        jabatanSelect.value = '';
                        instansiSelect.value = '';
                    }
                }

                // Event listener
                pnsRadio.addEventListener('change', toggleFields);
                nonAsnRadio.addEventListener('change', toggleFields);

                // Cek kondisi saat halaman dimuat (misal form reload karena error)
                toggleFields();
            });
            </script>
    </section><!-- /Service Details Section -->

  </main>

@endsection
