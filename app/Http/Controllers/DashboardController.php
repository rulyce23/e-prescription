<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Resep;
use App\Models\User;
use App\Models\ObatalkesM;
use App\Models\SignaM;

class DashboardController extends Controller
{
    /**
     * Display dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get statistics based on user role
        $stats = $this->getStatistics($user);
        
        // Get recent prescriptions based on user role
        $recentPrescriptions = $this->getRecentPrescriptions($user);
        
        // Get role-specific data
        $roleData = $this->getRoleSpecificData($user);
        
        // Get charts data
        $chartsData = $this->getChartsData($user);

        return view('dashboard', compact(
            'stats', 
            'recentPrescriptions', 
            'roleData',
            'chartsData'
        ));
    }

    /**
     * Get statistics based on user role
     */
    private function getStatistics($user)
    {
        $stats = [];

        if ($user->isAdmin()) {
            // Admin can see all statistics
            $stats = [
                'total_prescriptions' => Resep::count(),
                'pending_prescriptions' => Resep::pending()->count(),
                'approved_prescriptions' => Resep::approved()->count(),
                'completed_prescriptions' => Resep::completed()->count(),
                'rejected_prescriptions' => Resep::rejected()->count(),
                'total_users' => User::count(),
                'total_obatalkes' => ObatalkesM::count(),
                'total_signa' => SignaM::count(),
                'low_stock_items' => ObatalkesM::where('stok', '<=', 10)->where('stok', '>', 0)->count(),
                'out_of_stock_items' => ObatalkesM::where('stok', '<=', 0)->count()
            ];
        } elseif ($user->isDokter()) {
            // Dokter can see their own prescriptions and pending approvals
            $stats = [
                'my_prescriptions' => Resep::where('user_id', $user->id)->count(),
                'pending_approvals' => Resep::pending()->count(),
                'approved_by_me' => Resep::where('approved_by', $user->id)->count(),
                'rejected_by_me' => Resep::where('rejected_by', $user->id)->count(),
                'total_prescriptions' => Resep::count(),
                'total_users' => User::count(),
                'total_obatalkes' => ObatalkesM::count(),
                'total_signa' => SignaM::count()
            ];
        } elseif ($user->isApoteker()) {
            // Apoteker can see approved and completed prescriptions
            $stats = [
                'approved_prescriptions' => Resep::approved()->count(),
                'completed_prescriptions' => Resep::completed()->count(),
                'received_by_me' => Resep::where('received_by', $user->id)->count(),
                'total_prescriptions' => Resep::count(),
                'total_obatalkes' => ObatalkesM::count(),
                'low_stock_items' => ObatalkesM::where('stok', '<=', 10)->where('stok', '>', 0)->count(),
                'out_of_stock_items' => ObatalkesM::where('stok', '<=', 0)->count()
            ];
        } elseif ($user->isPasien()) {
            // Pasien can only see their own prescriptions
            $stats = [
                'my_prescriptions' => Resep::where('user_id', $user->id)->count(),
                'draft_prescriptions' => Resep::where('user_id', $user->id)->draft()->count(),
                'pending_prescriptions' => Resep::where('user_id', $user->id)->pending()->count(),
                'approved_prescriptions' => Resep::where('user_id', $user->id)->approved()->count(),
                'completed_prescriptions' => Resep::where('user_id', $user->id)->completed()->count(),
                'rejected_prescriptions' => Resep::where('user_id', $user->id)->rejected()->count()
            ];
        }

        return $stats;
    }

    /**
     * Get recent prescriptions based on user role
     */
    private function getRecentPrescriptions($user)
    {
        if ($user->isAdmin()) {
            return Resep::with(['user', 'approver', 'receiver'])
                       ->orderBy('created_at', 'desc')
                       ->limit(10)
                       ->get();
        } elseif ($user->isDokter()) {
            return Resep::with(['user', 'approver', 'receiver'])
                       ->where(function($query) use ($user) {
                           $query->where('user_id', $user->id)
                                 ->orWhere('status', 'pending')
                                 ->orWhere('approved_by', $user->id);
                       })
                       ->orderBy('created_at', 'desc')
                       ->limit(10)
                       ->get();
        } elseif ($user->isApoteker()) {
            return Resep::with(['user', 'approver', 'receiver'])
                       ->where(function($query) use ($user) {
                           $query->where('status', 'approved')
                                 ->orWhere('received_by', $user->id)
                                 ->orWhere('status', 'completed');
                       })
                       ->orderBy('created_at', 'desc')
                       ->limit(10)
                       ->get();
        } elseif ($user->isPasien()) {
            return Resep::with(['user', 'approver', 'receiver'])
                       ->where('user_id', $user->id)
                       ->orderBy('created_at', 'desc')
                       ->limit(10)
                       ->get();
        }

        return collect();
    }

