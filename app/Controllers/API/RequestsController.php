<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Models\GuestModel;
use CodeIgniter\HTTP\ResponseInterface;

class GuestsController extends BaseController
{
    protected $guestModel;
    
    public function __construct()
    {
        $this->guestModel = new GuestModel();
    }
    
    public function index()
    {
        try {
            $guests = $this->guestModel->findAll();
            
            return $this->response->setJSON([
                'status' => 'success',
                'data' => $guests
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to fetch guests'
            ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function show($id)
    {
        try {
            $guest = $this->guestModel->find($id);
            
            if (!$guest) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Guest not found'
                ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
            }
            
            return $this->response->setJSON([
                'status' => 'success',
                'data' => $guest
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to fetch guest'
            ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function create()
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'first_name' => 'required|max_length[50]',
            'last_name' => 'required|max_length[50]',
            'email' => 'required|valid_email|is_unique[guests.email]',
            'phone' => 'permit_empty|max_length[20]',
            'room_number' => 'permit_empty|max_length[10]',
            'check_in_date' => 'permit_empty|valid_date',
            'check_out_date' => 'permit_empty|valid_date',
            'status' => 'permit_empty|in_list[checked_in,checked_out,reserved]'
        ]);
        
        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validation->getErrors()
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }
        
        try {
            $data = [
                'first_name' => $this->request->getJSON()->first_name,
                'last_name' => $this->request->getJSON()->last_name,
                'email' => $this->request->getJSON()->email,
                'phone' => $this->request->getJSON()->phone ?? null,
                'room_number' => $this->request->getJSON()->room_number ?? null,
                'check_in_date' => $this->request->getJSON()->check_in_date ?? null,
                'check_out_date' => $this->request->getJSON()->check_out_date ?? null,
                'status' => $this->request->getJSON()->status ?? 'reserved'
            ];
            
            if ($this->guestModel->insert($data)) {
                $guestId = $this->guestModel->getInsertID();
                $guest = $this->guestModel->find($guestId);
                
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Guest created successfully',
                    'data' => $guest
                ])->setStatusCode(ResponseInterface::HTTP_CREATED);
            }
            
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to create guest'
            ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to create guest'
            ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function update($id)
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'first_name' => 'permit_empty|max_length[50]',
            'last_name' => 'permit_empty|max_length[50]',
            'email' => 'permit_empty|valid_email|is_unique[guests.email,id,' . $id . ']',
            'phone' => 'permit_empty|max_length[20]',
            'room_number' => 'permit_empty|max_length[10]',
            'check_in_date' => 'permit_empty|valid_date',
            'check_out_date' => 'permit_empty|valid_date',
            'status' => 'permit_empty|in_list[checked_in,checked_out,reserved]'
        ]);
        
        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validation->getErrors()
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }
        
        try {
            $guest = $this->guestModel->find($id);
            
            if (!$guest) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Guest not found'
                ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
            }
            
            $requestData = $this->request->getJSON(true);
            $data = [];
            
            // Only update fields that are provided
            $allowedFields = ['first_name', 'last_name', 'email', 'phone', 'room_number', 'check_in_date', 'check_out_date', 'status'];
            
            foreach ($allowedFields as $field) {
                if (isset($requestData[$field])) {
                    $data[$field] = $requestData[$field];
                }
            }
            
            if (empty($data)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'No data provided for update'
                ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
            }
            
            if ($this->guestModel->update($id, $data)) {
                $updatedGuest = $this->guestModel->find($id);
                
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Guest updated successfully',
                    'data' => $updatedGuest
                ]);
            }
            
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to update guest'
            ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to update guest'
            ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function delete($id)
    {
        try {
            $guest = $this->guestModel->find($id);
            
            if (!$guest) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Guest not found'
                ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
            }
            
            if ($this->guestModel->delete($id)) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Guest deleted successfully'
                ]);
            }
            
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to delete guest'
            ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to delete guest'
            ])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}