<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Customer.php';
require_once __DIR__ . '/services/SimpleEmailService.php';

$database = new Database();
$db = $database->getConnection();
$customer = new Customer($db);
$emailService = new SimpleEmailService();

$name = $email = $phone = "";
$name_err = $email_err = "";

if ($_POST) {
    // Validate name
    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter a name.";
    } else {
        $customer->name = trim($_POST["name"]);
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email.";
    } else {
        $customer->email = trim($_POST["email"]);
    }

    $customer->phone = trim($_POST["phone"]);

    // Check if email already exists
    if (empty($name_err) && empty($email_err)) {
        if ($customer->emailExists()) {
            $email_err = "This email address is already registered.";
        }
    }

    // Check errors before inserting
    if (empty($name_err) && empty($email_err)) {
        if ($customer->create()) {
            // Send welcome email
            $emailSent = $emailService->sendWelcomeEmail($customer->email, $customer->name);
            
            if ($emailSent) {
                $_SESSION['success_message'] = "Customer created successfully and welcome email sent!";
            } else {
                $_SESSION['success_message'] = "Customer created successfully, but welcome email failed to send.";
            }
            
            // Store email status in session (more secure)
            $_SESSION['email_status'] = [
                'email' => $customer->email,
                'sent' => $emailSent ? '1' : '0'
            ];
            header("location: email_sent.php");
            exit();
        } else {
            echo "<div class='alert alert-danger'>Unable to create customer.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Create New Customer</h3>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name *</label>
                                <input type="text" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" 
                                       id="name" name="name" value="<?php echo $name; ?>" required>
                                <span class="invalid-feedback"><?php echo $name_err; ?></span>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" 
                                       id="email" name="email" value="<?php echo $email; ?>" required>
                                <span class="invalid-feedback"><?php echo $email_err; ?></span>
                            </div>
                            
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $phone; ?>">
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Create Customer</button>
                                <a href="index.php" class="btn btn-secondary">Back to List</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>