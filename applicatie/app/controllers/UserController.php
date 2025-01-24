<?php
require_once __DIR__ . '/../data/userData.php';



class UserController {
    public function displayLogin() {
        require __DIR__ . '/../views/login.php';
    }

    public function displayRegister(){
        require __DIR__ . '/../views/register.php';
    }

    public function displayProfile() {
        if (!isset($_SESSION['username'])) {
            header('Location: /login');
            exit;
        }
    
        $username = $_SESSION['username'];
        $user = new User();
    
        try {
            $clientDetails = $user->getClientDetails($username);
            $clientOrders = $user->getClientOrders($username);
        } catch (Exception $e) {
            $errorMessage = "Fout bij het ophalen van gegevens: " . $e->getMessage();
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newFirstName = $_POST['first_name'] ?? $clientDetails['first_name'];
            $newLastName = $_POST['last_name'] ?? $clientDetails['last_name'];
            $newAddress = $_POST['address'] ?? $clientDetails['address'];
    
            try {
                $user->updateClientDetails($username, $newFirstName, $newLastName, $newAddress);
                $clientDetails = $user->getClientDetails($username); // Refresh data after update
                $successMessage = "Uw gegevens zijn succesvol bijgewerkt!";
            } catch (Exception $e) {
                $errorMessage = "Fout bij het bijwerken van gegevens: " . $e->getMessage();
            }
        }
    
        require_once __DIR__ . '/../views/profile.php';
    }
}
?>
