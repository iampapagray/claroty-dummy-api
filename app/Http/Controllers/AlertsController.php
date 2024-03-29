<?php

namespace App\Http\Controllers;

use App\Models\Alerts;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Str;
use ClaudioDekker\WordGenerator\Generator;
use DateTime;
use Illuminate\Support\Arr;
use stdClass;

class AlertsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = [];
        $status = ["Resolved", "Unresolved"];

        for ($i = 0; $i < 25; $i++) {
            $obj = new stdClass;

            $obj->id = $i;
            $obj->alert_type_name = Generator::generate(' ', 3);
            $obj->category = "Segmentation";
            $obj->detected_time = $this->randomDateInRange(Carbon::now()->startOfYear(), Carbon::now())->format('Y-m-d H:i:s');
            $obj->updated_time = $this->randomDateInRange(Carbon::now()->startOfYear(), Carbon::now())->format('Y-m-d H:i:s');
            $obj->devices_count = random_int(1, 50);
            $obj->unresolved_devices_count = random_int(1, 50);
            $obj->medical_devices_count = random_int(1, 15);
            $obj->iot_devices_count = random_int(0, 50);
            $obj->it_devices_count = random_int(0, 8);
            $obj->status = $status[array_rand($status)];

            array_push($data, $obj);
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
                $searchColumn = strtolower($value->alert_type_name);
                return Str::contains($searchColumn, $search);
            });

            return $this->paginate($searched);
        }

        return $this->paginate($data);
    }

    function randomDateInRange(DateTime $start, DateTime $end)
    {
        $randomTimestamp = mt_rand($start->getTimestamp(), $end->getTimestamp());
        $randomDate = new DateTime();
        $randomDate->setTimestamp($randomTimestamp);
        return $randomDate;
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Alerts $alerts)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Alerts $alerts)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alerts $alerts)
    {
        //
    }
}
