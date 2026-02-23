<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SalesExecutiveSalesHistory;
use App\Models\SalesOrderItems;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function index()
    {
        $data = SalesExecutiveSalesHistory::with(['get_sales_order', 'get_sales_exe'])->get();
        $product = SalesOrderItems::with(['get_sales_order', 'get_product'])->get();

        $totalSales = $data->sum('sales_amount');
        $totalProduct = $product->sum('quantity');

        try {

            // Start Sales Growth Calculation //
            $currentMonthSales = SalesExecutiveSalesHistory::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('sales_amount');

            $lastMonthSales = SalesExecutiveSalesHistory::whereMonth('created_at', Carbon::now()->subMonth()->month)
                ->whereYear('created_at', Carbon::now()->subMonth()->year)
                ->sum('sales_amount');

            $growth = $lastMonthSales > 0
                ? (($currentMonthSales - $lastMonthSales) / $lastMonthSales) * 100
                : 0;

            // End Sales Growth Calculation //

        } catch (\Exception $e) {
            $growth = 0;
        }

        return view('admin.index', compact('data', 'totalSales', 'growth'));
    }
}
