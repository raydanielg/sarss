<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Traits\Auditable;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    use Auditable;

    public function index()
    {
        $regions = Region::withCount('districts')->orderBy('name')->get();
        return view('setup.regions.index', compact('regions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:regions,code',
        ]);
        $region = Region::create($data);
        $this->logAction('create', 'Regions', "Created region {$region->name}");
        return redirect()->route('regions.index')->with('status', 'Region created successfully.');
    }

    public function update(Request $request, Region $region)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:regions,code,' . $region->id,
        ]);
        $old = $region->toArray();
        $region->update($data);
        $this->logAction('update', 'Regions', "Updated region {$region->name}", $old, $region->toArray());
        return redirect()->route('regions.index')->with('status', 'Region updated successfully.');
    }

    public function destroy(Region $region)
    {
        $region->delete();
        $this->logAction('delete', 'Regions', "Deleted region {$region->name}");
        return redirect()->route('regions.index')->with('status', 'Region deleted.');
    }
}
