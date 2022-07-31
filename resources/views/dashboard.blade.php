@extends('layouts.base', ["title" => "Dashboard"])

@php
    $role = auth()->user()->role->name;
@endphp

@push('style')
    <style>
        .btn-add {
            box-shadow: none;
            background-color: #02dda5 !important;
            color: #fff !important;
        }

        .btn-add:hover {
            background-color: #019a73 !important;
            color: #fff !important;
        }
    </style>
@endpush

@section('section-header')
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active">Dashboard</div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    Selamat datang, {{ auth()->user()->name }}
                </div>
            </div>
        </div>
    </div>

    @if ($role == 'admin' || $role == 'petugas loket')
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-success" style="background-color: #02dda5 !important">
                                {{-- <i class="fas fa-paper-plane"></i> --}}
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Pendapatan</h4>
                                </div>
                                <div class="card-body">
                                    Rp.{{ number_format($income, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="fas fa-ticket-alt"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Jumlah Tiket Terjual</h4>
                                </div>
                                <div class="card-body">
                                    {{ $ticketSold }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-danger">
                                <i class="fas fa-list"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Transaksi</h4>
                                </div>
                                <div class="card-body">
                                    {{ $transaction }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                <h4>Statistics</h4>
                {{-- <div class="card-header-action">
                    <div class="btn-group">
                    <a href="#" class="btn btn-primary">Week</a>
                    <a href="#" class="btn">Month</a>
                    </div>
                </div> --}}
                </div>
                <div class="card-body">
                <canvas id="myChart" height="182"></canvas>
                </div>
            </div>
            </div>
        </div>
    @endif
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        
        

        $.ajax({
            url: "{{ route('dashboard.get-income-by-day') }}",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                var ctx = document.getElementById('myChart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Pendapatan',
                            data: data.income,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 159, 64, 0.2)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });
            }
        })
        
    </script>
@endpush