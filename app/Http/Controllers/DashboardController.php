<?php

namespace App\Http\Controllers;


use App\Models\Asset;
use App\Models\AssetPackage;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

 class DashboardController extends Controller
 {
     public function index(): View
     {
         /** @var User $user */
         $user = Auth::user();
 
         $data = [
             'total_assets'   => Asset::count(),
             'pending_assets' => Asset::where('status', 'pending')->count(),
             'asset_statuses' => Asset::select('status')
                 ->selectRaw('count(*) as total')
                 ->groupBy('status')
                 ->get(),
         ];
 
 
         return view('dashboard', $data);
     }
 }