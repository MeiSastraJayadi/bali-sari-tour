<div class="col-lg-6 col-md-6 col-sm-6 col-12">
    <div class="d-flex justify-content-center align-items-center" style="height: 400px;">
        <canvas class="bg-gradient-info" id="{{ $id }}" width="400" height="200"></canvas>
    </div>    
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('{{ $id }}').getContext('2d');
        new Chart(ctx, {
            type: 'line', // can be 'line', 'pie', 'doughnut', etc.
            data: {
                labels: ['January', 'Februari', 'Maret', 'April', "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"],
                datasets: [{
                    label: 'Sales',
                    data: [12, 19, 3, 5],
                    backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { 
                        display: true, 
                        labels: {
                            color: 'white' 
                        } 
                    }
                }, 
                scales: {
                    x: {
                        ticks: {
                            color: 'white' 
                        },
                        title: {
                            display: true,
                            text: 'Month',
                            color: 'white' 
                        },
                        grid: {
                            color: 'rgba(255,255,255,0.2)' 
                        }
                    },
                    y: {
                        ticks: {
                            color: 'white' 
                        },
                        title: {
                            display: true,
                            text: 'Visitors',
                            color: 'white' 
                        },
                        grid: {
                            color: 'rgba(255,255,255,0.2)' 
                        }
                    }
                }
            }
        });
    });
</script>
