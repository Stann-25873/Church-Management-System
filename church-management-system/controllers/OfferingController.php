<?php

namespace Controllers;

use Core\Controller;
use Models\Offering;

class OfferingController extends Controller
{
    public function index()
    {
        $this->checkAccess(['admin', 'pastor']);

        $offeringModel = new Offering();
        $offerings = $offeringModel->getAll();

        $this->view('offerings', ['offerings' => $offerings]);
    }

    public function add()
    {
        $this->checkAccess(['admin', 'member']);
        $this->view('offerings', ['action' => 'add']);
    }

    public function store()
    {
        $this->checkAccess(['admin', 'member']);

        $amount = $_POST['amount'] ?? '';
        $memberId = $_SESSION['user_role'] === 'admin' ? ($_POST['member_id'] ?? '') : $_SESSION['user_id'];

        if (empty($amount) || !is_numeric($amount) || $amount <= 0) {
            $_SESSION['error'] = 'Valid amount is required.';
            header('Location: /offerings/add');
            exit;
        }

        $offeringModel = new Offering();
        $offeringId = $offeringModel->create([
            'member_id' => $memberId,
            'amount' => $amount
        ]);

        if ($offeringId) {
            $_SESSION['success'] = 'Offering added successfully.';
            header('Location: /offerings');
            exit;
        } else {
            $_SESSION['error'] = 'Failed to add offering.';
            header('Location: /offerings/add');
            exit;
        }
    }

    public function delete($id)
    {
        $this->checkAccess(['admin']);

        $offeringModel = new Offering();
        if ($offeringModel->delete($id)) {
            $_SESSION['success'] = 'Offering deleted successfully.';
        } else {
            $_SESSION['error'] = 'Failed to delete offering.';
        }

        header('Location: /offerings');
        exit;
    }

    private function checkAccess($roles)
    {
        if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], $roles)) {
            header('Location: /');
            exit;
        }
    }
}
