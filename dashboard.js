// Chart.js for Progress Monitoring
const ctx = document.getElementById('progressChart').getContext('2d');
const progressChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Completed', 'Pending', 'Upcoming'],
        datasets: [{
            label: 'Progress',
            data: [60, 20, 20], // Example data
            backgroundColor: ['#4caf50', '#f44336', '#ff9800'],
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            },
        },
    },
});
