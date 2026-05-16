<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\BusinessService;

class Business extends BaseController
{
    protected BusinessService $businessService;

    public function __construct()
    {
        $this->businessService = new BusinessService();
        helper(['kobo']);
    }

    public function index()
    {
        $businesses = $this->businessService->getAll(20);
        $pager = $this->businessService->getPager();

        return view('admin/businesses/index', [
            'businesses' => $businesses,
            'pager'      => $pager,
            'title'      => 'Businesses',
        ]);
    }

    public function create()
    {
        return view('admin/businesses/create', [
            'title' => 'Create Business',
        ]);
    }

    public function store()
    {
        $rules = [
            'name'  => 'required|min_length[2]|max_length[255]',
            'phone' => 'required|min_length[5]|max_length[50]',
            'email' => 'required|valid_email',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name'   => $this->request->getPost('name'),
            'phone'  => $this->request->getPost('phone'),
            'email'  => $this->request->getPost('email'),
            'status' => $this->request->getPost('status') ?? 'active',
        ];

        $business = $this->businessService->create($data);

        return redirect()->to(base_url('admin/businesses'))
            ->with('success', 'Business "' . $business['name'] . '" created successfully');
    }

    public function edit($businessId)
    {
        $business = $this->businessService->getByBusinessId($businessId);

        if (!$business) {
            return redirect()->to(base_url('admin/businesses'))
                ->with('error', 'Business not found');
        }

        return view('admin/businesses/edit', [
            'business' => $business,
            'title'    => 'Edit Business',
        ]);
    }

    public function update($businessId)
    {
        $business = $this->businessService->getByBusinessId($businessId);

        if (!$business) {
            return redirect()->to(base_url('admin/businesses'))
                ->with('error', 'Business not found');
        }

        $rules = [
            'name'  => 'required|min_length[2]|max_length[255]',
            'phone' => 'required|min_length[5]|max_length[50]',
            'email' => 'required|valid_email',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name'   => $this->request->getPost('name'),
            'phone'  => $this->request->getPost('phone'),
            'email'  => $this->request->getPost('email'),
            'status' => $this->request->getPost('status') ?? 'active',
        ];

        $this->businessService->update($business['id'], $data);

        return redirect()->to(base_url('admin/businesses'))
            ->with('success', 'Business updated successfully');
    }

    public function delete($businessId)
    {
        $business = $this->businessService->getByBusinessId($businessId);

        if (!$business) {
            return redirect()->to(base_url('admin/businesses'))
                ->with('error', 'Business not found');
        }

        $this->businessService->delete($business['id']);

        return redirect()->to(base_url('admin/businesses'))
            ->with('success', 'Business deleted successfully');
    }
}
