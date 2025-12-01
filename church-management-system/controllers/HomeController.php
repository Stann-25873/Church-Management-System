<?php

namespace Controllers;

use Core\Controller;
use Models\User;
use Models\Event;
use Models\Offering;
use Models\Sermon;

class HomeController extends Controller
{
    public function index()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /');
            exit;
        }

        try {
            // Initialize models
            $userModel = new User();
            $eventModel = new Event();
            $offeringModel = new Offering();
            $sermonModel = new Sermon();

            // Get statistics based on user role
            $stats = [];
            
            if ($_SESSION['user_role'] === 'admin') {
                $stats['total_members'] = $userModel->countByRole('member');
                $stats['total_pastors'] = $userModel->countByRole('pastor');
                
                $allEvents = $eventModel->getAll();
                $stats['upcoming_events'] = is_array($allEvents) ? count($allEvents) : 0;
                
                $offerings = $offeringModel->getAll();
                $offerings = is_array($offerings) ? $offerings : [];
                $stats['total_offerings'] = array_sum(array_column($offerings, 'amount'));
                $stats['recent_offerings'] = array_slice($offerings, 0, 5);
                
                $stats['recent_members'] = $userModel->getRecentMembers(5);
            } elseif ($_SESSION['user_role'] === 'pastor') {
                $stats['total_members'] = $userModel->countByRole('member');
                
                $allEvents = $eventModel->getAll();
                $stats['upcoming_events'] = is_array($allEvents) ? count($allEvents) : 0;
                
                $offerings = $offeringModel->getAll();
                $offerings = is_array($offerings) ? $offerings : [];
                $stats['total_offerings'] = array_sum(array_column($offerings, 'amount'));
                
                $allSermons = $sermonModel->getAll();
                $stats['my_sermons'] = is_array($allSermons) ? count($allSermons) : 0;
            } else {
                $allEvents = $eventModel->getAll();
                $stats['upcoming_events'] = is_array($allEvents) ? count($allEvents) : 0;
                $stats['my_offerings'] = 0;
            }

            // Get all data for the page
            $members = $userModel->getAll();
            $members = is_array($members) ? $members : [];
            
            $events = $eventModel->getAll();
            $events = is_array($events) ? $events : [];
            
            $offerings = $offeringModel->getAll();
            $offerings = is_array($offerings) ? $offerings : [];
            
            $sermons = $sermonModel->getAll();
            $sermons = is_array($sermons) ? $sermons : [];

            // Pass data to view
            $this->render('home', [
                'stats' => $stats,
                'members' => $members,
                'events' => $events,
                'offerings' => $offerings,
                'sermons' => $sermons
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo "Error: " . $e->getMessage();
        }
    }
}
