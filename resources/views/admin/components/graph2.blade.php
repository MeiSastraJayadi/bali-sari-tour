<div class="col-lg-6 col-md-6 col-sm-6 col-12">
    <div class="d-flex justify-content-center align-items-center" style="height: 400px;">
        <canvas class="bg-gradient-info" data-value="{{ $data['data'] }}" id="{{ $id }}" width="400" height="200"></canvas>
    </div>    
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('{{ $id }}').getContext('2d');
        const data = document.getElementById('{{ $id }}')
        const value = JSON.parse(data.dataset.value)

        new Chart(ctx, {
            type: 'line', 
            data: {
                labels: ['January', 'Februari', 'Maret', 'April', "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"],
                datasets: [{
                    label: "Jumlah Revenue",
                    data: value,
                    backgroundColor: ['#0d6efd']
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
                            text: 'Bulan',
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
                            text: "Pemasukan (Rp)",
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
