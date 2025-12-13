<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use App\Models\Event;
use Illuminate\Support\Facades\DB;

class EarningsController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Obtener todos los pagos de eventos del organizador
        $payments = Payment::whereHas('event', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with(['event', 'user'])
        ->orderBy('paid_at', 'desc')
        ->get();

        // Calcular estadísticas
        $totalEarnings = $payments->sum('organizer_amount');
        $totalTransactions = $payments->count();
        $totalPlatformFees = $payments->sum('platform_fee');
        
        // Eventos con más ingresos
        $topEvents = Payment::whereHas('event', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->select('event_id', DB::raw('SUM(organizer_amount) as total_earnings'), DB::raw('COUNT(*) as sales_count'))
        ->groupBy('event_id')
        ->orderBy('total_earnings', 'desc')
        ->limit(5)
        ->with('event')
        ->get();

        // Ingresos por mes (últimos 6 meses)
        $monthlyEarnings = Payment::whereHas('event', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->select(
            DB::raw('YEAR(paid_at) as year'),
            DB::raw('MONTH(paid_at) as month'),
            DB::raw('SUM(organizer_amount) as total'),
            DB::raw('COUNT(*) as count')
        )
        ->where('paid_at', '>=', now()->subMonths(6))
        ->groupBy('year', 'month')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->get();

        return view('earnings.index', compact(
            'payments',
            'totalEarnings',
            'totalTransactions',
            'totalPlatformFees',
            'topEvents',
            'monthlyEarnings'
        ));
    }
}