<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Region;
use App\Traits\Auditable;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    use Auditable;

    public function index()
    {
        $districts = District::with('region')->withCount('schools')->orderBy('name')->get();
        $regions = Region::orderBy('name')->get();
        return view('setup.districts.index', compact('districts', 'regions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'region_id' => 'required|exists:regions,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:districts,code',
        ]);
        $district = District::create($data);
        $this->logAction('create', 'Districts', "Created district {$district->name}");
        return redirect()->route('districts.index')->with('status', 'District created successfully.');
    }

    public function update(Request $request, District $district)
    {
        $data = $request->validate([
            'region_id' => 'required|exists:regions,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:districts,code,' . $district->id,
        ]);
        $old = $district->toArray();
        $district->update($data);
        $this->logAction('update', 'Districts', "Updated district {$district->name}", $old, $district->toArray());
        return redirect()->route('districts.index')->with('status', 'District updated successfully.');
    }

    public function destroy(District $district)
    {
        $district->delete();
        $this->logAction('delete', 'Districts', "Deleted district {$district->name}");
        return redirect()->route('districts.index')->with('status', 'District deleted.');
    }
}
