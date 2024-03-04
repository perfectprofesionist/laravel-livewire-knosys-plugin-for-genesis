<?php
namespace App\Http\Controllers;

use Carbon\Exceptions\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Models\Settings;

class HomeController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /*
     * public function __construct()
     * {
     * $this->middleware('auth');
     * }
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        session([
            'conversationid' => request('conversationid')
        ]);
        if (session('access_token') == null) {
            return view('home');
        } else {
            return view('/homekno7', [
                'convId' => request('conversationid')
            ]);
        }
    }
}
