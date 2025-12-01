<?php

namespace Controllers;

use Core\Controller;
use Models\Sermon;

class SermonController extends Controller
{
    public function index()
    {
        $this->checkAccess(['admin', 'pastor']);

        $sermonModel = new Sermon();
        $sermons = $sermonModel->getAll();

        $this->view('sermons', ['sermons' => $sermons]);
    }

    public function add()
    {
        $this->checkAccess(['pastor']);
        $this->view('sermons', ['action' => 'add']);
    }

    public function store()
    {
        $this->checkAccess(['pastor']);

        $title = $_POST['title'] ?? '';
        $message = $_POST['message'] ?? '';

        if (empty($title) || empty($message)) {
            $_SESSION['error'] = 'Title and message are required.';
            header('Location: /sermons/add');
            exit;
        }

        $sermonModel = new Sermon();
        $sermonId = $sermonModel->create([
            'pastor_id' => $_SESSION['user_id'],
            'title' => $title,
            'message' => $message
        ]);

        if ($sermonId) {
            $_SESSION['success'] = 'Sermon added successfully.';
            header('Location: /sermons');
            exit;
        } else {
            $_SESSION['error'] = 'Failed to add sermon.';
            header('Location: /sermons/add');
            exit;
        }
    }

    public function delete($id)
    {
        $this->checkAccess(['pastor']);

        $sermonModel = new Sermon();
        $sermon = $sermonModel->findById($id);

        if (!$sermon || $sermon['pastor_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Unauthorized access.';
            header('Location: /sermons');
            exit;
        }

        if ($sermonModel->delete($id)) {
            $_SESSION['success'] = 'Sermon deleted successfully.';
        } else {
            $_SESSION['error'] = 'Failed to delete sermon.';
        }

        header('Location: /sermons');
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
