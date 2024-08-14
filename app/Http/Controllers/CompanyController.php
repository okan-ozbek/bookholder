<?php

namespace App\Http\Controllers;

use App\Helpers\KVKHelper;
use App\Http\Requests\Companies\CreateCompanyRequest;
use App\Http\Requests\Companies\FindKVKRequest;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

final class CompanyController extends Controller
{
    public function index(): InertiaResponse
    {
        return Inertia::render('Admin/Company/Index', [
            'companies' => Company::all()
        ]);
    }

    public function create(): InertiaResponse
    {
        if (Session::has('company')) {
            return Inertia::render('Admin/Company/Create', [
                'company' => Session::get('company')
            ]);
        }

        return Inertia::render('Admin/Company/Create');
    }

    public function store(CreateCompanyRequest $request): RedirectResponse
    {
        Company::create([
            'name' => $request->name,
            'kvk' => $request->kvk,
            'street_address' => $request->street_address,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'country' => $request->country,
        ]);

        return redirect()->route('companies.index');
    }

    public function destroy(Company $company): SymfonyResponse
    {
        $company->delete();

        return redirect()->route('companies.index');
    }

    public function find(): InertiaResponse
    {
        return Inertia::render('Admin/Company/Find');
    }

    public function found(FindKVKRequest $request): RedirectResponse
    {
        return KVKHelper::redirectOnSuccess($request->kvk_to_find);
    }
}
