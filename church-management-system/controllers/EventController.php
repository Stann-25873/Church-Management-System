<?php

namespace Controllers;

use Core\Controller;
use Models\Event;

class EventController extends Controller
{
    public function index()
    {
        $this->checkAccess(['admin', 'pastor', 'member']);

        $eventModel = new Event();
        $events = $eventModel->getAll();

        $this->view('events', ['events' => $events]);
    }

    public function add()
    {
        $this->checkAccess(['admin']);
        $this->view('events', ['action' => 'add']);
    }

    public function store()
    {
        $this->checkAccess(['admin']);

        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $eventDate = $_POST['event_date'] ?? '';

        if (empty($title) || empty($eventDate)) {
            $_SESSION['error'] = 'Title and event date are required.';
            header('Location: /events/add');
            exit;
        }

        $eventModel = new Event();
        $eventId = $eventModel->create([
            'title' => $title,
            'description' => $description,
            'event_date' => $eventDate,
            'created_by' => $_SESSION['user_id']
        ]);

        if ($eventId) {
            $_SESSION['success'] = 'Event added successfully.';
            header('Location: /events');
            exit;
        } else {
            $_SESSION['error'] = 'Failed to add event.';
            header('Location: /events/add');
            exit;
        }
    }

    public function edit($id)
    {
        $this->checkAccess(['admin']);
        $eventModel = new Event();
        $event = $eventModel->findById($id);
        $this->view('events', ['action' => 'edit', 'event' => $event]);
    }

    public function update($id)
    {
        $this->checkAccess(['admin']);

        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $eventDate = $_POST['event_date'] ?? '';

        if (empty($title) || empty($eventDate)) {
            $_SESSION['error'] = 'Title and event date are required.';
            header('Location: /events/' . $id . '/edit');
            exit;
        }

        $eventModel = new Event();
        if ($eventModel->update($id, [
            'title' => $title,
            'description' => $description,
            'event_date' => $eventDate
        ])) {
            $_SESSION['success'] = 'Event updated successfully.';
        } else {
            $_SESSION['error'] = 'Failed to update event.';
        }

        header('Location: /events');
        exit;
    }

    public function delete($id)
    {
        $this->checkAccess(['admin']);

        $eventModel = new Event();
        if ($eventModel->delete($id)) {
            $_SESSION['success'] = 'Event deleted successfully.';
        } else {
            $_SESSION['error'] = 'Failed to delete event.';
        }

        header('Location: /events');
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
