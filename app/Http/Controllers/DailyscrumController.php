<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\dailyscrum;
use App\User;
use DB;
use Illuminate\Support\Facades\Validator;

class DailyscrumController extends Controller
{
    public function index()
    {
        //
    }

    public function getAll()
    {
    	try{
	        $data["count"] = dailyscrum::count();
	        $dailyscrum = array();
	        $dataDailyscrum = DB::table('daily_scrum')->join('users','users.id_users','=','daily_scrum.id_users')
                                               ->select('daily_scrum.id_users', 'users.id_users','daily_scrum.team','daily_scrum.activity_yesterday','daily_scrum.activity_today','daily_scrum.problem_yesterday','daily_scrum.solution')
	                                           ->get();

	        foreach ($dataDailyscrum as $p) {
	            $item = [
	              "id"                          => $p->id,
                  "id_users"                    => $p->id_siswa,
                  "activity_yesterday"          => $p->activity_yesterday,
                  "activity_today"              => $p->activity_today,
                  "problem_yesterday"           => $p->problem_yesterday,
                  "solution"                    => $p->solution,
	            ];

	            array_push($dailyscrum, $item);
	        }
	        $data["dailyscrum"] = $dailyscrum;
	        $data["status"] = 1;
	        return response($data);

	    } catch(\Exception $e){
			return response()->json([
			  'status' => '0',
			  'message' => $e->getMessage()
			]);
      	}
    }

    public function store(Request $request)
    {
        try{
    		$validator = Validator::make($request->all(), [
                'id_users'              => 'required|numeric',
    			'team'                  => 'required|string',
				'activity_yesterday'	=> 'required|string|max:255',
                'activity_today'		=> 'required|string|max:255',
                'problem_yesterday'	    => 'required|string|max:255',
                'solution'              => 'required|string|max:255',
    		]);

    		if($validator->fails()){
    			return response()->json([
    				'status'	=> 0,
    				'message'	=> $validator->errors()
    			]);
            }
            if (User::where('id', $request->input('id_users'))->count()>0) {
                if (dailyscrum::where('id', $request->input('id_users'))->count()>0) {
                    $data = new dailyscrum();
                    $data->id_users = $request->input('id_users');
                    $data->team = $request->input('team');
                    $data->activity_yesterday = $request->input('activity_yesterday');
                    $data->activity_today = $request->input('activity_today');
                    $data->problem_yesterday = $request->input('problem_yesterday');
                    $data->solution = $request->input('solution');
                    $data->save();
        
                    return response()->json([
                        'status'	=> '1',
                        'message'	=> 'Data activity berhasil ditambahkan!'
                    ], 201);    
            } else {
                return response()->json([
                    'status' => '0',
		            'message' => 'Data activity tidak ditemukan.'
                ]);
            }
      } else {
            return response()->json([
                'status' => '0',
	                'message' => 'Data users tidak ditemukan.'
            ]);
        }
    }catch(\Exception $e){
        return response()->json([
            'status' => '0',
            'message' => $e->getMessage()
        ]);
    }
}

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
