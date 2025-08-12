<?php
session_start();
include('db.php');
include('header.php');

// Security: Prevent direct access
if (!isset($_SESSION['selectedSeats']) || empty($_SESSION['selectedSeats'])) {
    header("Location: seat_layout.php");
    exit();
}

// Validate schedule_id
if (!isset($_SESSION['schedule_id']) || !is_numeric($_SESSION['schedule_id'])) {
    header("Location: index.php");
    exit();
}

// Fetch schedule details with prepared statement
$schedule_id = $_SESSION['schedule_id'];
$stmt = $conn->prepare("SELECT s.*, b.bus_name, b.bus_type FROM schedules s 
                       JOIN buses b ON s.bus_id = b.id 
                       WHERE s.id = ?");
$stmt->bind_param("i", $schedule_id);
$stmt->execute();
$result = $stmt->get_result();
$schedule_details = $result->fetch_assoc();

if (!$schedule_details) {
    header("Location: index.php");
    exit();
}

// Calculate total fare with validation
$selectedSeats = $_SESSION['selectedSeats'];
$selectedSeatTypes = $_SESSION['selectedSeatTypes'];
$seaterPrice = floatval($_SESSION['seater_price']);
$sleeperPrice = floatval($_SESSION['sleeper_price']);

$totalAmount = 0;
$seaterCount = 0;
$sleeperCount = 0;

foreach ($selectedSeats as $seat) {
    if (isset($selectedSeatTypes[$seat])) {
        if ($selectedSeatTypes[$seat] === 'sleeper') {
            $totalAmount += $sleeperPrice;
            $sleeperCount++;
        } else {
            $totalAmount += $seaterPrice;
            $seaterCount++;
        }
    }
}

// Store total amount in session
$_SESSION['totalAmount'] = $totalAmount;

// Generate unique transaction ID
$_SESSION['transaction_id'] = uniqid('TRANS_', true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Bus ticket payment page">
    <title>Payment - Bus Ticket Booking</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        :root {
            --primary-color: #007bff;
            --secondary-color: #0056b3;
            --error-color: #dc3545;
            --success-color: #28a745;
            --border-radius: 8px;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 20px;
            line-height: 1.6;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 25px;
            border-radius: var(--border-radius);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
        }

        .booking-summary {
            background: #f8f9fa;
            padding: 15px;
            border-radius: var(--border-radius);
            margin-bottom: 20px;
        }

        .booking-summary table {
            width: 100%;
            border-collapse: collapse;
        }

        .booking-summary td {
            padding: 8px;
            border-bottom: 1px solid #dee2e6;
        }

        .passenger-details-form {
            display: grid;
            gap: 20px;
        }

        .passenger-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: var(--border-radius);
            border: 1px solid #dee2e6;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: var(--border-radius);
            font-size: 16px;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
        }

        .error-message {
            color: var(--error-color);
            font-size: 14px;
            margin-top: 5px;
        }

        .payment-section {
            margin-top: 30px;
        }

        .payment-instructions {
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: var(--border-radius);
            border: 1px solid #dee2e6;
        }

        .payment-instructions h4 {
            color: var(--primary-color);
            margin: 0 0 12px 0;
            font-size: 1rem;
            display: flex;
            align-items: center;
        }

        .payment-instructions h4 i {
            margin-right: 8px;
        }

        .payment-steps {
            display: grid;
            gap: 8px;
        }

        .payment-step {
            position: relative;
            padding: 8px 8px 8px 35px;
            background: #fff;
            border-radius: 4px;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .payment-step::before {
            content: counter(step-counter);
            counter-increment: step-counter;
            position: absolute;
            left: 8px;
            top: 50%;
            transform: translateY(-50%);
            width: 22px;
            height: 22px;
            background: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: bold;
        }

        .payment-step strong {
            color: var(--primary-color);
            margin-right: 4px;
        }

        .security-note {
            margin-top: 10px;
            padding: 8px;
            background: #e8f5e9;
            border-radius: 4px;
            border-left: 3px solid #4caf50;
            font-size: 0.8rem;
            color: #2e7d32;
            display: flex;
            align-items: center;
        }

        .security-note i {
            margin-right: 8px;
            font-size: 1rem;
        }

        .upi-logo {
            max-width: 120px;
            margin-bottom: 20px;
        }

        .total-amount {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary-color);
            margin: 20px 0;
            padding: 10px;
            background: #e9ecef;
            border-radius: var(--border-radius);
        }

        .btn-primary {
            display: inline-block;
            padding: 12px 24px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s, transform 0.1s;
        }

        .btn-primary:hover {
            background: var(--secondary-color);
            transform: translateY(-1px);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .loading {
            display: none;
            text-align: center;
            padding: 20px;
        }

        .loading i {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            .passenger-card {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Complete Your Booking</h1>

        <!-- Booking Summary -->
        <div class="booking-summary">
            <h2>Booking Details</h2>
            <table>
                <tr>
                    <td><strong>Bus:</strong></td>
                    <td><?php echo htmlspecialchars($schedule_details['bus_name']); ?></td>
                </tr>
                <tr>
                    <td><strong>Type:</strong></td>
                    <td><?php echo htmlspecialchars($schedule_details['bus_type']); ?></td>
                </tr>
                <tr>
                    <td><strong>Selected Seats:</strong></td>
                    <td><?php echo htmlspecialchars(implode(', ', $selectedSeats)); ?></td>
                </tr>
                <tr>
                    <td><strong>Transaction ID:</strong></td>
                    <td><?php echo htmlspecialchars($_SESSION['transaction_id']); ?></td>
                </tr>
            </table>
        </div>

        <!-- Passenger Details Form -->
        <form id="bookingForm" onsubmit="return validateAndProceed(event)">
            <div class="passenger-details-form">
                <?php foreach ($selectedSeats as $index => $seat): ?>
                <div class="passenger-card">
                    <h3>Passenger <?php echo $index + 1; ?> (Seat <?php echo htmlspecialchars($seat); ?>)</h3>
                    
                    <div class="form-group">
                        <label for="name_<?php echo $index; ?>">Full Name</label>
                        <input type="text" 
                               class="form-control" 
                               id="name_<?php echo $index; ?>" 
                               name="passenger_name[]" 
                               pattern="[A-Za-z ]{1,25}" 
                               required>
                        <div class="error-message" id="nameError_<?php echo $index; ?>"></div>
                    </div>

                    <div class="form-group">
                        <label for="age_<?php echo $index; ?>">Age</label>
                        <input type="number" 
                               class="form-control" 
                               id="age_<?php echo $index; ?>" 
                               name="passenger_age[]" 
                               min="1" 
                               max="99" 
                               required>
                        <div class="error-message" id="ageError_<?php echo $index; ?>"></div>
                    </div>

                    <div class="form-group">
                        <label for="gender_<?php echo $index; ?>">Gender</label>
                        <select class="form-control" 
                                id="gender_<?php echo $index; ?>" 
                                name="passenger_gender[]" 
                                required>
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>
                <?php endforeach; ?>

                <div class="passenger-card">
                    <h3>Contact Information</h3>
                    <div class="form-group">
                        <label for="contact_number">Contact Number</label>
                        <input type="tel" 
                               class="form-control" 
                               id="contact_number" 
                               name="contact_number" 
                               pattern="[0-9]{10}" 
                               required>
                        <div class="error-message" id="contactError"></div>
                    </div>

                   
                </div>
            </div>

            <!-- Payment Section -->
            <div class="payment-section">
                <h2><i class="fas fa-credit-card"></i> Payment Details</h2>
                
                <div class="total-amount">
                    <i class="fas fa-receipt"></i> Total Amount: ₹<?php echo number_format($totalAmount, 2); ?>
                </div>

                <div class="payment-instructions">
                    <h4>
                        <i class="fas fa-info-circle"></i>
                        Quick Payment Guide
                    </h4>
                    
                    <div class="payment-steps">
                        <div class="payment-step">
                            <strong>Check Amount:</strong> Verify total of ₹<?php echo number_format($totalAmount, 2); ?>
                        </div>

                        <div class="payment-step">
                            <strong>UPI ID Format:</strong> Enter your ID (e.g., name@upi)
                        </div>

                        <div class="payment-step">
                            <strong>Pay:</strong> Click "Pay Securely" and approve in your UPI app
                        </div>
                    </div>

                    <div class="security-note">
                        <i class="fas fa-shield-alt"></i>
                        <span>Secure payment with end-to-end encryption</span>
                    </div>
                </div>

                <!-- UPI Payment Controls -->
                <div class="payment-controls">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e1/UPI-Logo-vector.svg/1200px-UPI-Logo-vector.svg.png" 
                         alt="UPI Logo" 
                         class="upi-logo">
                    
                    <div class="form-group">
                        <label for="upiId">UPI ID</label>
                        <input type="text" 
                               class="form-control" 
                               id="upiId" 
                               name="upi_id" 
                               placeholder="example@upi" 
                               pattern="[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+" 
                               required>
                        <div class="error-message" id="upiError"></div>
                    </div>

                    <button type="submit" class="btn-primary">
                        <i class="fas fa-lock"></i> Pay Securely
                    </button>

                    <div class="loading">
                        <i class="fas fa-spinner"></i> Processing Payment...
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function validateAndProceed(event) {
            event.preventDefault();
            let isValid = true;
            const errors = {};

            // Validate passenger names
            document.querySelectorAll('input[name="passenger_name[]"]').forEach((input, index) => {
                const name = input.value.trim();
                if (!/^[A-Za-z ]{1,25}$/.test(name)) {
                    errors[`nameError_${index}`] = "Name should contain only letters and spaces (max 25 characters)";
                    isValid = false;
                }
            });

            // Validate passenger ages
            document.querySelectorAll('input[name="passenger_age[]"]').forEach((input, index) => {
                const age = parseInt(input.value);
                if (isNaN(age) || age < 1 || age > 99) {
                    errors[`ageError_${index}`] = "Age must be between 1 and 99";
                    isValid = false;
                }
            });

            // Validate contact number
            const contactNumber = document.getElementById('contact_number').value;
            if (!/^[0-9]{10}$/.test(contactNumber)) {
                errors.contactError = "Please enter a valid 10-digit contact number";
                isValid = false;
            }

            // Validate email
            const email = document.getElementById('email').value;
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                errors.emailError = "Please enter a valid email address";
                isValid = false;
            }

            // Validate UPI ID
            const upiId = document.getElementById('upiId').value;
            if (!/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/.test(upiId)) {
                errors.upiError = "Please enter a valid UPI ID";
                isValid = false;
            }

            // Clear all previous error messages
            document.querySelectorAll('.error-message').forEach(elem => {
                elem.textContent = '';
            });

            // Display new error messages if any
            for (const [id, message] of Object.entries(errors)) {
                document.getElementById(id).textContent = message;
            }

            if (isValid) {
                // Show loading animation
                document.querySelector('.loading').style.display = 'block';
                document.querySelector('.btn-primary').style.display = 'none';

                // Simulate payment processing
                setTimeout(() => {
                    // Store form data in session storage
                    const formData = new FormData(document.getElementById('bookingForm'));
                    const formObject = {};
                    formData.forEach((value, key) => {
                        formObject[key] = value;
                    });
                    sessionStorage.setItem('bookingData', JSON.stringify(formObject));

                    // Redirect to payment processing page
                    window.location.href = 'process_payment.php';
                }, 1500);
            }

            return false;
        }
    </script>
</body>
</html>