namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\UniqueNumberMail;

class EmailController extends Controller
{
    public function getEmails(Request $request)
    {   
        $userEmail = $request->email;
        $type = $request->input('query');
        $emails = [];

        switch ($type) {
            case 'manager':
                $emails = DB::table('managers')->pluck('email');
                break;
            case 'employee':
                $emails = DB::table('employees')->pluck('email');
                break;
            case 'senior_manager':
                $emails = DB::table('senior_managers')->pluck('email');
                break;
        }

        // Generate unique 6-digit number
        $uniqueNumber = mt_rand(100000, 999999);

        // Send email to user's email address
        Mail::to($userEmail)->send(new UniqueNumberMail($uniqueNumber));

        // Send email to each fetched address
        foreach ($emails as $email) {
            Mail::to($email)->send(new UniqueNumberMail($uniqueNumber));
        }
        Contact::create([
            'email' => $userEmail,
            'query_type' => $type,
            'unique_number' => $uniqueNumber,
        ]);

        // Prepare success message for the user
        $message = "An email with a unique 6-digit number ($uniqueNumber) has been sent to your email address ($userEmail) and to others.";

        return response()->json(['message' => $message]);
    }
}
