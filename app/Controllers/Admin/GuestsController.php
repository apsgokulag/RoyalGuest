<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\GuestModel;

class GuestsController extends BaseController
{
    protected $guestModel;
    
    public function __construct()
    {
        $this->guestModel = new GuestModel();
    }
    
    public function index()
    {
        $model = new GuestModel();
        $guests = $model->findAll();
        return view('admin/guests/index', ['guests' => $guests]);
    }

    public function create()
    {
        return view('admin/guests/create');
    }

    public function store()
    {
        $model = new GuestModel();

        $data = [
            'first_name'      => $this->request->getPost('first_name'),
            'last_name'       => $this->request->getPost('last_name'),
            'email'           => $this->request->getPost('email'),
            'phone'           => $this->request->getPost('phone'),
            'room_number'     => $this->request->getPost('room_number'),
            'check_in_date'   => $this->request->getPost('check_in_date'),
            'check_out_date'  => $this->request->getPost('check_out_date'),
            'status'          => $this->request->getPost('status'),
        ];

        $model->insert($data);
        return redirect()->to(site_url('admin/guests'))->with('success', 'Guest added successfully');
    }
    public function edit($id)
    {
        $guestModel = new \App\Models\GuestModel();
        $guest = $guestModel->find($id);

        if (!$guest) {
            return redirect()->to(site_url('admin/guests'))->with('error', 'Guest not found.');
        }

        return view('admin/guests/edit', ['guest' => $guest]);
    }

    public function update($id)
    {
        $guestModel = new \App\Models\GuestModel();

        // Get posted form data
        $data = $this->request->getPost();

        // Set validation rules
        $validation = \Config\Services::validation();
        $validation->setRules([
            'first_name'   => 'required',
            'last_name'    => 'required',
            'email'        => "required",
            'phone'        => 'required',
            'room_number'  => 'required',
            'status'       => 'required',
        ]);
        // Run validation
        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        // If validation passes, update the guest
        if (!$guestModel->update($id, $data)) {
            return redirect()->back()->withInput()->with('errors', $guestModel->errors());
        }

        return redirect()->to(site_url('admin/guests'))->with('success', 'Guest updated successfully.');
    }
    public function delete($id)
    {
        $guestModel = new \App\Models\GuestModel();
        $guestModel->delete($id);

        return redirect()->to(site_url('admin/guests'))->with('success', 'Guest deleted successfully.');
    }
}