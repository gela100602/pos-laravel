<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $category = Category::count();
        $product = Product::count();
        $customer = Customer::count();
        $supplier = Supplier::count();

        $start_date = date('Y-m-01');
        $end_date = date('Y-m-d');

        $data_date = array();
        $data_income = array();

        while (strtotime($start_date) <= strtotime($end_date)) {
            $data_date[] = (int) substr($start_date, 8, 2);

            $start_date = date('Y-m-d', strtotime("+1 day", strtotime($start_date)));
        }

        $start_date = date('Y-m-01');
        
        return view('admin.dashboard', compact('category', 'product', 'customer', 'supplier', 'start_date', 'end_date', 'data_date', 'data_income'));
        
    }
}