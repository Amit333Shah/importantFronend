<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


class EmailController extends Controller
{
    public function emailSend(){
        return view('email');
    }
    public function emailPost(Request $request)
{
    // Validate the incoming request data
    $request->validate([
        'name' => 'required|string',
        'email' => 'required|email',
        'phone' => 'required|string',
        'department_id' => 'required|integer',
        'subject' => 'required|string',
    ]);

    // Retrieve inputs from the request
    $name = $request->input('name');
    $email = $request->input('email');
    $phone = $request->input('phone');
    $department_id = $request->input('department_id');
    $subject = $request->input('subject');

    // Retrieve department email
    $department = DB::table('departments')->where('id', $department_id)->first();
    if (!$department) {
        return redirect()->back()->with('error', 'Department not found.');
    }

    $uniqueNumber = substr(uniqid(), -6);

    // Generate unique number (you can adjust this as needed)
    $uniqueNumber = uniqid();

    // Send email
    try {
        Mail::send([], [], function ($message) use ($name, $email, $phone, $subject, $uniqueNumber, $departmentEmail) {
            $message->to([$email, $departmentEmail])
                    ->subject($subject)
                    ->html("
                        <h2>New Email</h2>
                        <p>Name: $name</p>
                        <p>Email: $email</p>
                        <p>Phone: $phone</p>
                        <p>Unique Number: $uniqueNumber</p>
                    ");
        });
        DB::table('contact_us')->insert([
            'name' => $name,
            'email_id' => $email,
            'department_email'=>$departmentEmail,
            'phoneno' => $phone,
            'department_id' => $department_id,
            'message' => $subject,
            'ticket_no' => $uniqueNumber,
            
        ]);

        return redirect()->back()->with('success', 'Email sent successfully. Unique Number: ' . $uniqueNumber);
    } catch (\Exception $e) {
        \Log::error('Failed to send email: ' . $e->getMessage());

        // Optionally, you can also return the error message to the view
        return redirect()->back()->with('error', 'Failed to send email. ' . $e->getMessage());    }
}
}
