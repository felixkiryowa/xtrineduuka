<?php

namespace App\Http\Controllers\Expense;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ExpenseResource;
use App\Models\Expense;

class ExpenseController extends Controller
{
     public function getAllExpenses() {
         return ExpenseResource::collection(Expense::latest()->paginate(10));
     }

     public function createExpense(Request $request) {
        $this->validate($request, [
            'amount' => 'required',
            'description' => 'required',
        ]);

        $expense = Expense::create([
            'amount' => $request->amount,
            'description' => $request->description
        ]);

        if($expense) {
            return response()->json(['success' => true, 'message' => 'Successfully Added an Expense'], 200);
        }
     }

     public function updateExpense(Request $request) {
        $this->validate($request, [
            'amount' => 'required',
            'description' => 'required',
        ]);

        $expense = Expense::findOrFail($request->id);
        $expense->amount = $request->amount;
        $expense->amount = $request->amount;
        $expense->save();
        return response()->json(['success' => true, 'message' => 'Successfully Updated an Expense'], 200);

     }

     public function generateExpensesReport(Request $request) {
        $this->validate($request, [
            'start_date' => 'required',
            'end_date' => 'required',
        ]);
        $expenses = Expense::whereBetween('expenses.created_at', [
            $request->start_date, $request->end_date
           ])
           ->select('expenses.*')
           ->get();

           echo $expenses;
     }

}
