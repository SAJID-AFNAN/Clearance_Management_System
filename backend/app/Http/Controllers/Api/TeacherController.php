<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Approval;
use App\Models\ClearanceRequest;
use App\Models\Student;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    // Get teacher dashboard
    public function dashboard(Request $request)
    {
        $user = $request->user();
        $teacher = $user->teacher;

        // Determine what this teacher can approve
        $authorityTypes = [];

        if ($teacher->is_librarian) {
            $authorityTypes[] = 'library';
        }

        if ($teacher->is_hall_warden) {
            $authorityTypes[] = 'hall';
        }

        if ($teacher->is_lab_incharge) {
            $authorityTypes[] = 'lab';
        }

        // Department teachers can approve department requests
        if (!$teacher->is_librarian && !$teacher->is_hall_warden && !$teacher->is_lab_incharge) {
            $authorityTypes[] = 'department';
        }

        // Get pending approvals for this teacher
        $pendingApprovals = Approval::whereIn('authority_type', $authorityTypes)
            ->where('status', 'pending')
            ->with('clearanceRequest.student.user')
            ->get();

        // Get approval history
        $history = Approval::whereIn('authority_type', $authorityTypes)
            ->where('status', '!=', 'pending')
            ->with('clearanceRequest.student.user')
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'teacher' => $teacher,
            'authority_types' => $authorityTypes,
            'pending_approvals' => $pendingApprovals,
            'history' => $history,
        ]);
    }

    // Get student details for approval
    public function studentDetails($id)
    {
        $student = Student::with('user', 'department', 'hall')->findOrFail($id);

        return response()->json($student);
    }

    // Process approval (approve/reject)
    public function processApproval(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'comments' => 'nullable|string',
        ]);

        $approval = Approval::findOrFail($id);

        $approval->status = $request->status;
        $approval->comments = $request->comments;
        $approval->approved_at = now();

        // In real app, you'd store signature from teacher
        // $approval->signature_path = $teacher->signature_path;

        $approval->save();

        // Check if all approvals are done
        $clearanceRequest = $approval->clearanceRequest;
        $pendingCount = $clearanceRequest->approvals()
            ->where('status', 'pending')
            ->count();

        if ($pendingCount === 0) {
            $clearanceRequest->status = 'completed';
            $clearanceRequest->save();
        } else {
            $clearanceRequest->status = 'in_progress';
            $clearanceRequest->save();
        }

        return response()->json([
            'message' => 'Approval processed successfully',
            'approval' => $approval,
        ]);
    }
}
