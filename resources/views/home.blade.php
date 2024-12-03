@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Dashboard
                </div>

                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-4">
                            <div class="card text-white bg-primary">
                                <div class="card-body pb-3">
                                    <div class="text-value">{{ number_format($totalTickets) }}</div>
                                    <div>Total Tickets</div>
                                    <br />
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card text-white bg-success">
                                <div class="card-body pb-3">
                                    <div class="text-value">{{ number_format($openTickets) }}</div>
                                    <div>Open Tickets</div>
                                    <br />
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card text-white bg-success">
                                <div class="card-body pb-3">
                                    <div class="text-value">{{ number_format($progressTickets) }}</div>
                                    <div>Progress Tickets</div>
                                    <br />
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card text-white bg-danger">
                                <div class="card-body pb-3">
                                    <div class="text-value">{{ number_format($closedTickets) }}</div>
                                    <div>Closed Tickets</div>
                                    <br />
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                Ticket Overview
            </div>
            <div class="card-body">
                <canvas id="ticketChart" height="500"></canvas> <!-- Set height for compactness -->
            </div>
        </div>
    </div>
</div>
                
@endsection

@section('scripts')
@parent
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('ticketChart').getContext('2d');
    var ticketChart = new Chart(ctx, {
        type: 'bar', // Change to bar chart
        data: {
            labels: ['Total Tickets', 'Open Tickets', 'Progress Tickets', 'Closed Tickets'],
            datasets: [{
                label: 'Number of Tickets',
                data: [
                    {{ $totalTickets }},
                    {{ $openTickets }},
                    {{ $progressTickets }},
                    {{ $closedTickets }}
                ],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.6)', // Total Tickets
                    'rgba(75, 192, 192, 0.6)', // Open Tickets
                    'rgba(75, 192, 192, 0.6)', // Open Tickets
                    'rgba(255, 99, 132, 0.6)'   // Closed Tickets
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1,
                barThickness: 20, // Set bar thickness to make it minimal
                maxBarThickness: 25 // Max thickness for bars
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1 // Set step size for y-axis ticks
                    }
                },
                x: {
                    ticks: {
                        autoSkip: false // Show all x-axis labels
                    }
                }
            },
            responsive: true,
            maintainAspectRatio: false // Allow for height control
        }
    });
</script>
@endsection