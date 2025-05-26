<?php
session_start();
include 'connect.php';

// Redirect if cart is empty
if(!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit();
}

// Fetch customer details if logged in
$customerDetails = [];
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $cid = $_SESSION['cid'];
    $query = "SELECT * FROM customer WHERE Cid = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $cid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if($row = mysqli_fetch_assoc($result)) {
        $customerDetails = $row;
    }
}

// Split customer name into first and last name if available
$firstName = '';
$lastName = '';
if(isset($customerDetails['C_name'])) {
    $nameParts = explode(' ', $customerDetails['C_name'], 2);
    $firstName = $nameParts[0];
    $lastName = isset($nameParts[1]) ? $nameParts[1] : '';
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="author" content="Untree.co">
  <link rel="shortcut icon=" href="favicon.png">

  <meta name="description" content="" />
  <meta name="keywords" content="bootstrap, bootstrap4" />

		<!-- Bootstrap CSS -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
		<link href="css/tiny-slider.css" rel="stylesheet">
		<link href="css/style.css" rel="stylesheet">
		<title>The Divine Decor </title>
        <style>
            .custom-navbar {
                padding-top: 15px !important;
                padding-bottom: 15px !important;
            }
            .custom-navbar .nav-link {
                padding-top: 5px !important;
                padding-bottom: 5px !important;
            }
            .custom-navbar .navbar-brand {
                padding: 0 !important;
            }
            .custom-navbar img {
                width: 20px;
                height: auto;
            }
            .hero {
                padding: 30px 0;
                padding-bottom: 20px;
            }
            /* Payment Method Styles */
            .payment-method {
                border-radius: 8px;
                transition: all 0.3s ease;
            }
            
            .payment-method-option {
                padding: 15px;
                margin-bottom: 10px;
                border: 1px solid #dee2e6;
                border-radius: 8px;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                gap: 10px;
            }
            
            .payment-method-option .payment-content {
                flex: 1;
            }
            
            .payment-method-option.available {
                background-color: #f8f9fa;
                border-color: #3b5d50;
            }
            
            .payment-method-option.available:hover {
                background-color: #3b5d50;
                color: white;
            }
            
            .payment-method-option.disabled {
                opacity: 0.6;
                background-color: #f8f9fa;
            }
            
            .payment-icon {
                font-size: 1.2rem;
                margin-right: 10px;
            }
            
            .payment-method-title {
                font-weight: 600;
                margin-bottom: 5px;
            }
            
            .payment-method-option .form-check-input {
                margin-top: 0;
            }
            .is-invalid {
                border-color: #dc3545 !important;
                background-color: #fff8f8;
            }

            .required-field::after {
                content: " *";
                color: #dc3545;
            }
        </style>
	</head>

	<body>
    <?php include 'includes/nav.php'; ?>

		<!-- Start Hero Section -->
			<div class="hero">
				<div class="container">
					<div class="row justify-content-between">
						<div class="col-lg-5">
							<div class="intro-excerpt">
								<p style="font-size:40px; font-weight:bold; color:#ffffff;">Checkout</p>
							</div>
						</div>
						<div class="col-lg-7">
							
						</div>
					</div>
				</div>
			</div>
		<!-- End Hero Section -->

		<div class="untree_co-section">
		    <div class="container">
		      <!-- <div class="row mb-5">
		        <div class="col-md-12">
		          <div class="border p-4 rounded" role="alert">
		            Returning customer? <a href="#">Click here</a> to login
		          </div>
		        </div>
		      </div> -->
		      <div class="row">
		        <div class="col-md-6 mb-5 mb-md-0">
		          <h2 class="h3 mb-3 text-black">Billing Details</h2>
		          <div class="p-3 p-lg-5 border bg-white">
		            <form id="checkoutForm" class="row g-3" method="POST">
		                <div class="form-group row">
    <div class="col-md-6">
        <label for="c_fname" class="text-black">First Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="c_fname" name="c_fname" 
               value="<?= htmlspecialchars($firstName) ?>">
    </div>
    <div class="col-md-6">
        <label for="c_lname" class="text-black">Last Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="c_lname" name="c_lname" 
               value="<?= htmlspecialchars($lastName) ?>">
    </div>
</div>

		                <div class="form-group row">
		                  <div class="col-md-12">
		                    <label for="c_companyname" class="text-black">Company Name (optional)	</label>
		                    <input type="text" class="form-control" id="c_companyname" name="c_companyname">
		                  </div>
		                </div>

<div class="form-group row">
    <div class="col-md-12">
        	<label for="c_address" class="text-black">Address <span class="text-danger">*</span></label>
			<input type="text" class="form-control" id="c_address" name="c_address" 
            placeholder="Street address" 
            value="<?= isset($customerDetails['Address']) ? htmlspecialchars($customerDetails['Address']) : '' ?>">
    </div>
</div>

		        <div class="form-group mt-3">
		            <input type="text" class="form-control" placeholder="Apartment, suite, unit etc. (optional)">
		        </div>

<div class="form-group row">
    <div class="col-md-6">
        <label for="c_state_country" class="text-black">City <span class="text-danger">*</span></label>
        <select class="form-control" id="c_state_country" name="c_state_country" required>
            <option value="Ahmedabad">Ahmedabad</option>
            <option value="Gandhinagar">Gandhinagar</option>
            <option value="Vadodara">Vadodara</option>
        </select>
    </div>
	<div class="col-md-6">
		<label for="c_postal_zip" class="text-black">Pincode / Area code <span class="text-danger">*</span></label>
		<input type="text" class="form-control" id="c_postal_zip" name="c_postal_zip" 
		       value="<?= isset($customerDetails['Area_id']) ? htmlspecialchars($customerDetails['Area_id']) : '' ?>">
	</div>

</div>
		                  
		                <div class="form-group row mb-5">
		                    <div class="col-md-6">
		                        <label for="c_email_address" class="text-black">Email Address <span class="text-danger">*</span></label>
		                        <input type="text" class="form-control" id="c_email_address" name="c_email_address" 
		                               value="<?= isset($customerDetails['Email']) ? htmlspecialchars($customerDetails['Email']) : '' ?>">
		                    </div>
		                    <div class="col-md-6">
		                        <label for="c_phone" class="text-black">Phone <span class="text-danger">*</span></label>
		                        <input type="text" class="form-control" id="c_phone" name="c_phone" 
		                               value="<?= isset($customerDetails['Contact_no']) ? htmlspecialchars($customerDetails['Contact_no']) : '' ?>">
		                    </div>
		            
		                    
		                </div>
		                
		            </form>
		          </div>
		        </div>
		        <div class="col-md-6">
		          <!-- Removed coupon code section -->
		          <div class="row mb-5">
		            <div class="col-md-12">
					<!-- order details start -->
		              <h2 class="h3 mb-3 text-black">Your Order</h2>
		              <div class="p-3 p-lg-5 border bg-white">
		                <table class="table site-block-order-table mb-5">
                    <thead>
                        <th>Product</th>
                        <th>Total</th>
                    </thead>
                    <tbody>
                    <?php 
                    $subtotal = 0;
                    if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                        foreach($_SESSION['cart'] as $pid => $item) {
                            $item_subtotal = $item['price'] * $item['quantity'];
                            $subtotal += $item_subtotal;
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']) ?> <strong class="mx-2">x</strong> <?= $item['quantity'] ?></td>
                            <td>₹<?= number_format($item_subtotal, 2) ?></td>
                        </tr>
                    <?php 
                        }
                    }
                    $total = $subtotal;
                    ?>
                    <tr>
                        <td class="text-black font-weight-bold"><strong>Order Total</strong></td>
                        <td class="text-black font-weight-bold"><strong>₹<?= number_format($total, 2) ?></strong></td>
                    </tr>
                    </tbody>
                </table>
		                <!-- Payment method options -->
		                <div class="border p-4 mb-3 payment-method">
    <h3 class="h6 mb-3 text-center">Select Payment Method</h3>
    
    <!-- UPI Section -->
    <div class="payment-method-option disabled">
        <input class="form-check-input" type="radio" name="payment_method" id="upi" value="upi" disabled>
        <div class="payment-content">
            <i class="fas fa-mobile-alt payment-icon"></i>
            <span class="payment-method-title">UPI Payment</span>
            <br>
            <small>This Option is Unavailable</small>
        </div>
    </div>

    <!-- Cards Section -->
    <div class="payment-method-option disabled">
        <input class="form-check-input" type="radio" name="payment_method" id="card" value="card" disabled>
        <div class="payment-content">
            <i class="fas fa-credit-card payment-icon"></i>
            <span class="payment-method-title">Credit/Debit Card</span>
            <br>
            <small>This Option is Unavailable</small>
        </div>
    </div>

    <!-- Net Banking Section -->
    <div class="payment-method-option disabled">
        <input class="form-check-input" type="radio" name="payment_method" id="netbanking" value="netbanking" disabled>
        <div class="payment-content">
            <i class="fas fa-university payment-icon"></i>
            <span class="payment-method-title">Net Banking</span>
            <br>
            <small>This Option is Unavailable</small>
        </div>
    </div>

    <!-- COD Section -->
    <div class="payment-method-option available">
        <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" checked>
        <div class="payment-content">
            <i class="fas fa-money-bill-wave payment-icon"></i>
            <span class="payment-method-title">Cash on Delivery</span>
            <br>
            <small class="text-success">✓ Available for this order</small>
        </div>
    </div>
