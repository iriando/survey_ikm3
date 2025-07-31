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
                        <div class="col-md-6">
                            <label class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Usia</label>
                            <input type="number" name="usia" class="form-control" min="1" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select" required>
                                <option value="" selected>Pilih Gender</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">No HP</label>
                            <input type="text" name="nohp" class="form-control" required>
                        </div>

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

                        <div class="col-md-6">
                            <label class="form-label">Instansi</label>
                            <select name="instansi" class="form-select" required>
                                <option value="" selected>Pilih Instansi</option>
                                @foreach($instansi as $instansi)
                                    <option value="{{ $instansi->nama_instansi }}">{{ $instansi->nama_instansi }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Jenis Layanan</label>
                            <select name="j_layanan" class="form-select" required>
                                <option selected>Pilih Jenis Layanan</option>
                                @foreach($layanans as $layanan)
                                    <option value="{{ $layanan->j_layanan }}">{{ $layanan->j_layanan }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-success px-4">Simpan</button>
                    </div>
                </form>
            </div>
          </div>

        </div>

      </div>

    </section><!-- /Service Details Section -->

  </main>

@endsection
