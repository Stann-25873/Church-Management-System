<?php

namespace Controllers;

use Core\Controller;
use Models\User;
use Models\Event;
use Models\Offering;
use Models\Sermon;

class DashboardController extends Controller
{
    public function index()
    {
        $this->checkAccess(['admin', 'pastor', 'member']);

        $userModel = new User();
        $eventModel = new Event();
        $offeringModel = new Offering();
        $sermonModel = new Sermon();

        $stats = [];

        if ($_SESSION['user_role'] === 'admin') {
            $stats['total_members'] = $userModel->countByRole('member');
            $stats['total_pastors'] = $userModel->countByRole('pastor');
            $stats['upcoming_events'] = $eventModel->countUpcoming();
            $stats['total_offerings'] = $offeringModel->sumAll();
            $stats['recent_offerings'] = $offeringModel->getRecent(5);
            $stats['recent_members'] = $userModel->getRecentMembers(5);
        } elseif ($_SESSION['user_role'] === 'pastor') {
            $stats['total_members'] = $userModel->countByRole('member');
            $stats['upcoming_events'] = $eventModel->countUpcoming();
            $stats['total_offerings'] = $offeringModel->sumAll();
            $stats['recent_offerings'] = $offeringModel->getRecent(5);
            $stats['my_sermons'] = $sermonModel->countByPastor($_SESSION['user_id']);
        } else {
            $stats['upcoming_events'] = $eventModel->countUpcoming();
            $stats['my_offerings'] = $offeringModel->sumByMember($_SESSION['user_id']);
        }

        $this->view('dashboard', ['stats' => $stats]);
    }

    private function checkAccess($roles)
    {
        if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], $roles)) {
            header('Location: /');
            exit;
        }
    }
}
