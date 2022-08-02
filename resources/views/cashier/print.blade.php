<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>E-Ticket | Print &mdash; </title>

  <!-- General CSS Files -->
  {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"> --}}
  <!-- Template CSS -->
  {{-- <link rel="stylesheet" href="{{ asset('/') }}assets/css/style.css"> --}}
  {{-- <link rel="stylesheet" href="{{ asset('/') }}assets/css/components.css"> --}}

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600&display=swap" rel="stylesheet">

  <style>
    * {
        font-family: 'Poppins', sans-serif;
    }
    @media print {
      .print-only {
        display: none;
      }
    }
    .logo {
        display: block;
        margin-left: auto;
        margin-right: auto;
    }
    .date-event {
        text-align: center;
        font-size: 10pt;
        font-weight: 400;
    }
    .product-name {
        text-align: center;
        font-size: 14pt;
        font-weight: 600;
        margin-bottom: -15px;
    }
    .barcode {
        /* display: block;
        margin-left: auto;
        margin-right: auto; */
        text-align: center;
    }
    .line {
        border-top: 2px #000 dashed;
        margin-top: 25px;
        margin-bottom: 25px;
    }
    .line:last-child {
        margin-bottom: 0;
    }
  </style>
</head>

<body>
    <div class="row justify-content-center">
        <div class="col-lg-12 gap">
            @foreach ($data as $item)
                <div class="">
                    <img src="{{ asset('assets/img/logo_app.png') }}" class="logo" width="250px">
                </div>
                <div class="">
                    <h3 class="product-name">{{ $item->product->name }}</h3>
                    <p class="date-event">{{ $item->product->date_event->format('l, d-m-Y') }}</p>
                </div>
                <div class="barcode">
                    {{-- {!! DNS1D::getBarcodeSVG($item->barcode, 'C39',1,65); !!} --}}
                    {{-- {!! DNS1D::getBarcodeSVG($item->barcode, 'C39',0.5,65); !!} --}}
                    {{-- DNS2D::getBarcodeSVG('4445645656', 'DATAMATRIX'); --}}
                    {!! DNS2D::getBarcodeSVG($item->barcode, 'QRCODE', 5, 5); !!}
                </div>
                <hr class="line">
            @endforeach
        </div>
    </div>



  <!-- General JS Scripts -->
  {{-- <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script> --}}
  <script src="{{ asset('assets/js/jquery/jquery-3.6.0.min.js') }}"></script>
  <script src="{{ asset('/') }}assets/js/stisla.js"></script>

  <!-- JS Libraies -->

  <!-- Template JS File -->
  <script src="{{ asset('/') }}assets/js/scripts.js"></script>
  <script src="{{ asset('/') }}assets/js/custom.js"></script>

  <script>
    $(document).ready(function() {
        window.print();
        window.onfocus=function(){ window.close(); }
    });
  </script>
</body>
</html>