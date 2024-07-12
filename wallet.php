use App\Models\WalletTransaction;

class WalletController extends Controller
{
    public function transaction(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'name_id' => 'required',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:debit,credit',
        ]);

        // Determine transaction type (debit or credit)
        $type = $request->input('type');
        $amount = $request->input('amount');
        $nameId = $request->input('name_id');

        // Initialize a new transaction
        $transaction = new WalletTransaction();
        $transaction->name_id = $nameId;

        if ($type === 'debit') {
            $transaction->debit = $amount;
        } else {
            $transaction->credit = $amount;
        }

        // Calculate total amount based on previous transaction
        $lastTransaction = WalletTransaction::where('name_id', $nameId)
                                            ->orderBy('created_at', 'desc')
                                            ->first();

        if ($lastTransaction) {
            if ($type === 'debit') {
                $transaction->total_amount = $lastTransaction->total_amount - $amount;
            } else {
                $transaction->total_amount = $lastTransaction->total_amount + $amount;
            }
        } else {
            // Initial transaction
            if ($type === 'debit') {
                $transaction->total_amount = -$amount;
            } else {
                $transaction->total_amount = $amount;
            }
        }

        // Save the transaction
        $transaction->save();

        // Redirect back with success message
        $message = ($type === 'debit') ? 'Amount debited successfully.' : 'Amount credited successfully.';
        return redirect()->back()->with('success', $message);
    }
}
<!--new code  -->
use App\Models\WalletTransaction;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function transaction(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'name_id' => 'required',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:debit,credit',
        ]);

        // Determine transaction type (debit or credit)
        $type = $request->input('type');
        $amount = $request->input('amount');
        $nameId = $request->input('name_id');

        // Initialize a new transaction
        $transaction = new WalletTransaction();
        $transaction->name_id = $nameId;

        if ($type === 'debit') {
            $transaction->debit = $amount;
        } else {
            $transaction->credit = $amount;
        }

        // Calculate total amount based on previous transaction
        $lastTransaction = WalletTransaction::where('name_id', $nameId)
                                            ->orderBy('created_at', 'desc')
                                            ->first();

        if ($lastTransaction) {
            if ($type === 'debit') {
                $transaction->total_amount = $lastTransaction->total_amount - $amount;
            } else {
                $transaction->total_amount = $lastTransaction->total_amount + $amount;
            }
        } else {
            // Initial transaction
            if ($type === 'debit') {
                $transaction->total_amount = -$amount;
            } else {
                $transaction->total_amount = $amount;
            }
        }

        // Save the transaction
        $transaction->save();

        // Prepare response data
        $message = ($type === 'debit') ? 'Amount debited successfully.' : 'Amount credited successfully.';
        $responseData = [
            'success' => true,
            'message' => $message,
        ];

        return response()->json($responseData);
    }
}



