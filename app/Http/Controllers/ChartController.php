<?php

namespace App\Http\Controllers;

use App\Trip;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    //graph data of donut graph
    public function getGraphData(Request $request)
    {
        try {

            if ($request->subtype == 'booking') {
                $column = 'booking_date';
                $tripType = 1;
            } else {
                $column = 'trip_date';
                $tripType = 2;
            }
            if ($request->type == 'donut') {
                $result = [];
                $indiscussion = 0;
                $accepted = 0;
                $rejected = 0;
                $list = Trip::where('trip_type', $tripType);
                //graph data of month filter
                if (!empty($request->month)) {
                    $list->whereRaw('MONTH(' . $column . ') = ' . $request->month);
                }
                //graph data of year filter
                if (!empty($request->year)) {
                    $list->whereRaw('YEAR(' . $column . ') = ' . $request->year);
                }
                //graph data of week filter
                if (!empty($request->week) && !empty($request->year) && !empty($request->month)) {
                    $query_date = $request->year . '-' . $request->month . '-01';
                    $Date = date('Y-m-01', strtotime($query_date));
                    if ($request->week == 2) {
                        $Date = date('Y-m-d', strtotime($Date . ' + 7 days'));
                    }
                    if ($request->week == 3) {
                        $Date = date('Y-m-d', strtotime($Date . ' + 14 days'));
                    }
                    if ($request->week == 4) {
                        $Date = date('Y-m-d', strtotime($Date . ' + 21 days'));
                    }
                    $fromDate = date('Y-m-d', strtotime($Date));
                    $todate = date('Y-m-d', strtotime($Date . ' + 7 days'));
                    $list->whereBetween($column, [$fromDate, $todate]);
                }
                $list = $list->get();
                foreach ($list as $value) {
                    if ($value->status == 1) {
                        $indiscussion++;
                    } elseif ($value->status == 2) {
                        $accepted++;
                    } else {
                        $rejected++;
                    }
                }
                $result['indiscussion'] = $indiscussion;
                $result['rejected'] = $rejected;
                $result['accepted'] = $accepted;
            }

            return response()->json(['status' => true, 'data' => $result]);
        } catch (\Exception $ex) {
            return response()->json(['status' => false, 'message' => $ex->getMessage()]);
        }
    }

    //graph data of bar graph
    public function getBarGraphData(Request $request)
    {
        try {
            $list = Trip::latest();
            if (!empty($request->month)) {
                $list->whereRaw('MONTH(created_at) = ' . $request->month);
            }
            if (!empty($request->year)) {
                $list->whereRaw('YEAR(created_at) = ' . $request->year);
            }
            if (!empty($request->week) && !empty($request->year) && !empty($request->month)) {
                $query_date = $request->year . '-' . $request->month . '-01';
                $Date = date('Y-m-01', strtotime($query_date));
                if ($request->week == 2) {
                    $Date = date('Y-m-d', strtotime($Date . ' + 7 days'));
                }
                if ($request->week == 3) {
                    $Date = date('Y-m-d', strtotime($Date . ' + 14 days'));
                }
                if ($request->week == 4) {
                    $Date = date('Y-m-d', strtotime($Date . ' + 21 days'));
                }
                $fromDate = date('Y-m-d', strtotime($Date));
                $todate = date('Y-m-d', strtotime($Date . ' + 7 days'));
                $list->whereBetween('created_at', [$fromDate, $todate]);
            }
            $list = $list->get();
            $result = [];
            $row = [];
            foreach ($list as $value) {
                $row['trip'] = $value->trip_name;
                $row['booking_cost'] = $value->booking_cost;
                $row['commission_cost'] = $value->commission_cost;
                $result[] = $row;
            }
            return response()->json(['status' => true, 'data' => $result]);
        } catch (\Exception $ex) {
            return response()->json(['status' => false, 'message' => $ex->getMessage()]);
        }
    }

    //graph data of line graph
    public function getLineGraphData(Request $request)
    {
        try {
            $result = [];
            $row = [];
            for ($i = 1; $i <= 12; $i++) {
                $monthNum  = $i;
                $dateObj   = \DateTime::createFromFormat('!m', $monthNum);
                $monthName = $dateObj->format('F');
                $sum = Trip::whereRaw('MONTH(created_at) = ' . $i)->sum('booking_cost');
                $row['month'] = $monthName;
                $row['month_num'] = $i;
                $row['booking_cost'] =  $sum;
                $result[] = $row;
            }
            return response()->json(['status' => true, 'data' => $result]);
        } catch (\Exception $ex) {
            return response()->json(['status' => false, 'message' => $ex->getMessage()]);
        }
    }
}