</div>

		                <div class="row">
    <div class="col-md-12">
        <button type="button" name="place_order" id="place_order" class="btn btn-black btn-lg py-3 btn-block">Place Order</button>
    </div>
</div>
		            </div>
						
		          </div>

		        </div>
		      </div>
		      <!-- </form> -->
		    </div>
		  </div>
		</div>
		<!-- Start Footer Section -->
        <?php include 'includes/footer.php'; ?>
		<!-- End Footer Section -->	

		<script src="js/bootstrap.bundle.min.js"></script>
		<script src="js/tiny-slider.js"></script>
		<script src="js/custom.js"></script>
		<script>
document.getElementById('place_order').addEventListener('click', function() {
    const form = document.getElementById('checkoutForm');
    const formData = new FormData(form);
    
    // Required fields validation
    const requiredFields = {
        'c_fname': 'First Name',
        'c_lname': 'Last Name',
        'c_address': 'Address',
        'c_state_country': 'City',
        'c_postal_zip': 'Pincode',
        'c_email_address': 'Email Address',
        'c_phone': 'Phone'
    };

    let isValid = true;
    let errorMessage = '';

    // Check each required field
    for (let [fieldId, fieldName] of Object.entries(requiredFields)) {
        const field = document.getElementById(fieldId);
        const value = field.value.trim();
        
        // Remove any existing error styling
        field.classList.remove('is-invalid');
        
        if (!value) {
            field.classList.add('is-invalid');
            errorMessage += `${fieldName} is required\n`;
            isValid = false;
        }
    }

    // Validate email format
    const emailField = document.getElementById('c_email_address');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (emailField.value && !emailRegex.test(emailField.value)) {
        emailField.classList.add('is-invalid');
        errorMessage += 'Please enter a valid email address\n';
        isValid = false;
    }

    // Validate phone number (10 digits)
    const phoneField = document.getElementById('c_phone');
    const phoneRegex = /^\d{10}$/;
    if (phoneField.value && !phoneRegex.test(phoneField.value)) {
        phoneField.classList.add('is-invalid');
        errorMessage += 'Please enter a valid 10-digit phone number\n';
        isValid = false;
    }

    // Validate pincode (6 digits)
    const pincodeField = document.getElementById('c_postal_zip');
    const pincodeRegex = /^\d{6}$/;
    if (pincodeField.value && !pincodeRegex.test(pincodeField.value)) {
        pincodeField.classList.add('is-invalid');
        errorMessage += 'Please enter a valid 6-digit pincode\n';
        isValid = false;
    }

    if (!isValid) {
        alert(errorMessage);
        return;
    }

    // Add payment method to form data
    formData.append('payment_method', document.querySelector('input[name="payment_method"]:checked').value);

    // First update stock
   a
});

