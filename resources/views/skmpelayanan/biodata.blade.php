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

                <form action="{{ route('skmpelayanan.biodata') }}" method="POST">
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
                                <option value="" selected>Pilih Gender</option>
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
                                <option value="" selected>Pilih Pendidikan</option>
                                <option value="SD">SD</option>
                                <option value="SMP">SMP</option>
                                <option value="SMA">SMA</option>
                                <option value="D3">D3</option>
                                <option value="S1">S1</option>
                                <option value="S2">S2</option>
                            </select>
                        </div>

                        {{-- Pekerjaan --}}
                        <div class="col-md-6">
                            <label class="form-label d-block">Pekerjaan</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="pekerjaan" id="pns" value="Pegawai Negeri Sipil" required>
                                <label class="form-check-label" for="pns">Pegawai Negeri Sipil</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="pekerjaan" id="nonasn" value="Non ASN" required>
                                <label class="form-check-label" for="nonasn">Non ASN</label>
                            </div>
                        </div>

                        {{-- Jabatan (Hidden by default) --}}
                        <div class="col-md-6" id="jabatan-container" style="display:none;">
                            <label class="form-label">Jabatan</label>
                            <select name="jabatan" id="jabatan" class="form-select">
                                <option value="" selected>Pilih Jabatan</option>
                                <option value="Jabatan Pimpinan Tinggi">Jabatan Pimpinan Tinggi</option>
                                <option value="Administrator">Administrator</option>
                                <option value="Pengawas">Pengawas</option>
                                <option value="Jabatan Fungsional Tertentu">Jabatan Fungsional Tertentu</option>
                                <option value="Jabatan Pelaksana">Jabatan Pelaksana</option>
                            </select>
                        </div>

                        {{-- Instansi (Hidden by default) --}}
                        <div class="col-md-6" id="instansi-container" style="display:none;">
                            <label class="form-label">Instansi</label>
                            <select name="instansi" id="instansi" class="form-select">
                                <option value="" selected>Pilih Instansi</option>
                                @foreach($instansi as $instansi)
                                    <option value="{{ $instansi->nama_instansi }}">{{ $instansi->nama_instansi }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- layanan --}}
                        <div class="col-md-6">
                            <label class="form-label">Jenis Layanan</label>
                            <select name="j_layanan" class="form-select" required>
                                <option selected>Pilih Jenis Layanan</option>
                                @foreach($layanans as $layanan)
                                    <option value="{{ $layanan->j_layanan }}">{{ $layanan->j_layanan }}</option>
                                @endforeach
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
