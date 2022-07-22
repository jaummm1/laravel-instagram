<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use App\Notifications\DivMade;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class MakeDiv implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $num1;
    public $num2;
    public $userId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($num1, $num2, $userId)
    {
        $this->num1 = $num1;
        $this->num2 = $num2;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $div = ($this->num1 / $this->num2);

        if ($this->num2 === 0)
        {
            $title = 'Erro';
            $description = 'Divisão por zero';

            $user = User::find($this->userId);
            $user -> notify(new DivMade($title, $description));
            logger()->info('div =  ' . $div);
        }
        
            $title = 'Sucesso';
            $description = 'Div = ' . $div;  

            $user = User::find($this->userId);
            $user -> notify(new DivMade($title, $description));
            logger()->info('div =  ' . $div);

    }
}
