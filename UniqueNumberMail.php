namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UniqueNumberMail extends Mailable
{
    use Queueable, SerializesModels;

    public $uniqueNumber;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($uniqueNumber)
    {
        $this->uniqueNumber = $uniqueNumber;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.unique_number')
                    ->with([
                        'uniqueNumber' => $this->uniqueNumber,
                    ])
                    ->subject('Your Unique Number');
    }
}
