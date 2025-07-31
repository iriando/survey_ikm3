<x-filament-panels::page>
    <div class="space-y-4">
        {{-- Form filter tanggal --}}
        <div class="flex gap-4">
            {{ $this->form }}
        </div>
    </div>

    <x-filament::card>
        <h2 class="text-lg font-bold mb-4">Laporan Data Responden</h2>

        <div class="table-responsive">
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border border-gray-300 px-4 py-2">Nama Responden</th>
                        @foreach($this->getPertanyaan() as $p)
                            <th class="border border-gray-300 px-4 py-2">{{ $p->unsur->kd_unsur }}</th>
                        @endforeach
                        <th class="border border-gray-300 px-4 py-2">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($this->getDataResponden() as $responden)
                        <tr>
                            <td class="border border-gray-300 px-4 py-2">{{ $responden->nama_responden }}</td>
                            @foreach($this->getPertanyaan() as $p)
                                <td class="border border-gray-300 px-4 py-2 text-center">
                                    {{ $responden->{$p->unsur->kd_unsur} ?? '-' }}
                                </td>
                            @endforeach
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $responden->tanggal }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-filament::card>

    <x-filament::card>
        <h2 class="text-lg font-bold mb-4">Laporan Jumlah Responden Berdasarkan Gender</h2>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border border-gray-300 px-4 py-2">Gender</th>
                    <th class="border border-gray-300 px-4 py-2">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($this->getGenderCount() as $data)
                    <tr>
                        <td class="border border-gray-300 px-4 py-2">{{ $data->gender }}</td>
                        <td class="border border-gray-300 px-4 py-2 text-center">{{ $data->total }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-filament::card>

    <x-filament::card>
        <h2 class="text-lg font-bold mb-4">Laporan Jumlah Responden Berdasarkan Pendidikan</h2>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border border-gray-300 px-4 py-2">Pendidikan</th>
                    <th class="border border-gray-300 px-4 py-2">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($this->getPendidikanCount() as $data)
                    <tr>
                        <td class="border border-gray-300 px-4 py-2">{{ $data->pendidikan }}</td>
                        <td class="border border-gray-300 px-4 py-2 text-center">{{ $data->total }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-filament::card>

    <x-filament::card>
        <h2 class="text-lg font-bold mt-8 mb-4">Total Skor</h2>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border border-gray-300 px-4 py-2">Parameter (kd_unsur)</th>
                    <th class="border border-gray-300 px-4 py-2">Total Skor</th>
                    <th class="border border-gray-300 px-4 py-2">NRR (Rata-rata)</th>
                    <th class="border border-gray-300 px-4 py-2">SKM</th>
                </tr>
            </thead>
            <tbody>
                @foreach($this->getTotalPerParameter() as $param)
                    <tr>
                        <td class="border border-gray-300 px-4 py-2">{{ $param->kd_unsurikmpembinaan }}</td>
                        <td class="border border-gray-300 px-4 py-2 text-center">{{ $param->total_skor }}</td>

                        @php
                            $avgSkor = collect($this->getAveragePerParameter())->where('kd_unsurikmpembinaan', $param->kd_unsurikmpembinaan)->first();
                            $skm = collect($this->getSkmPerParameter())->where('kd_unsurikmpembinaan', $param->kd_unsurikmpembinaan)->first();
                        @endphp

                        <td class="border border-gray-300 px-4 py-2 text-center">
                            {{ $avgSkor->avg_skor ?? '0.00' }}
                        </td>
                        <td class="border border-gray-300 px-4 py-2 text-center">
                            {{ $skm->skm ?? '0.00' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-filament::card>

    <x-filament::card>
        <h2 class="text-lg font-bold mt-8 mb-4">IKM</h2>
        <div class="table-responsive">
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border border-gray-300 px-4 py-2">Nilai IKM</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2 text-center">{{ $this->getikm() }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </x-filament::card>

</x-filament-panels::page>

