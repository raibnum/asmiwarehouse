<!DOCTYPE html>
<html lang="en">

<head>
  <title>Report Inout Tool</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  {{--
  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}"> --}}
  <link rel="stylesheet" href="{{ asset('css/inout_pdf.css') }}">

  <style>
    @import url("https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap");

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }

    html {
      height: 100%;
    }

    body {
      padding: 10px !important;
    }

    table {
      border-collapse: collapse;
    }

    .content-wrapper {
      background-color: #fff !important;
    }

    table.header {
      width: 100%;
      margin-bottom: 20px;
      border: 1px solid #000;
    }

    table.header tr td {
      padding: 10px;
      text-align: center;
      border: 1px solid #000;
    }

    table.header tr td:nth-child(1) {
      width: 20%;
    }

    table.header tr td:nth-child(1) img {
      height: 80px;
    }

    table.header tr td:nth-child(2) {
      width: 80%;
    }

    table.header tr td:nth-child(2) table.title {
      width: 100%;
    }

    table.header tr td:nth-child(2) table.title tr {
      height: 50%;
      border: none;
    }

    table.header tr td:nth-child(2) table.title tr:nth-child(1) td h1 {
      font-size: 30px;
    }

    table.header tr td:nth-child(2) table.title tr td {
      border: none;
      padding: none;
    }

    table.detail {
      width: 100%;
    }

    table.detail th,
    table.detail td {
      border: 1px solid #000;
      padding: 5px;
      text-align: center;
    }
  </style>
</head>

<body>
  <div class="content-wrapper">
    <table class="header">
      <tr>
        <td>
          <img src="{{ asset('images/logo-asmi.png') }}" alt="Logo">
        </td>
        <td>
          <table class="title">
            <tr>
              <td>
                <h1>Laporan Keluar - Masuk Tool</h1>
              </td>
            </tr>
            <tr>
              <td>Periode: {{ $tgl_awal }} - {{ $tgl_akhir }}
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table> <!-- /.header -->

    <table class="detail">
      <thead>
        <tr>
          <th style="width: 5%;">No</th>
          <th style="width: 15%;">Tanggal</th>
          <th>Kode Tool</th>
          <th>Nama Tool</th>
          <th style="width: 20%;">Operator</th>
          <th style="width: 5%;">Qty</th>
          <th style="width: 10%;">Harga</th>
          <th style="width: 10%;">Status</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($inout as $io)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ \Carbon\Carbon::parse($io->tgl)->format('d-m-Y') }}</td>
          <td>{{ $io->kd_tool }}</td>
          <td>{{ $io->tool->nm_tool }}</td>
          <td>{{ $io->opr->nm_operator }}</td>
          <td>{{ $io->qty }}</td>
          <td>{{ (int) $io->harga }}</td>
          <td>{{ $io->status }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div> <!-- /.content-wrapper -->
</body>

</html>