// Add visual feedback for required fields
document.querySelectorAll('input, select').forEach(element => {
    element.addEventListener('input', function() {
        if (this.value.trim()) {
            this.classList.remove('is-invalid');
        }
    });
});

		function getCityFromPincode(pincode) {
		    if(pincode.length === 6) {
		        // Pincode ranges for cities
		        const pincodeRanges = {
		            'Ahmedabad': { start: 380001, end: 380999 },
		        };
		        
		        const numPincode = parseInt(pincode);
		        let foundCity = '';
		        
		        for(const [city, range] of Object.entries(pincodeRanges)) {
		            if(numPincode >= range.start && numPincode <= range.end) {
		                foundCity = city;
		                break;
		            }
		        }
		        
		        if(foundCity) {
		            document.getElementById('c_state_country').value = foundCity;
		        } else {
		            alert('Sorry, we currently do not deliver to this pincode. Please select from Ahmedabad, Gandhinagar, or Vadodara.');
		            document.getElementById('c_postal_zip').value = '';
		            document.getElementById('c_state_country').value = '';
		        }
		    }
		}

		// Add city change handler to update pincode field
		document.getElementById('c_state_country').addEventListener('change', function() {
		    const cityPincodes = {
		        'Ahmedabad': '380001',
		        // 'Gandhinagar': '382001',
		        // 'Vadodara': '390001'
		    };
		    
		    const selectedCity = this.value;
		    if(selectedCity && !document.getElementById('c_postal_zip').value) {
		        document.getElementById('c_postal_zip').value = cityPincodes[selectedCity];
		    }
		});

		document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
    radio.addEventListener('change', function() {
        // Hide all collapse elements first
        document.querySelectorAll('.collapse').forEach(collapse => {
            collapse.classList.remove('show');
        });
        
        // Show the relevant options based on selection
        if (this.value === 'upi') {
            document.getElementById('upiOptions').classList.add('show');
        } else if (this.value === 'netbanking') {
            document.getElementById('netbankingOptions').classList.add('show');
        } else if (this.value === 'card') {
            document.getElementById('cardOptions').classList.add('show');
        }
    });
});
</script>
	</body>

</html>