    /**
     * Get role-specific data
     */
    private function getRoleSpecificData($user)
    {
        $data = [];

        if ($user->isAdmin() || $user->isApoteker()) {
            // Low stock alerts
            $data['lowStockAlerts'] = ObatalkesM::where('stok', '<=', 10)
                                              ->where('stok', '>', 0)
                                              ->orderBy('stok', 'asc')
                                              ->limit(5)
                                              ->get();
            
            // Out of stock items
            $data['outOfStockItems'] = ObatalkesM::where('stok', '<=', 0)
                                               ->orderBy('obatalkes_nama', 'asc')
                                               ->limit(5)
                                               ->get();
        }
        
        if ($user->isAdmin() || $user->isDokter()) {
            // Pending approvals
            $data['pendingApprovals'] = Resep::pending()
                                           ->with('user')
                                           ->orderBy('created_at', 'desc')
                                           ->limit(5)
                                           ->get();
        }
        
        if ($user->isApoteker()) {
            // Waiting to receive
            $data['waitingToReceive'] = Resep::approved()
                                           ->with('user')
                                           ->orderBy('approved_at', 'desc')
                                           ->limit(5)
                                           ->get();
            
            // Processing prescriptions
            $data['processingPrescriptions'] = Resep::processing()
                                                  ->with('user')
                                                  ->orderBy('received_at', 'desc')
                                                  ->limit(5)
                                                  ->get();
        }

        if ($user->isDokter()) {
            // My recent approvals
            $data['myRecentApprovals'] = Resep::where('approved_by', $user->id)
                                            ->with('user')
                                            ->orderBy('approved_at', 'desc')
                                            ->limit(5)
                                            ->get();
        }

        return $data;
    }

    /**
     * Get charts data
     */
    private function getChartsData($user)
    {
        $chartsData = [];

        if ($user->isAdmin()) {
            // Prescription status chart
            $chartsData['prescriptionStatus'] = [
                'labels' => ['Draft', 'Pending', 'Approved', 'Rejected', 'Processing', 'Completed'],
                'data' => [
                    Resep::draft()->count(),
                    Resep::pending()->count(),
                    Resep::approved()->count(),
                    Resep::rejected()->count(),
                    Resep::processing()->count(),
                    Resep::completed()->count()
                ]
            ];

            // Monthly prescriptions
            $chartsData['monthlyPrescriptions'] = $this->getMonthlyPrescriptions();

            // Top prescribed medicines
            $chartsData['topMedicines'] = $this->getTopMedicines();
        } elseif ($user->isDokter()) {
            // My prescription status chart
            $chartsData['myPrescriptionStatus'] = [
                'labels' => ['Draft', 'Pending', 'Approved', 'Rejected', 'Processing', 'Completed'],
                'data' => [
                    Resep::where('user_id', $user->id)->draft()->count(),
                    Resep::where('user_id', $user->id)->pending()->count(),
                    Resep::where('user_id', $user->id)->approved()->count(),
                    Resep::where('user_id', $user->id)->rejected()->count(),
                    Resep::where('user_id', $user->id)->processing()->count(),
                    Resep::where('user_id', $user->id)->completed()->count()
                ]
            ];

            // My monthly prescriptions
            $chartsData['myMonthlyPrescriptions'] = $this->getMonthlyPrescriptions($user->id);

            // Pending approvals chart
            $chartsData['pendingApprovals'] = [
                'labels' => ['Pending', 'Approved by Me', 'Rejected by Me'],
                'data' => [
                    Resep::pending()->count(),
                    Resep::where('approved_by', $user->id)->count(),
                    Resep::where('rejected_by', $user->id)->count()
                ]
            ];
        } elseif ($user->isApoteker()) {
            // Prescription processing chart
            $chartsData['prescriptionProcessing'] = [
                'labels' => ['Approved', 'Processing', 'Completed'],
                'data' => [
                    Resep::approved()->count(),
                    Resep::processing()->count(),
                    Resep::completed()->count()
                ]
            ];

            // Stock levels chart
            $chartsData['stockLevels'] = [
                'labels' => ['In Stock', 'Low Stock', 'Out of Stock'],
                'data' => [
                    ObatalkesM::where('stok', '>', 10)->count(),
                    ObatalkesM::where('stok', '<=', 10)->where('stok', '>', 0)->count(),
                    ObatalkesM::where('stok', '<=', 0)->count()
                ]
            ];

            // Monthly completed prescriptions
            $chartsData['monthlyCompleted'] = $this->getMonthlyCompletedPrescriptions();
        } elseif ($user->isPasien()) {
            // My prescription status chart
            $chartsData['myPrescriptionStatus'] = [
                'labels' => ['Draft', 'Pending', 'Approved', 'Rejected', 'Processing', 'Completed'],
                'data' => [
                    Resep::where('user_id', $user->id)->draft()->count(),
                    Resep::where('user_id', $user->id)->pending()->count(),
                    Resep::where('user_id', $user->id)->approved()->count(),
                    Resep::where('user_id', $user->id)->rejected()->count(),
                    Resep::where('user_id', $user->id)->processing()->count(),
                    Resep::where('user_id', $user->id)->completed()->count()
                ]
            ];

            // My monthly prescriptions
            $chartsData['myMonthlyPrescriptions'] = $this->getMonthlyPrescriptions($user->id);
        }

        return $chartsData;
    }

