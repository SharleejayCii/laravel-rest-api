<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function store(Request $request){
        if($request->input('type')==='deposit'){
            return $this->deposit(
                $request->input('destination'),
                $request->input('amounth')
            );
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
}
