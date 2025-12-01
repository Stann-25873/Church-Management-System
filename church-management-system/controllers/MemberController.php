<?php

namespace Controllers;

use Core\Controller;
use Models\User;
use Services\EmailService;

class MemberController extends Controller
{
    public function index()
    {
        $this->checkAccess(['admin', 'pastor']);

        $userModel = new User();
        $members = $userModel->getAllMembers();

        $this->view('members', ['members' => $members]);
    }

    public function add()
    {
        $this->checkAccess(['admin']);
        $this->view('members', ['action' => 'add']);
    }

    public function store()
    {
        $this->checkAccess(['admin']);

        $firstName = $_POST['first_name'] ?? '';
        $lastName = $_POST['last_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'member';

        if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
            $_SESSION['error'] = 'All fields are required.';
            header('Location: /members/add');
            exit;
        }

        // Handle file upload
        $photoPath = '';
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $photoPath = $this->uploadPhoto($_FILES['photo']);
            if (!$photoPath) {
                $_SESSION['error'] = 'Invalid photo upload.';
                header('Location: /members/add');
                exit;
            }
        }

        $userModel = new User();
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $userId = $userModel->create([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'password' => $hashedPassword,
            'role' => $role,
            'photo' => $photoPath
        ]);

        if ($userId) {
            // Send email to admin
            $emailService = new EmailService();
            $adminEmail = 'admin@church.com'; // Replace with actual admin email
            $emailService->sendEmail($adminEmail, 'New Member Added', "A new member {$firstName} {$lastName} has been added.");

            // Send welcome email to member
            $emailService->sendEmail($email, 'Welcome to Our Church', "Welcome {$firstName}, you have been registered successfully!");

            $_SESSION['success'] = 'Member added successfully.';
            header('Location: /members');
            exit;
        } else {
            $_SESSION['error'] = 'Failed to add member.';
            header('Location: /members/add');
            exit;
        }
    }

    public function show($id)
    {
        $this->checkAccess(['admin', 'pastor', 'member']);

        $userModel = new User();
        $member = $userModel->findById($id);

        if (!$member) {
            $_SESSION['error'] = 'Member not found.';
            header('Location: /members');
            exit;
        }

        $this->view('profile', ['member' => $member]);
    }

    public function edit($id)
    {
        $this->checkAccess(['admin']);
        $userModel = new User();
        $member = $userModel->findById($id);
        $this->view('members', ['action' => 'edit', 'member' => $member]);
    }

    public function update($id)
    {
        $this->checkAccess(['admin']);

        $firstName = $_POST['first_name'] ?? '';
        $lastName = $_POST['last_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $role = $_POST['role'] ?? 'member';

        if (empty($firstName) || empty($lastName) || empty($email)) {
            $_SESSION['error'] = 'Required fields are missing.';
            header('Location: /members/' . $id . '/edit');
            exit;
        }

        $photoPath = '';
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $photoPath = $this->uploadPhoto($_FILES['photo']);
        }

        $userModel = new User();
        $updateData = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'role' => $role
        ];

        if ($photoPath) {
            $updateData['photo'] = $photoPath;
        }

        if ($userModel->update($id, $updateData)) {
            $_SESSION['success'] = 'Member updated successfully.';
        } else {
            $_SESSION['error'] = 'Failed to update member.';
        }

        header('Location: /members');
        exit;
    }

    public function delete($id)
    {
        $this->checkAccess(['admin']);

        $userModel = new User();
        if ($userModel->delete($id)) {
            $_SESSION['success'] = 'Member deleted successfully.';
        } else {
            $_SESSION['error'] = 'Failed to delete member.';
        }

        header('Location: /members');
        exit;
    }

    public function profile()
    {
        $this->checkAccess(['member', 'pastor', 'admin']);
        $userId = $_SESSION['user_id'];
        $userModel = new User();
        $member = $userModel->findById($userId);
        $this->view('profile', ['member' => $member]);
    }

    private function uploadPhoto($file)
    {
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        $maxSize = 2 * 1024 * 1024; // 2MB

        if (!in_array($file['type'], $allowedTypes) || $file['size'] > $maxSize) {
            return false;
        }

        $uploadDir = __DIR__ . '/../public/uploads/';
        $fileName = uniqid() . '_' . basename($file['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return '/uploads/' . $fileName;
        }

        return false;
    }

    private function checkAccess($roles)
    {
        if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], $roles)) {
            header('Location: /');
            exit;
        }
    }
}
