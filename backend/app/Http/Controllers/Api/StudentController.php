<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\ClearanceRequest;
use App\Models\Approval;
use App\Models\Department;
use App\Models\Hall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    // Get student dashboard data
    public function dashboard(Request $request)
    {
        $user = $request->user();
        $student = $user->student;

        // Load relationships
        $student->load('department', 'hall');

        // Get active clearance request (if any)
        $activeRequest = ClearanceRequest::where('student_id', $student->id)
            ->whereIn('status', ['pending', 'in_progress'])
            ->with('approvals')
            ->first();

        // Get clearance history
        $history = ClearanceRequest::where('student_id', $student->id)
            ->where('status', '!=', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'profile' => $student,
            'activeRequest' => $activeRequest,
            'history' => $history,
        ]);
    }

    // Complete student profile
    public function completeProfile(Request $request)
    {
        $user = $request->user();
        $student = $user->student;

        $request->validate([
            'registration_no' => 'required|string|unique:students,registration_no,' . $student->id,
            'academic_session' => 'required|string',  
            'department_id' => 'required|exists:departments,id',
            'hall_id' => 'required|exists:halls,id',
            'phone' => 'required|string',
            'photo' => 'nullable|image|max:2048',
            'signature' => 'nullable|image|max:1024',
        ]);

        $student->registration_no = $request->input('registration_no');
        $student->session = $request->input('academic_session');  
        $student->department_id = $request->input('department_id');
        $student->hall_id = $request->input('hall_id');
        $student->phone = $request->input('phone');

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos', 'public');
            $student->photo_path = $path;
        }

        // Handle signature upload
        if ($request->hasFile('signature')) {
            $path = $request->file('signature')->store('signatures', 'public');
            $student->signature_path = $path;
        }

        $student->profile_completed = true;
        $student->save();

        return response()->json(['message' => 'Profile completed successfully', 'student' => $student]);
    }

    // Submit clearance request
    public function submitRequest(Request $request)
    {
        $user = $request->user();
        $student = $user->student;

        // Check if profile is complete
        if (!$student->profile_completed) {
            return response()->json(['message' => 'Please complete your profile first'], 400);
        }

        // Check if already has pending request
        $existingRequest = ClearanceRequest::where('student_id', $student->id)
            ->whereIn('status', ['pending', 'in_progress'])
            ->first();

        if ($existingRequest) {
            return response()->json(['message' => 'You already have a pending request'], 400);
        }

        // Generate request number
        $requestNo = 'CLR-' . date('Y') . '-' . str_pad(
            ClearanceRequest::whereYear('created_at', date('Y'))->count() + 1,
            5,
            '0',
            STR_PAD_LEFT
        );

        // Create clearance request
        $clearanceRequest = ClearanceRequest::create([
            'student_id' => $student->id,
            'request_no' => $requestNo,
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        // Create required approvals
        // Department approval
        Approval::create([
            'clearance_request_id' => $clearanceRequest->id,
            'authority_type' => 'department',
            'status' => 'pending',
        ]);

        // Lab approval (if department has lab)
        if ($student->department->has_lab) {
            Approval::create([
                'clearance_request_id' => $clearanceRequest->id,
                'authority_type' => 'lab',
                'status' => 'pending',
            ]);
        }

        // Library approval
        Approval::create([
            'clearance_request_id' => $clearanceRequest->id,
            'authority_type' => 'library',
            'status' => 'pending',
        ]);

        // Hall approval
        Approval::create([
            'clearance_request_id' => $clearanceRequest->id,
            'authority_type' => 'hall',
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Request submitted successfully',
            'request' => $clearanceRequest->load('approvals')
        ]);
    }

    // Get clearance request status
    public function requestStatus($id)
    {
        $request = ClearanceRequest::with('approvals', 'student.user')
            ->findOrFail($id);

        return response()->json($request);
    }

    // Get departments list
    public function departments()
    {
        return response()->json(Department::all());
    }

    // Get halls list
    public function halls()
    {
        return response()->json(Hall::all());
    }
}
