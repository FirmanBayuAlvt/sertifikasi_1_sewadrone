<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Riwayat Booking — Print</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { font-size: 12px; }
    .table th, .table td { vertical-align: middle; }
    @media print { .no-print { display: none !important; } .page-break{page-break-after:always;} }
    .report-header { margin-bottom: 20px; }
  </style>
</head>
<body>
<div class="container-fluid">
  <div class="report-header row">
    <div class="col-8">
      <h4>Riwayat Booking</h4>
      <div class="small text-muted">
        Filter:
        @if(!empty($filters))
          @foreach($filters as $k=>$v)
            @if($v) {{ $k }}={{ $v }}; @endif
          @endforeach
        @else
          Semua
        @endif
      </div>
    </div>
    <div class="col-4 text-end no-print">
      <button class="btn btn-sm btn-primary" onclick="window.print()">Cetak</button>
      <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
    </div>
  </div>

  <table class="table table-bordered table-sm">
    <thead>
      <tr>
        <th>#</th>
        <th>User (email)</th>
        <th>Drone (serial)</th>
        <th>Durasi</th>
        <th>Harga</th>
        <th>Status</th>
        <th>Start / End</th>
        <th>Created At</th>
      </tr>
    </thead>
    <tbody>
      @forelse($bookings as $i => $b)
        <tr>
          <td>{{ $i+1 }}</td>
          <td>{{ $b->user->name ?? '-' }} <br><small>{{ $b->user->email ?? '' }}</small></td>
          <td>{{ $b->drone->model ?? '-' }} <br><small>{{ $b->drone->serial_no ?? '' }}</small></td>
          <td>{{ $b->duration_hours ?? '-' }} jam</td>
          <td>Rp{{ number_format($b->price ?? 0,0,',','.') }}</td>
          <td>{{ ucfirst($b->status) }}</td>
          <td>
            {{ optional($b->start_at)->format('Y-m-d H:i') ?? '-' }}<br>
            {{ optional($b->end_at)->format('Y-m-d H:i') ?? '-' }}
          </td>
          <td>{{ optional($b->created_at)->format('Y-m-d H:i') }}</td>
        </tr>
      @empty
        <tr><td colspan="8" class="text-center">Tidak ada data.</td></tr>
      @endforelse
    </tbody>
  </table>

  <div class="mt-4 small text-muted">
    Dicetak oleh: {{ auth()->user()->name ?? 'System' }}, tanggal: {{ now()->format('Y-m-d H:i') }}
  </div>
</div>

<script>
  window.onload = function(){
    setTimeout(function(){ window.print(); }, 200);
  }
</script>
</body>
</html>
