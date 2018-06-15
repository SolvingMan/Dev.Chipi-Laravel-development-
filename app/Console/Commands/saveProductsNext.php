<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Http\API\NextAPI;

class saveProductsNext extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'product:next';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save products next';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \Log::info('I was here = ' . \Carbon\Carbon::now());
        $nextAPI = new NextAPI();
        $categories = \DB::table("nextSubSubcats")->get()->all();
        $nextAPI->saveProductsByCategoriesInDB($categories);
        \Log::info('I gone from here = ' . \Carbon\Carbon::now());
    }
}
