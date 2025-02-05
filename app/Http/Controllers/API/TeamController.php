<?php

namespace App\Http\Controllers\API;

use App\Actions\Jetstream\AddTeamMember;
use App\Actions\Jetstream\CreateTeam;
use App\Actions\Jetstream\DeleteTeam;
use App\Actions\Jetstream\InviteTeamMember;
use App\Actions\Jetstream\RemoveTeamMember;
use App\Actions\Jetstream\UpdateTeamName;
use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $teams = Team::latest();

            if ($request->has('search') && !empty($request->search)) {
                $teams = $teams->where('name', 'LIKE', '%'.$request->search.'%');
            }

            $teams = $teams->paginate(10);

            $code = 200;
            $message = 'Success';
            if (empty($teams)) {
                $message = 'Error';
                $code = 404;
            }

            return response()->json([
                'message'   => $message,
                'data'      => $teams,
            ], $code);
        } catch (\Exception $e) {
            return response()->json([
                'message'   => $e->getMessage(),
                'data'      => null,
            ], 500);
        }
    }

    /**
     * Invite member by email
     */
    public function invite(Request $request)
    {
        try {
            $user = Auth::user();
            $validation = Validator::make($request->all(), [
                'team_id'   => ['required', 'integer'],
                'email'     => ['required', 'string', 'email'],
                'role'      => ['required', 'string', 'in:admin,editor,developer,designer,manager'],
            ], [
                'role.in'   => "Allowed roles: admin | editor | developer | designer | manage"
            ]);

            if ($validation->fails()) {
                return response()->json([
                    'message'   => $validation->errors(),
                    'data'      => null,
                ], 400);
            }

            $email = $request->email;
            $role = $request->role;

            $team = Team::findOrFail($request->team_id);

            $invitation = new InviteTeamMember();
            $invitation->invite($user, $team, $email, $role);

            return response()->json([
                'message'   => 'success',
                'data'      => 'Invitation has sent to '.$email,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message'   => $e->getMessage(),
                'data'      => null,
            ], 500);
        }
    }

    /**
     * Create new team.
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            $validation = Validator::make($request->all(), [
                'name' => ['required', 'string'],
            ]);

            if ($validation->fails()) {
                return response()->json([
                    'message'   => $validation->errors(),
                    'data'      => null,
                ], 400);
            }

            $creation = new CreateTeam();
            $team = $creation->create($user, ['name' => $request->name]);

            return response()->json([
                'message'   => 'success',
                'data'      => $team,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message'   => $e->getMessage(),
                'data'      => null,
            ], 500);
        }
    }

    /**
     * Add existing user as team member.
     */
    public function add(Request $request)
    {
        try {
            $user = Auth::user();
            $validation = Validator::make($request->all(), [
                'team_id'   => ['required', 'integer'],
                'email'     => ['required', 'string', 'email'],
                'role'      => ['required', 'string', 'in:admin,editor,developer,designer,manager'],
            ], [
                'role.in'   => "Allowed roles: admin | editor | developer | designer | manage"
            ]);

            if ($validation->fails()) {
                return response()->json([
                    'message'   => $validation->errors(),
                    'data'      => null,
                ], 400);
            }

            $email = $request->email;
            $role = $request->role;

            $team = Team::findOrFail($request->team_id);

            $add = new AddTeamMember();
            $add->add($user, $team, $email, $role);

            return response()->json([
                'message'   => 'success',
                'data'      => 'Invitation has sent to '.$email,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message'   => $e->getMessage(),
                'data'      => null,
            ], 500);
        }
    }

    /**
     * Remove team member.
     */
    public function remove(Request $request)
    {
        try {
            $user = Auth::user();
            $validation = Validator::make($request->all(), [
                'user_id'   => ['required', 'integer'],
                'team_id'   => ['required', 'integer'],
            ]);

            if ($validation->fails()) {
                return response()->json([
                    'message'   => $validation->errors(),
                    'data'      => null,
                ], 400);
            }

            $teamMember = User::findOrFail($request->user_id);
            $team = Team::findOrFail($request->team_id);

            $remove = new RemoveTeamMember();
            $remove->remove($user, $team, $teamMember);

            return response()->json([
                'message'   => 'success',
                'data'      => $teamMember->name.' is removed from '.$team->name,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message'   => $e->getMessage(),
                'data'      => null,
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $user = Auth::user();
            $validation = Validator::make($request->all(), [
                'name' => ['required', 'string'],
            ]);

            if ($validation->fails()) {
                return response()->json([
                    'message'   => $validation->errors(),
                    'data'      => null,
                ], 400);
            }

            $team = Team::findOrFail($id);
            $update = new UpdateTeamName();
            $update->update($user, $team, ['name' => $request->name]);

            return response()->json([
                'message'   => 'success',
                'data'      => $team,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message'   => $e->getMessage(),
                'data'      => null,
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $team = Team::findOrFail($id);
            $delete = new DeleteTeam();
            $delete->delete($team);

            return response()->json([
                'message'   => 'success',
                'data'      => 'Team is deleted',
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message'   => $e->getMessage(),
                'data'      => null,
            ], 500);
        }
    }
}
