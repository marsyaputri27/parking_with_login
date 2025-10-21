<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>@yield('title', 'Sistem Parkir')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <style>
    body {
      background: #f5f5f5;
      font-family: "Courier New", monospace;
      font-size: 14px;
      line-height: 1.4;
    }

    .ticket {
      width: 300px;          /* lebar standar karcis */
      margin: 20px auto;
      padding: 15px;
      background: #fff;
      border: 1px dashed #000; /* border putus-putus seperti struk */
      box-shadow: 0 0 5px rgba(0,0,0,0.1);
    }

    .ticket-header {
      text-align: center;
      margin-bottom: 10px;
    }

    .ticket-logo {
      height: 60px;
      margin-bottom: 5px;
    }

    .company-info {
      font-size: 13px;
      line-height: 1.3;
    }
    .company-name {
      font-size: 15px;
      display: block;
      font-weight:bold ;
    }

    h3, h5 {
      text-align: center;
      margin: 8px 0;
      font-size: 16px;
      text-transform: uppercase;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 8px;
    }
    td {
      padding: 2px 0;
      font-size: 13px;
    }
    td.label {
      width: 40%;
      font-weight: bold;
    }

    .line {
      border-top: 1px dashed #000;
      margin: 6px 0;
    }

    .total {
      font-weight: bold;
      font-size: 15px;
      margin-top: 8px;
    }

    .center {
      text-align: center;
    }

    .barcode {
      margin: 15px 0;
      text-align: center;
    }

    .transaksid1{
      margin-left: 85px
    }
    
    .footer {
      text-align: center;
      font-size: 12px;
      margin-top: 5px;
    }

    .total{
      text-align: center;
    }

   .back{
    background-color: blue;
    padding: 20px;
    color: white;
    text-decoration: none;
    border-radius: 10px;
    margin-top: 60px;
   }
    /* print mode */
    @media print {
      body { background: #fff; }
      .no-print { display: none !important; }
      .ticket { border: none; box-shadow: none; width: 100%; }
    }
  </style>

  @yield('styles')
</head>
<body>
  <div class="ticket">
    @yield('content')
  </div>

  @yield('scripts')
</body>
</html>
