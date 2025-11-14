<?php
session_start();

// Get data from session (more secure)
$email = isset($_SESSION['email_status']['email']) ? htmlspecialchars($_SESSION['email_status']['email']) : '';
$emailSent = isset($_SESSION['email_status']['sent']) ? $_SESSION['email_status']['sent'] : '0';

// Clear the session data after reading
unset($_SESSION['email_status']);

// Fallback to GET for backward compatibility (but log this as suspicious)
if (empty($email) && isset($_GET['email'])) {
    $email = htmlspecialchars($_GET['email']);
    $emailSent = isset($_GET['sent']) ? $_GET['sent'] : '0';
    error_log('WARNING: Email status accessed via GET parameters - potential security issue');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h3>Email Status</h3>
                    </div>
                    <div class="card-body text-center">
                        <?php if ($emailSent === '1'): ?>
                            <div class="alert alert-success">
                                <h4>✅ Success!</h4>
                                <p>Welcome email has been sent to:</p>
                                <strong><?php echo $email; ?></strong>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning">
                                <h4>⚠️ Email Not Sent</h4>
                                <p>Customer was created, but welcome email failed to send to:</p>
                                <strong><?php echo $email; ?></strong>
                            </div>
                        <?php endif; ?>
                        
                        <div class="mt-4">
                            <a href="index.php" class="btn btn-primary">View All Customers</a>
                            <a href="create_customer.php" class="btn btn-success">Create Another Customer</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>