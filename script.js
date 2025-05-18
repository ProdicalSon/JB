// Show modal on button click
function initiatePayment() {
    document.getElementById("modal").style.display = "flex";
}

// Handle STK push (mockup for now)
function sendSTKPush() {
    const phone = document.getElementById("phone").value;
    const amount = document.getElementById("amount").value;
    const response = document.getElementById("response");

    if (!phone || !amount) {
        response.innerText = "Please fill in both phone number and amount.";
        return;
    }

    // Simulate sending payment request
    fetch("stk_push.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `phone=${phone}&amount=${amount}`
        })
        .then(res => res.text())
        .then(data => {
            response.innerText = data;
        })
        .catch(err => {
            response.innerText = "Error sending request. Try again.";
        });
}