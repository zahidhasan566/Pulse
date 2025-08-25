<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\CommonTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AgentController extends Controller
{
    use CommonTrait;
    public function index(Request $request)
    {
        try {
            $query = DB::table('Agents')
                ->select([
                    'AgentID',
                    'AgentCode',
                    'AgentName',
                    'AgentEmail',
                    'AgentPhone',
                    'AgentAddress',
                    'AgentNID',
                    'AgentPhoto',
                    'JoinDate',
                    'Status',
                    'CreatedAt'
                ])
                ->orderBy('CreatedAt', 'desc');

            // Add search functionality if needed
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('AgentName', 'like', "%{$search}%")
                        ->orWhere('AgentCode', 'like', "%{$search}%")
                        ->orWhere('AgentEmail', 'like', "%{$search}%")
                        ->orWhere('AgentPhone', 'like', "%{$search}%");
                });
            }

            $agents = $query->get();

            return response()->json([
                'status' => 'success',
                'data' => $agents
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error fetching agents: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created agent
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // Validation rules
            $validator = Validator::make($request->all(), [
                'AgentName' => 'required|string|max:100',
                'AgentEmail' => 'nullable|email|unique:agents,AgentEmail|max:100',
                'AgentPhone' => 'nullable|string|max:20',
                'AgentAddress' => 'nullable|string|max:255',
                'AgentNID' => 'nullable|string|max:50',
                'AgentPhoto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'JoinDate' => 'nullable|date'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Generate unique agent code
            $agentCode = $this->generateAgentCode();

            // Handle photo upload
            $photoPath = null;
            if ($request->hasFile('AgentPhoto')) {
                $photoPath = $request->file('AgentPhoto')->store('agents', 'public');
            }

            // Insert agent
            $agentId = DB::table('Agents')->insertGetId([
                'AgentCode' => $agentCode,
                'AgentName' => $request->AgentName,
                'AgentEmail' => $request->AgentEmail,
                'AgentPhone' => $request->AgentPhone,
                'AgentAddress' => $request->AgentAddress,
                'AgentNID' => $request->AgentNID,
                'AgentPhoto' => $photoPath,
                'JoinDate' => $request->JoinDate ? date('Y-m-d H:i:s', strtotime($request->JoinDate)) : now(),
                'Status' => 1,
                'CreatedAt' => now(),
                'UpdatedAt' => now()
            ]);
            //Insert  User Manager
            $user = new User();
            $user->UserID = $agentCode;
            $user->Name = $request->AgentName;
            $user->Email = $request->AgentEmail;
            $user->Mobile = $request->AgentPhone;
            $user->Address = '';
            $user->NID = $request->AgentNID;
            $user->RawPassword = ('123456');
            $user->Password = bcrypt('123456');
            $user->RoleID = 'Customer';
            $user->Status = 1;
            $user->CreatedBy = Auth::user()->UserID;
            $user->UpdatedBy = Auth::user()->UserID;
            $user->CreatedAt = \Illuminate\Support\Carbon::now()->format('Y-m-d H:i:s');
            $user->UpdatedAt = Carbon::now()->format('Y-m-d H:i:s');
            $user->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Agent created successfully',
                'data' => [
                    'AgentID' => $agentId,
                    'AgentCode' => $agentCode
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error creating agent: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified agent
     */
    public function show($id)
    {
        try {
            $agent = DB::table('agents')
                ->where('AgentID', $id)
                ->first();

            if (!$agent) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Agent not found'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $agent
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error fetching agent: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified agent
     */
    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'AgentID' => 'required|integer|exists:agents,AgentID',
                'AgentName' => 'required|string|max:100',
                'AgentEmail' => 'nullable|email|max:100|unique:agents,AgentEmail,' . $request->AgentID . ',AgentID',
                'AgentPhone' => 'nullable|string|max:20',
                'AgentAddress' => 'nullable|string|max:255',
                'AgentNID' => 'nullable|string|max:50',
                'AgentPhoto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'JoinDate' => 'nullable|date'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Get current agent data
            $currentAgent = DB::table('agents')->where('AgentID', $request->AgentID)->first();
            if (!$currentAgent) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Agent not found'
                ], 404);
            }

            // Handle photo upload
            $photoPath = $currentAgent->AgentPhoto;
            if ($request->hasFile('AgentPhoto')) {
                // Delete old photo
                if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                    Storage::disk('public')->delete($photoPath);
                }
                $photoPath = $request->file('AgentPhoto')->store('agents', 'public');
            }

            // Update agent
            DB::table('agents')
                ->where('AgentID', $request->AgentID)
                ->update([
                    'AgentName' => $request->AgentName,
                    'AgentEmail' => $request->AgentEmail,
                    'AgentPhone' => $request->AgentPhone,
                    'AgentAddress' => $request->AgentAddress,
                    'AgentNID' => $request->AgentNID,
                    'AgentPhoto' => $photoPath,
                    'JoinDate' => $request->JoinDate ? date('Y-m-d H:i:s', strtotime($request->JoinDate)) : $currentAgent->JoinDate,
                    'UpdatedAt' => now()
                ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Agent updated successfully'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error updating agent: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified agent
     */
    public function destroy($id)
    {
        try {
            $agent = DB::table('Agents')->where('AgentID', $id)->first();

            User::where('UserID', $agent->AgentCode)->delete();

            if (!$agent) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Agent not found'
                ], 404);
            }

            // Delete photo if exists
            if ($agent->AgentPhoto && Storage::disk('public')->exists($agent->AgentPhoto)) {
                Storage::disk('public')->delete($agent->AgentPhoto);
            }

            // Delete agent
            DB::table('agents')->where('AgentID', $id)->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Agent deleted successfully'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error deleting agent: ' . $e->getMessage()
            ], 500);
        }
    }
}
