<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\GuestModel;
use App\Models\ServiceRequestModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $guestModel = new GuestModel();
        $requestModel = new ServiceRequestModel();
        
        $data = [
            'total_guests' => $guestModel->countAll(),
            'checked_in_guests' => $guestModel->where('status', 'checked_in')->countAllResults(),
            'total_requests' => $requestModel->countAll(),
            'pending_requests' => $requestModel->where('status', 'pending')->countAllResults(),
            'recent_requests' => $requestModel->getRequestsWithGuests()
        ];
        
        return view('admin/dashboard', $data);
    }

    public function getRecentRequests()
    {
        $requestModel = new ServiceRequestModel();
        $recentRequests = $requestModel->getRecentRequests();

        return $this->response->setJSON([
            'status' => true,
            'data' => $recentRequests
        ]);
    }
}
