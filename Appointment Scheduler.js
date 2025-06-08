document.addEventListener('DOMContentLoaded', function() {
    const serviceSelect = document.getElementById('service');
    const timeSelect = document.getElementById('time');
    const dateInput = document.getElementById('date');

    // Predefined time slots for each service
    const timeSlots = {
        'Counseling': ['10:00 AM', '11:30 AM', '2:00 PM', '4:00 PM'],
        'Therapy': ['9:00 AM', '12:00 PM', '2:30 PM', '5:00 PM'],
        'Support Session': ['8:30 AM', '1:00 PM', '3:30 PM', '6:00 PM']
    };

    // Function to update available time slots based on selected service
    function updateTimeSlots() {
        const selectedService = serviceSelect.value;
        const availableSlots = timeSlots[selectedService];
        timeSelect.innerHTML = ''; // Clear existing options

        availableSlots.forEach(slot => {
            const option = document.createElement('option');
            option.value = slot;
            option.textContent = slot;
            timeSelect.appendChild(option);
        });
    }

    // Update time slots when the page loads
    updateTimeSlots();

    // Update time slots when the service is changed
    serviceSelect.addEventListener('change', updateTimeSlots);

    // Form submission handling
    const form = document.getElementById('appointment-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        
        const service = serviceSelect.value;
        const counselor = document.getElementById('counselor').value;
        const date = dateInput.value;
        const time = timeSelect.value;

        alert(`Appointment Confirmed!\nService: ${service}\nCounselor: ${counselor}\nDate: ${date}\nTime: ${time}`);
    });
});
