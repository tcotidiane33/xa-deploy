<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;
use App\Http\Controllers\Controller;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Audit::with('user')->latest()->paginate(10);
        return view('admin.activities.index', compact('activities'));
    }
}
