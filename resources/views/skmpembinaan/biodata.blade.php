@extends('layouts.app')

@section('content')

<main class="main">
    <section id="service-details" class="service-details section">
    <div class="container">
        <div class="row gy-5">

          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">

            <div class="service-box">
              <h4>Serices List</h4>
              <div class="services-list">
                <a href="#" class="active" style="background-color: #af1e1e; color: white;"><i class="bi bi-arrow-right-circle"></i><span>Isi biodata</span></a>
                <a href="#"><i class="bi bi-arrow-right-circle" ></i><span>Isi SKM Pembinaan</span></a>
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

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('skmpembinaan.biodata') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        {{-- Nama --}}
                        <div class="col-md-6">
                            <label class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>

                        {{-- Usia --}}
                        <div class="col-md-3">
                            <label class="form-label">Usia</label>
                            <select name="usia" class="form-select" required>
                                <option value="" selected>Pilih usia</option>
                                <option value="20 - 30">20 - 30 tahun</option>
                                <option value="31 - 40">31 - 40 tahun</option>
                                <option value="41 - 50">41 - 50 tahun</option>
                                <option value="51 - 60">51 - 60 tahun</option>
                                <option value="61 keatas">61 tahun keatas</option>
                            </select>
                        </div>

                        {{-- Gender --}}
                        <div class="col-md-3">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select" required>
                                <option value="" selected disabled>Pilih Gender</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>

                        {{-- No HP --}}
                        <div class="col-md-6">
                            <label class="form-label">No HP</label>
                            <input type="text" name="nohp" class="form-control" required>
                        </div>

                        {{-- Pendidikan --}}
                        <div class="col-md-6">
                            <label class="form-label">Pendidikan</label>
                            <select name="pendidikan" class="form-select" required>
                                <option value="" selected disabled>Pilih Pendidikan</option>
                                <option value="SD">SD</option>
                                <option value="SMP">SMP</option>
                                <option value="SMA">SMA</option>
                                <option value="D3">D3</option>
                                <option value="S1">S1</option>
                                <option value="S2">S2</option>
                                <option value="S2">S3</option>
                            </select>
                        </div>

                        {{-- Pekerjaan --}}
                        <input type="hidden" name="pekerjaan" value="Pegawai Negeri Sipil">

                        <div class="col-md-6" id="jabatan-container">
                            <label class="form-label">Jabatan</label>
                            <select name="jabatan" id="jabatan" class="form-select" required>
                                <option value="" selected disabled>Pilih Jabatan</option>
                                <option value="Jabatan Pimpinan Tinggi">Jabatan Pimpinan Tinggi</option>
                                <option value="Administrator">Administrator</option>
                                <option value="Pengawas">Pengawas</option>
                                <option value="Jabatan Fungsional Tertentu">Jabatan Fungsional Tertentu</option>
                                <option value="Jabatan Pelaksana">Jabatan Pelaksana</option>
                            </select>
                        </div>

                        <div class="col-md-6" id="instansi-container">
                            <label class="form-label">Instansi</label>
                            <select name="instansi" id="instansi" class="form-select">
                                <option value="" selected disabled>Pilih Instansi</option>
                                @foreach($instansi as $instansi)
                                    <option value="{{ $instansi->nama_instansi }}">{{ $instansi->nama_instansi }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Kegiatan --}}
                        <div class="col-md-6">
                            <label class="form-label">Kegiatan</label>
                            <select name="kegiatan" class="form-select" id="kegiatan-select" required>
                                <option value="" selected disabled>Pilih Kegiatan</option>
                                @foreach($kegiatans as $kegiatan)
                                    <option value="{{ $kegiatan->n_kegiatan }}"
                                        data-narasumber='@json($kegiatan->narasumbers)'>
                                        {{ $kegiatan->n_kegiatan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Narasumber --}}
                        <div class="col-md-6">
                            <label class="form-label">Narasumber</label>
                            <select name="narasumber_id" class="form-select" id="narasumber-select" required>
                                <option value="" selected disabled>Pilih Narasumber</option>
                            </select>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-success px-4">Simpan</button>
                    </div>
                </form>
            </div>
          </div>

        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const kegiatanSelect = document.getElementById('kegiatan-select');
            const narasumberSelect = document.getElementById('narasumber-select');
            kegiatanSelect.addEventListener('change', function () {
                const selectedOption = this.options[this.selectedIndex];
                const narasumberData = JSON.parse(selectedOption.getAttribute('data-narasumber') || '[]');
                narasumberSelect.innerHTML = '<option value="">Pilih Narasumber</option>';
                narasumberData.forEach(n => {
                    const option = document.createElement('option');
                    option.value = n.id;
                    option.textContent = n.nama;
                    narasumberSelect.appendChild(option);
                });
            });
        });
    </script>
    </section><!-- /Service Details Section -->
</main>
@endsection
