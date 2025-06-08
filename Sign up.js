document.getElementById('signupForm').addEventListener('submit', function (e) {
    // e.preventDefault(); // Remove this line to allow form submission
    const name = document.getElementById('full_name').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    if (name && email && password) {
        alert(`Welcome, ${name}! Your account has been successfully created.`);
    } else {
        alert('Please fill in all fields.');
    }
});