    /**
     * Get monthly prescriptions data
     */
    private function getMonthlyPrescriptions($userId = null)
    {
        $query = Resep::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                     ->whereYear('created_at', date('Y'));

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $monthlyData = $query->groupBy('month')
                           ->orderBy('month')
                           ->get()
                           ->keyBy('month');

        $data = [];
        for ($i = 1; $i <= 12; $i++) {
            $data[] = $monthlyData->get($i, (object)['count' => 0])->count;
        }

        return [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'data' => $data
        ];
    }

    /**
     * Get monthly completed prescriptions
     */
    private function getMonthlyCompletedPrescriptions()
    {
        $monthlyData = Resep::selectRaw('MONTH(completed_at) as month, COUNT(*) as count')
                           ->whereYear('completed_at', date('Y'))
                           ->whereNotNull('completed_at')
                           ->groupBy('month')
                           ->orderBy('month')
                           ->get()
                           ->keyBy('month');

        $data = [];
        for ($i = 1; $i <= 12; $i++) {
            $data[] = $monthlyData->get($i, (object)['count' => 0])->count;
        }

        return [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'data' => $data
        ];
    }

    /**
     * Get top prescribed medicines
     */
    private function getTopMedicines()
    {
        $topMedicines = DB::table('resep_items')
                         ->join('obatalkes_m', 'resep_items.obatalkes_id', '=', 'obatalkes_m.obatalkes_id')
                         ->selectRaw('obatalkes_m.obatalkes_nama, COUNT(*) as count')
                         ->groupBy('obatalkes_m.obatalkes_id', 'obatalkes_m.obatalkes_nama')
                         ->orderBy('count', 'desc')
                         ->limit(10)
                         ->get();

        return [
            'labels' => $topMedicines->pluck('obatalkes_nama')->toArray(),
            'data' => $topMedicines->pluck('count')->toArray()
        ];
    }

    /**
     * API method for dashboard
     */
    public function apiIndex()
    {
        $user = Auth::user();
        
        $stats = $this->getStatistics($user);
        $recentPrescriptions = $this->getRecentPrescriptions($user);
        $roleData = $this->getRoleSpecificData($user);
        $chartsData = $this->getChartsData($user);

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => $stats,
                'recent_prescriptions' => $recentPrescriptions,
                'role_data' => $roleData,
                'charts_data' => $chartsData
            ]
        ]);
    }
} 