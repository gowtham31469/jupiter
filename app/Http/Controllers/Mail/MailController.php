<?php

namespace App\Http\Controllers\Mail;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Response;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
 
class MailController extends Controller
{
    public function sendFeedback(Request $request)
    {
    	$feedback_email = $request['feedback_email'];
    	$feedback       = $request['feedback'];
    	$attachments    = []; // empty array for all files to be attached.

    	if( $request->hasFile('image')) // Check if attachments attached or not.
    	{
    	    $files       = $request->file('image');
    	    foreach ($files as $file) 
    	    {
    	    	$destination_path='/storage/app/public/screenshots/';
		    	$path='storage/app/public/screenshots/';
		    	$Orname = $file->getClientOriginalName();
		    	$filename= time().'_'.preg_replace('/\s+/', '-', $Orname);
		    	$file->move($path,$filename);
		    	$destination_path_insert=$destination_path.$filename;
		    	
			    array_push($attachments,$destination_path_insert); // array of all files to be attached.
    	    }
    	    
    	}

    	Mail::send('mails.demo', ['feedback' => $feedback,'reporter' => $feedback_email], function ($message) use ($feedback_email,$attachments)
    	{
    	    $message->from($feedback_email);
    	    $message->subject('Jupiter Garments Accessories - Portal Feedback Email');
    	    foreach ($attachments as $key => $value) {
    	    	$message->attach(public_path($value));
    	    }
    	    $message->to('praveen4develop@gmail.com');
    	});
    }
}

 
