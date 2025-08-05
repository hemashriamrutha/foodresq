document.addEventListener('DOMContentLoaded', async function() {
    const totalDonationsEl = document.getElementById('total-donations');
    const totalClaimedEl = document.getElementById('total-claimed');
    const totalUnclaimedEl = document.getElementById('total-unclaimed');
    const activityBody = document.getElementById('activity-body');
    const chartCanvas = document.getElementById('donationsChart');

    // --- Function to Fetch All Dashboard Data ---
    async function loadDashboardData() {
        try {
            const response = await fetch('get_admin_stats.php');
            const data = await response.json();

            // Populate Stats Cards
            const totalDonations = parseInt(data.stats.total_donations, 10);
            const totalClaimed = parseInt(data.stats.total_claimed, 10);
            totalDonationsEl.textContent = totalDonations;
            totalClaimedEl.textContent = totalClaimed;
            totalUnclaimedEl.textContent = totalDonations - totalClaimed;

            // Populate Recent Activity Table
            activityBody.innerHTML = ''; // Clear existing rows
            data.recent_donations.forEach(donation => {
                const row = document.createElement('tr');
                const status = donation.is_claimed == 1 ? '<span class="status-claimed">Claimed</span>' : '<span class="status-unclaimed">Available</span>';
                row.innerHTML = `
                    <td>${donation.food_name}</td>
                    <td>${donation.location}</td>
                    <td>${status}</td>
                    <td><button class="delete-btn" data-id="${donation.id}">Delete</button></td>
                `;
                activityBody.appendChild(row);
            });

            // Create Donations Chart
            const chartLabels = data.chart_data.map(item => item.month);
            const chartValues = data.chart_data.map(item => item.count);
            new Chart(chartCanvas, {
                type: 'bar',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: '# of Donations',
                        data: chartValues,
                        backgroundColor: 'rgba(40, 167, 69, 0.7)',
                        borderColor: 'rgba(40, 167, 69, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: { y: { beginAtZero: true } },
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

        } catch (error) {
            console.error('Failed to load dashboard data:', error);
        }
    }
    
    // --- Event Listener for Delete Buttons ---
    activityBody.addEventListener('click', async function(event) {
        if (event.target.classList.contains('delete-btn')) {
            const button = event.target;
            const donationId = button.dataset.id;

            if (confirm('Are you sure you want to permanently delete this entry?')) {
                try {
                    const response = await fetch('delete_donation.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: donationId })
                    });
                    const result = await response.json();

                    if (result.message) {
                        // Remove the row from the table on success and reload data
                        loadDashboardData(); 
                    } else {
                        alert('Error: ' + result.error);
                    }
                } catch (error) {
                    console.error('Failed to delete donation:', error);
                    alert('A network error occurred.');
                }
            }
        }
    });

    // Initial load of all data
    loadDashboardData();
});