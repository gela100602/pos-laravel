<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Cashier;
use App\Models\Purchase;
use App\Models\Expense;
use App\Models\Sales;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // $category = Category::count();
        // $product = Product::count();
        // $supplier = Supplier::count();
        // $sales = Sales::sum('accepted');
        // $expense = Expense::sum('nominal');
        // $purchase = Purchase::sum('pay');

        $start_date = date('Y-m-01');
        $end_date = date('Y-m-d');

        $data_date = array();
        $data_income = array();

        while (strtotime($start_date) <= strtotime($end_date)) {
            $data_date[] = (int) substr($start_date, 8, 2);

            // $total_sales = Sales::where('created_at', 'LIKE', "%$start_date%")->sum('pay');
            // $total_purchase = Purchase::where('created_at', 'LIKE', "%$start_date%")->sum('pay');
            // $total_expense = Expense::where('created_at', 'LIKE', "%$start_date%")->sum('nominal');

            // $income = $total_sales - $total_purchase - $total_expense;
            // $data_income[] += $income;

            $start_date = date('Y-m-d', strtotime("+1 day", strtotime($start_date)));
        }

        $start_date = date('Y-m-01');
        
        return view('admin.dashboard', compact('category', 'product', 'supplier', 'sales', 'expense', 'purchase', 'start_date', 'end_date', 'data_date', 'data_income'));
        
    }
}