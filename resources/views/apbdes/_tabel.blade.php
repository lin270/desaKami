@php
    $fmt = fn ($n) => \App\Support\Rupiah::format($n);
    $pct = fn ($real, $plan) => \App\Support\Rupiah::percent($real, $plan);
@endphp
<div class="tabel-scroll">
    <table class="tabel-apbdes">
        <thead>
            <tr><th>Uraian</th><th class="num">Anggaran</th><th class="num">Realisasi</th><th class="num">%</th></tr>
        </thead>
        <tbody>
            @foreach ($rows as $row)
                @php $p = $pct($row->amount_realized, $row->amount_plan); @endphp
                <tr>
                    <td>
                        {{ $row->name }}
                        @if ($p !== null)
                            <div class="bar bar-kecil" role="img" aria-label="Realisasi {{ $p }} persen"><span style="width:{{ min(100, $p) }}%"></span></div>
                        @endif
                    </td>
                    <td class="num">{{ $fmt($row->amount_plan) }}</td>
                    <td class="num">{{ $fmt($row->amount_realized) }}</td>
                    <td class="num">{{ $p === null ? '-' : str_replace('.', ',', (string) $p).'%' }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            @php $tp = $pct($total['realized'], $total['plan']); @endphp
            <tr>
                <th>{{ $totalLabel }}</th>
                <th class="num">{{ $fmt($total['plan']) }}</th>
                <th class="num">{{ $fmt($total['realized']) }}</th>
                <th class="num">{{ $tp === null ? '-' : str_replace('.', ',', (string) $tp).'%' }}</th>
            </tr>
        </tfoot>
    </table>
</div>
