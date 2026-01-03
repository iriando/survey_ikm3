@extends('layouts.app')

@section('content')

<main class="main">
    <!-- Starter Section Section -->
    <section id="starter-section" class="starter-section section">

        <div class="container py-5">
            <h2>Kritik & Saran</h2>
            <p>Silakan berikan masukan Anda setelah mengisi survei.</p>

            <form method="POST" action="{{ route('kritik-saranpembinaan.submit', $responden->id) }}">
                @csrf
                <div class="mb-3">
                    <label for="kritik_saran" class="form-label">Kritik & Saran</label>
                    <textarea name="kritik_saran" class="form-control" rows="5">{{ old('kritik_saran', $responden->kritik_saran) }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">Kirim</button>
            </form>
        </div>

    </section><!-- /Starter Section Section -->

</main>

@endsection
