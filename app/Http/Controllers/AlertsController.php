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
use stdClass;

class AlertsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [];
        $status = ["Resolved", "Unresolved"];

        for ($i = 0; $i < 30; $i++) {
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
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
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
