@extends('layouts.app')
@section('title', 'SPK SAW | Perhitungan SAW')
@section('content')

    <div class="card shadow mb-4">
        <a href="#listkriteria" class="d-block card-header py-3" data-toggle="collapse"
            role="button" aria-expanded="true" aria-controls="collapseCardExample">
            <h6 class="m-0 font-weight-bold text-primary">Tahap Analisa</h6>
        </a>
        <div class="collapse show" id="listkriteria">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama Alternatif</th>
                                @foreach($kriteria as $key => $value)
                                    <th>{{ $value->nama_kriteria }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($alternatif as $alt => $valt)
                                <tr>
                                    <td>{{ $valt->nama_alternatif }}</td>
                                    @if(count($valt->penilaian) > 0)
                                        @foreach($valt->penilaian as $key => $value)
                                        <td>
                                            {{ optional($value->crips)->bobot ?? '-' }}
                                        </td>

                                        @endforeach
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td>Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <a href="#normalisasi" class="d-block card-header py-3" data-toggle="collapse"
            role="button" aria-expanded="true" aria-controls="collapseCardExample">
            <h6 class="m-0 font-weight-bold text-primary">Tahap Normalisasi</h6>
        </a>
        <div class="collapse show" id="listkriteria">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Alternatif / Kriteria</th>
                                @foreach($kriteria as $key => $value)
                                    <th>{{ $value->nama_kriteria }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($normalisasi as $namaAlt => $nilaiKriteria)
                                <tr>
                                    <td>{{ $namaAlt }}</td>
                                    @foreach($kriteria as $krit)
                                        <td>{{ number_format($nilaiKriteria[$krit->id] ?? 0, 2) }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="mb-5 d-flex justify-content-end align-items-center">
        <div class="d-flex align-items-center">
            <h6 class="m-0 font-weight-bold text-dark mr-3">Aksi</h6>
            <a href="{{ route('perhitungan.cetakPDF') }}" target="_blank" class="btn btn-danger btn-sm mr-2">
                <i class="fas fa-file-pdf"></i> Cetak PDF
            </a>
            <a href="{{ route('perhitungan.exportCSV') }}" class="btn btn-success btn-sm">
                <i class="fas fa-file-csv"></i> Export CSV
            </a>
        </div>
    </div>


    <div class="card shadow mb-4">
        <a href="#rank" class="d-block card-header py-3" data-toggle="collapse"
            role="button" aria-expanded="true" aria-controls="collapseCardExample">
            <h6 class="m-0 font-weight-bold text-primary">Tahap Perangkingan</h6>
        </a>
        <div class="collapse show" id="rank">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th></th>
                                @foreach($kriteria as $key => $value)
                                    <th>{{ $value->nama_kriteria }}</th>
                                @endforeach
                                <th rowspan="2" style="text-align: center; padding-bottom: 45px;">Total</th>
                                <th rowspan="2" style="text-align: center; padding-bottom: 45px;">Rank</th>
                            </tr>
                            <tr>
                                <th>Bobot</th>
                                @foreach($kriteria as $key => $value)
                                    <th>{{ $value->bobot }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                        @php $no = 1; @endphp
                        @foreach($ranking as $namaAlt => $data)
                            <tr>
                                <td>{{ $namaAlt }}</td>
                                @foreach($kriteria as $krit)
                                    <td>{{ number_format($data['kriteria'][$krit->id] ?? 0, 2) }}</td>
                                @endforeach
                                <td>{{ number_format($data['total'], 2) }}</td>
                                <td>{{ $no++ }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
