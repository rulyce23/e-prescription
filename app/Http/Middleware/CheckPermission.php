<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated'
                ], 401);
            }
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Check if user has the required permission
        switch ($permission) {
            case 'create.prescriptions':
                if ((method_exists($user, 'canCreatePrescription') && $user->canCreatePrescription()) || (method_exists($user, 'canInputResep') && $user->canInputResep())) {
                    return $next($request);
                }
                break;
            case 'view.prescriptions':
                // All authenticated users can view prescriptions (with role-based filtering in controller)
                return $next($request);
            case 'edit.prescriptions':
                if (method_exists($user, 'canEditPrescription') && $user->canEditPrescription()) {
                    return $next($request);
                }
                break;
            case 'delete.prescriptions':
                if (method_exists($user, 'canDeletePrescription') && $user->canDeletePrescription()) {
                    return $next($request);
                }
                break;
            case 'approve.prescriptions':
                if (method_exists($user, 'canApprovePrescription') && $user->canApprovePrescription()) {
                    return $next($request);
                }
                break;
            case 'receive.prescriptions':
                if (method_exists($user, 'canReceivePrescription') && $user->canReceivePrescription()) {
                    return $next($request);
                }
                break;
            case 'manage.master.data':
                if (method_exists($user, 'canManageMasterData') && $user->canManageMasterData()) {
                    return $next($request);
                }
                break;
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied. Insufficient permissions.'
            ], 403);
        }

        return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki izin untuk mengakses halaman ini.');
    }
} 