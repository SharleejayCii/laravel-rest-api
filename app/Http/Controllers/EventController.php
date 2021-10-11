<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function store(Request $request){
        if($request->input('type')==='deposit'){
            return $this->deposit(
                $request->input('destination'),
                $request->input('amounth')
            );
        }else{
            if($request->input('type')==='withdraw'){
                return $this->withdraw(
                    $request->input('origin'),
                    $request->input('amounth')
                );
            }elseif($request->input('type')==='transfer'){
                return $this->transfer(
                    $request->input('origin'),
                    $request->input('destination'),
                    $request->input('amounth')
                );
            }
        }
    }

    private function deposit($destination, $amounth){
        $account = Account::firstOrCreate([
            'id' => $destination
        ]);

        $account->balance += $amounth;
        $account->save();
        return response()->json([
            'destination' => [
                'id' => $account->id,
                'balance' => $account->balance
            ]
        ], 201);
    }

    private function withdraw($origin, $amounth){
        $account = Account::findOrFail($origin);
        $account->balance -= $amounth;
        $account->save();
        return response()->json([
            'origin' => [
                'id' => $account->id,
                'balance' => $account->balance
            ]
        ], 201);
    }

    private function transfer($origin, $destination, $amounth){        
        $accountOrigin = Account::findOrFail($origin);        
        $accountDestination = Account::firstOrCreate([
            'id'=>$destination
        ]);
        
        DB::transaction(function() use ($accountOrigin, $accountDestination, $amounth){    
            $accountOrigin->balance -= $amounth;
            $accountDestination->balance += $amounth;
    
            $accountOrigin->save();
            $accountDestination->save();
        });       
    
        return response()->json([
            'origin' => [
                'id' => $accountOrigin->id,
                'balance' => $accountOrigin->balance
            ],
            'destination' => [
                'id' => $accountDestination->id,
                'balance' => $accountDestination->balance
            ]
        ], 201);
    }
}
