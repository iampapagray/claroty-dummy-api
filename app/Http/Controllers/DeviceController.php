<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use stdClass;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = [];
        $scores = ["Low", "Medium", "High"];
        $models = ['Laser Printer', "Jet Printer", "Photocopier", "InkJet", "Air Conditioner"];

        for ($i = 0; $i < 30; $i++) {
            $vlan = array_map(
                function () {
                    return rand(0, 100);
                },
                array_fill(0, rand(0, 4), null)
            );

            $newObj = new stdClass;
            $newObj->uid = Str::uuid();
            $newObj->asset_id = Str::random(7);
            $newObj->risk_score = $scores[array_rand($scores)];
            $newObj->os_category = "Other";
            $newObj->labels = [];
            $newObj->device_type_family = "Printer";
            $newObj->vlan_list = $vlan;
            $newObj->mac_list = [
                "0a:65:bc:43:38:97"
            ];
            $newObj->device_subcategory = "General IoT";
            $newObj->retired = (bool)random_int(0, 1);
            $newObj->assignees = [];
            $newObj->network_list = ["Corporate"];
            $newObj->model = $models[array_rand($models)];
            $newObj->device_type = "Printer";
            $newObj->device_category = "IoT";
            $newObj->ip_list = ["10.100.3.80"];

            array_push($data, $newObj);
        }

        if (isset($request->filter) && $request->filter == 'level-0') {
            $filtered = Arr::where($data, function ($value, $key) {
                $searchColumn = strtolower($value->device_type_family);
                return Str::contains($searchColumn, 'level-0');
            });

            $data = $filtered;
        }

        if (isset($request->search)) {
            $search = strtolower($request->search);
            $searched = Arr::where($data, function ($value, $key) use ($search) {
                $searchColumn = strtolower($value->model);
                return Str::contains($searchColumn, $search);
            });

            return $this->paginate($searched);
        }

        return $this->paginate($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Device $device)
    {
        //
    }

    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : new Collection($items);

        // return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);

        $paginatedData = $items->slice(($page - 1) * $perPage, $perPage)->values()->all();

        return new LengthAwarePaginator(
            $paginatedData, // Sliced data for the current page
            count($items), // Total items count
            $perPage, // Items per page
            $page, // Current page
            ['path' => request()->url()] // Additional options for the paginator, like URL
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Device $device)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Device $device)
    {
        //
    }
}
