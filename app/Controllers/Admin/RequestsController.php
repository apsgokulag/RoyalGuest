<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ServiceRequestModel;
use App\Models\GuestModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class RequestsController extends BaseController
{
    protected $requestModel;
    protected $guestModel;
    
    public function __construct()
    {
        $this->requestModel = new ServiceRequestModel();
        $this->guestModel = new GuestModel();
    }
    
    public function index()
    {
        $model = new ServiceRequestModel();
        $requests= $model->findAll();
        return view('admin/requests/index',['requests' => $requests]);
    }

    public function create()
    {
        $guestModel = new GuestModel();
        $userModel = new UserModel();

        $guests = $guestModel->findAll();  // For guest dropdown
        $users = $userModel->findAll();    // For assigned user dropdown

        return view('admin/requests/create', [
            'guests' => $guests,
            'users' => $users
        ]);
    }
    public function store()
    {
        $requestModel = new ServiceRequestModel();

        $data = [
            'guest_id'     => $this->request->getPost('guest_id'),
            'service_type' => $this->request->getPost('service_type'),
            'description'  => $this->request->getPost('description'),
            'priority'     => $this->request->getPost('priority'),
            'status'       => $this->request->getPost('status'),
            'assigned_to'  => $this->request->getPost('assigned_to'),
        ];

        if ($requestModel->insert($data)) {
            return redirect()->to(site_url('admin/requests'))->with('success', 'Service request created successfully.');
        } else {
            return redirect()->back()->withInput()->with('errors', $requestModel->errors());
        }
    }

    public function edit($id)
    {
        $model = new ServiceRequestModel();
        $request = $model->find($id);

        if (!$request) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Request not found");
        }

        return view('admin/requests/edit', ['request' => $request]);
    }

    public function update($id)
    {
        $model = new ServiceRequestModel();

        $status = $this->request->getPost('status');

        $model->update($id, ['status' => $status]);

        return redirect()->to('admin/requests')->with('success', 'Request status updated.');
    }
}