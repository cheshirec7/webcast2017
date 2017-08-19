<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\Backend\Access\User\UserRepository;
use App\Repositories\Backend\Access\Checkpoint\CheckpointRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $users;
    protected $checkpoints;

    /**
     * Create a new controller instance.
     *
     * @param  UserRepository $users
     * @param  CheckpointRepository $checkpoints
     */
    public function __construct(
        UserRepository $users,
        CheckpointRepository $checkpoints)
    {
        $this->users = $users;
        $this->checkpoints = $checkpoints;
    }

    /**
     * Display a the user template.
     *
     * @param  Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $checkpoints = $this->checkpoints->inOutOnly();
        return view('admin.users.index', ['checkpoints' => $checkpoints]);
    }

    /**
     * Display a list of all users.
     *
     * @param  Request $request
     * @return Response
     */
    public function show($id)
    {
        $users = $this->users->forDatatable();

        return Datatables::of($users)
            ->edit_column('perm_group', '@if($perm_group == 1) User @elseif($perm_group == 50) Admin @else Super @endif')
            ->edit_column('checkpoint_name', '@if($checkpoint_name) {{ $checkpoint_name }} @else All @endif')
            ->edit_column('enabled', '@if($enabled == 1) Yes @else No @endif')
            ->make();
    }

    /**
     * Store a user.
     *
     * @param  Request $request
     * @return Response
     */

    public function store(Request $request)
    {
        $user_id = intval($request->user_id);
        if ($user_id < 0)
            return response()->json(array('error' => true, 'needrefresh' => false, 'msg' => 'Invalid user id.'));

        $inputs = $request->only('user_id', 'username', 'email', 'enabled', 'permgroup', 'timingchkpt');
        foreach ($inputs as &$value) {
            $value = trim($value);
        }

        $username = filter_var($inputs['username'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_NO_ENCODE_QUOTES);
        $email = filter_var($inputs['email'], FILTER_SANITIZE_EMAIL);

        $rules = [
            'username' => 'required|min:2|max:255',
            'email' => 'required|email'
        ];

        $validator = Validator::make($inputs, $rules);
        if ($validator->fails())
            return response()->json(array('error' => true, 'needrefresh' => false, 'msg' => $validator->messages()->first()));

        $user_byemail = User::where('email', $email)
            ->where('id', '!=', $user_id)
            ->first();

        if ($user_byemail)
            return response()->json(array('error' => true, 'needrefresh' => true, 'msg' => 'That email address is in use.'));

        if ($user_id == 0) { //create new
            $user = new User();
        } else { //editing
            $user = User::find($user_id);
            if (!$user)
                return response()->json(array('error' => true, 'needrefresh' => true, 'msg' => 'Unable to edit. The user no longer exists.'));
        }

        $user->name = $username;
        $user->email = $email;
        $user->enabled = ($inputs["enabled"] == "Yes" ? 1 : 0);
        $user->timing_checkpoint_id = ($inputs["timingchkpt"] > 0 ? $inputs["timingchkpt"] : null);

        switch ($inputs["permgroup"]) {
            case "Admin":
                $user->perm_group = 50;
                break;
            case "Super":
                $user->perm_group = 100;
                break;
            default:
                $user->perm_group = 1;
                break;
        }

        if ($user->save()) {
            return response()->json(array('error' => false, 'idToFind' => $user->id, 'email' => $user->email));
        } else
            return response()->json(array('error' => true, 'needrefresh' => true, 'msg' => $user->errors()->first()));
    }

    /**
     * Destroy the given user.
     *
     * @param  id
     * @return Response
     */
    public function destroy($id)
    {
        $user_id = intval($id);

        if ($user_id <= 0)
            return response()->json(array('error' => TRUE, 'msg' => 'Invalid user id'));

        try {
            User::destroy($user_id);
            return response()->json(array('error' => FALSE));
        } catch (\Exception $e) {
            \Log::error($e->getMessage());

            if ($e->getCode() == 23000) {
                return response()->json(array(
                    'error' => TRUE,
                    'msg' => 'Unable to delete: data for the selected user exists in the database.'
                ));
            } else {
                return response()->json(array(
                    'error' => TRUE,
                    'msg' => $e->getMessage()
                ));
            }
        }
    }

    public function getUserDetailsJSON()
    {
        return $this->query()
            ->select('perm_group', 'timing_checkpoint_id')
                ->where('id', access()->id())
                ->get();
    }

}
