<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use App\Models\Product;


class TransactionController extends Controller
{
    public function getTransactions() {
        return TransactionResource::collection(Transaction::latest()->paginate(10));
    }

    public function createTransaction(Request $request) {
        $this->validate($request, [
            'quantity' => 'required',
        ]);

        //GET THE PRODUCT
        $product = Product::findOrFail($request->id);

        if($product->quantity_brought < (int) $request->quantity) {
            return response()->json(['success' => false, 'message' => 'Insufficient Items Of  '.strtoupper($product->item_name)], 200);
        }else {
            $product->quantity_brought = $product->quantity_brought - (int) $request->quantity;

            $profit = ($product->selling_price * (int) $request->quantity) - ($product->buying_price * (int) $request->quantity);
    
            //ADD A TRANSACTION
            $transaction = Transaction::create([
                'product_id' => $request->id,
                'quantity_sold' => (int) $request->quantity,
                'amount' => (int) $request->quantity * $product->selling_price,
                'profit' => $profit
            ]);
            $product->save();
    
            if($product) {
                return response()->json(['success' => true, 'message' => 'Successfully Created A Transaction'], 200);
            }
        }
    }
    
    public function generateTransactionsInExcel(Request $request) {

        $this->validate($request, [
            'start_date' => 'required',
            'end_date' => 'required',
        ]);
        $transactions = Transaction::whereBetween('transactions.created_at', [
            $request->start_date, $request->end_date
           ])
           ->select('products.item_name', 'products.buying_price', 
           'products.selling_price', 'transactions.product_id',
           DB::raw('sum(products.buying_price * transactions.quantity_sold) as total_buying_price'), 
           DB::raw('sum(transactions.profit) as total_profits'), 
           DB::raw('sum(transactions.amount) as total_amount_sold'),
           DB::raw('sum(transactions.quantity_sold) as total_quantity_sold'))
           ->leftJoin('products', 'transactions.product_id', '=', 'products.id')
           ->groupBy('products.id')
           ->get();

           echo $transactions;
    }
}
