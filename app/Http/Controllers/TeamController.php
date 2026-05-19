<?php

namespace App\Http\Controllers;

use App\Models\Salesman;
use App\Models\SalesmanTransfer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeamController extends Controller
{
    public function index()
    {
        $role = auth()->user()->role;
        $salesmanId = auth()->user()->salesman_id;

        if ($role === 'admin') {
            // Admin sees all managers, supervisors and unassigned
            $managers = Salesman::where('level', 'manager')->with('subordinates.subordinates')->get();
            $supervisors = Salesman::where('level', 'supervisor')->whereNull('supervisor_id')->with('subordinates')->get();
            $unassignedSalesmen = Salesman::whereNull('supervisor_id')->where('level', 'sales')->get();
                
            return view('team.index-admin', compact('managers', 'supervisors', 'unassignedSalesmen'));
            
        } elseif ($role === 'manager') {
            // Manager sees their supervisors and sales
            $me = Salesman::with('subordinates.subordinates')->find($salesmanId);
            $supervisors = $me->subordinates;
            
            return view('team.index-manager', compact('me', 'supervisors'));

        } elseif ($role === 'supervisor') {
            // Supervisor sees their own team
            $me = Salesman::with('subordinates')->find($salesmanId);
            $team = $me->subordinates;
            
            // Other supervisors/managers to transfer to
            $otherSupervisors = Salesman::whereIn('level', ['supervisor', 'manager'])
                ->where('id', '!=', $salesmanId)
                ->get();
                
            $pendingTransfers = SalesmanTransfer::where('from_supervisor_id', $salesmanId)
                ->where('status', 'pending')
                ->with(['salesman', 'toSupervisor'])
                ->get();
                
            return view('team.index-supervisor', compact('me', 'team', 'otherSupervisors', 'pendingTransfers'));
            
        } elseif ($role === 'sales') {
            // Sales sees their supervisor and colleagues
            $me = Salesman::find($salesmanId);
            $supervisor = $me ? $me->supervisor : null;
            $colleagues = collect();
            
            if ($supervisor) {
                $colleagues = Salesman::where('supervisor_id', $supervisor->id)
                    ->where('id', '!=', $salesmanId)
                    ->get();
            }
            
            return view('team.index-sales', compact('me', 'supervisor', 'colleagues'));
        }

        abort(403);
    }

    public function requestTransfer(Request $request)
    {
        if (auth()->user()->role !== 'supervisor') {
            abort(403);
        }

        $validated = $request->validate([
            'salesman_id' => 'required|exists:salesmen,id',
            'to_supervisor_id' => 'nullable|string',
            'reason' => 'required|string',
        ]);

        $toSupervisorId = null;
        if ($request->filled('to_supervisor_id') && $request->to_supervisor_id !== 'leave') {
            $request->validate([
                'to_supervisor_id' => 'exists:salesmen,id|different:salesman_id',
            ]);
            $toSupervisorId = $request->to_supervisor_id;
        }

        $salesman = Salesman::findOrFail($validated['salesman_id']);
        
        if ($salesman->supervisor_id != auth()->user()->salesman_id) {
            return back()->with('error', 'Salesman ini bukan anggota tim Anda.');
        }

        // Check if there's already a pending request
        $existing = SalesmanTransfer::where('salesman_id', $validated['salesman_id'])
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return back()->with('error', 'Sudah ada pengajuan mutasi yang sedang diproses untuk salesman ini.');
        }

        SalesmanTransfer::create([
            'salesman_id' => $validated['salesman_id'],
            'from_supervisor_id' => auth()->user()->salesman_id,
            'to_supervisor_id' => $toSupervisorId,
            'requested_by' => auth()->user()->id,
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        return back()->with('success', 'Pengajuan mutasi berhasil dikirim dan menunggu ACC Admin.');
    }

    public function approvals()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $transfers = SalesmanTransfer::with(['salesman', 'fromSupervisor', 'toSupervisor', 'requestedBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('team.approvals', compact('transfers'));
    }

    public function processTransfer(Request $request, SalesmanTransfer $transfer)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
        ]);

        DB::transaction(function () use ($transfer, $validated) {
            $transfer->status = $validated['action'] === 'approve' ? 'approved' : 'rejected';
            $transfer->approved_by = auth()->user()->id;
            $transfer->save();

            if ($transfer->status === 'approved') {
                $salesman = Salesman::find($transfer->salesman_id);
                if ($salesman) {
                    $salesman->supervisor_id = $transfer->to_supervisor_id;
                    $salesman->area = null;
                    $salesman->city = null;
                    $salesman->save();
                }
            }
        });

        $message = $validated['action'] === 'approve' ? 'Mutasi berhasil disetujui (ACC).' : 'Mutasi ditolak.';
        return back()->with('success', $message);
    }

    public function forceTransfer(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'salesman_id' => 'required|exists:salesmen,id',
            'to_supervisor_id' => 'nullable|exists:salesmen,id',
        ]);

        $salesman = Salesman::findOrFail($validated['salesman_id']);
        $salesman->supervisor_id = $validated['to_supervisor_id'];
        $salesman->area = null;
        $salesman->city = null;
        $salesman->save();

        return back()->with('success', 'Anggota berhasil dipindahkan.');
    }
}
