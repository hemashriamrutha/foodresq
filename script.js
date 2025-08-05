document.addEventListener('DOMContentLoaded', function() {
    const donationForm = document.getElementById('donation-form');
    const formMessage = document.getElementById('form-message');
    const donationsList = document.getElementById('donations-list');

    // --- Function to fetch and display all available donations ---
    const fetchDonations = async () => {
        try {
            // Use the singular filename 'get_donation.php' as we fixed before
            const response = await fetch('get_donation.php');
            if (!response.ok) throw new Error('Network response was not ok.');
            
            const donations = await response.json();

            donationsList.innerHTML = ''; // Clear the current list

            if (donations.length === 0) {
                donationsList.innerHTML = '<p>No donations available at the moment. Be the first to donate!</p>';
                return;
            }

            donations.forEach(donation => {
                const item = document.createElement('div');
                item.className = 'donation-item';
                // NEW: Added a claim button with a data-id attribute to store the donation's ID
                item.innerHTML = `
                    <h3>${donation.food_name}</h3>
                    <p><strong>Category:</strong> ${donation.category}</p>
                    <p><strong>Quantity:</strong> ${donation.quantity}</p>
                    <p><strong>Expires On:</strong> ${donation.expiry_date}</p>
                    <p><strong>Location:</strong> ${donation.location}</p>
                    <button class="claim-btn" data-id="${donation.id}">Claim This Food</button>
                `;
                donationsList.appendChild(item);
            });

        } catch (error) {
            console.error('Error fetching donations:', error);
            donationsList.innerHTML = '<p>Sorry, there was an error loading donations.</p>';
        }
    };

    // --- Handle form submission for new donations ---
    donationForm.addEventListener('submit', async function(event) {
        event.preventDefault();

        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());

        try {
            const response = await fetch('donate.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.message) {
                formMessage.textContent = 'Thank you! Your donation has been listed.';
                formMessage.style.color = 'green';
                donationForm.reset();
                fetchDonations(); // Refresh the list to show the new donation
            } else {
                formMessage.textContent = 'An error occurred. Please try again.';
                formMessage.style.color = 'red';
            }
        } catch (error) {
            console.error('Error submitting form:', error);
            formMessage.textContent = 'A network error occurred.';
            formMessage.style.color = 'red';
        }
    });
    
    // --- NEW: Handle clicks on the "Claim" buttons using Event Delegation ---
    donationsList.addEventListener('click', async function(event) {
        // Check if the clicked element is a claim button
        if (event.target.classList.contains('claim-btn')) {
            const button = event.target;
            const donationId = button.dataset.id; // Get the ID from the data-id attribute

            if (confirm('Are you sure you want to claim this item?')) {
                try {
                    const response = await fetch('claim_donation.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: donationId })
                    });

                    const result = await response.json();

                    if (result.message) {
                        // On success, remove the item from the page visually
                        const itemToRemove = button.closest('.donation-item');
                        itemToRemove.remove();
                        alert('Item claimed successfully!');
                    } else {
                        alert('Error: ' + result.error);
                    }
                } catch (error) {
                    console.error('Error claiming donation:', error);
                    alert('A network error occurred while claiming the item.');
                }
            }
        }
    });

    // --- Initial fetch of donations when the page loads ---
    fetchDonations();
});