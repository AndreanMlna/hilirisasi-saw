<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;


class AlgoritmaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $data = $this->hitungRanking();

        if ($request->has('search')) {
            $data['ranking'] = collect($data['ranking'])->filter(function ($item, $key) use ($request) {
                return str_contains(strtolower($key), strtolower($request->search));
            })->toArray();
        }

        return view('admin.perhitungan.index', $data);
    }

    public function cetakPDF()
    {
        $data = $this->hitungRanking();
        $pdf = Pdf::loadView('admin.perhitungan.laporan_pdf', $data);
        return $pdf->stream('laporan-ranking.pdf');
    }

    public function exportCSV()
    {
        $data = $this->hitungRanking();
        $filename = "hasil-perankingan.csv";
        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, ['Alternatif', 'Total Nilai']);

        foreach ($data['ranking'] as $nama => $item) {
            fputcsv($handle, [$nama, $item['total']]);
        }
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return Response::make($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ]);
    }

    private function hitungRanking()
    {
        $alternatif = Alternatif::with('penilaian.crips')->get();
        $kriteria   = Kriteria::with('crips')->orderBy('nama_kriteria', 'ASC')->get();
        $penilaian  = Penilaian::with('crips', 'alternatif')->get();

        $minMax       = $this->hitungMinMax($kriteria, $penilaian);
        $normalisasi  = $this->hitungNormalisasi($kriteria, $penilaian, $minMax);
        $ranking      = $this->hitungTotalRanking($normalisasi, $kriteria);

        return [
            'alternatif'   => $alternatif,
            'kriteria'     => $kriteria,
            'normalisasi'  => $normalisasi,
            'ranking'      => $ranking,
        ];
    }

    private function hitungMinMax($kriteria, $penilaian)
    {
        $minMax = [];
        foreach ($kriteria as $krit) {
            foreach ($penilaian as $pn) {
                if ($pn->crips && $krit->id == $pn->crips->kriteria_id) {
                    $minMax[$krit->id][] = $pn->crips->bobot;
                }
            }
        }
        return $minMax;
    }

    private function hitungNormalisasi($kriteria, $penilaian, $minMax)
    {
        $normalisasi = [];
        foreach ($penilaian as $pn) {
            foreach ($kriteria as $krit) {
                if ($pn->crips && $krit->id == $pn->crips->kriteria_id) {
                    $namaAlternatif = $pn->alternatif->nama_alternatif ?? 'Tidak diketahui';
                    $bobot          = $pn->crips->bobot ?? 0;
                    $max            = !empty($minMax[$krit->id]) ? max($minMax[$krit->id]) : 1;
                    $min            = !empty($minMax[$krit->id]) ? min($minMax[$krit->id]) : 1;

                    if ($krit->attribut === 'Benefit' && $max > 0) {
                        $normalisasi[$namaAlternatif][$krit->id] = $bobot / $max;
                    } elseif ($krit->attribut === 'Cost' && $bobot > 0) {
                        $normalisasi[$namaAlternatif][$krit->id] = $min / $bobot;
                    } else {
                        $normalisasi[$namaAlternatif][$krit->id] = 0;
                    }
                }
            }
        }
        return $normalisasi;
    }

    private function hitungTotalRanking($normalisasi, $kriteria)
    {
        $ranking = [];
        foreach ($normalisasi as $namaAlt => $nilaiKriteria) {
            $total            = 0;
            $nilaiPerKriteria = [];

            foreach ($kriteria as $krit) {
                $nilai = ($nilaiKriteria[$krit->id] ?? 0) * $krit->bobot;
                $nilaiPerKriteria[$krit->id] = $nilai;
                $total += $nilai;
            }

            $ranking[$namaAlt] = [
                'kriteria' => $nilaiPerKriteria,
                'total'    => round($total, 2),
            ];
        }

        return collect($ranking)->sortByDesc('total')->toArray();
    }
}
