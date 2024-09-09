<?php

namespace App\Http\Controllers;

use Log;
use App\Models\User;
use App\Models\Church;
use Illuminate\Http\Request;
use App\Models\TransferRequest;
use Illuminate\Support\Facades\Hash;

class UssdController extends Controller
{

    private $churches = [
        1 => 'BASE', 2 => 'BUHANDE', 3 => 'BURAMIRA', 4 => 'RUSHOKI',
        5 => 'BUYOGOMA', 6 => 'CYIRI', 7 => 'GASIZA', 8 => 'GATETE',
        9 => 'GIHINGA', 10 => 'GITARE', 11 => 'KARAMBO', 12 => 'KARUHURA',
        13 => 'KIRURI', 14 => 'MAREMBO', 15 => 'MURAMBI', 16 => 'NYANGE',
        17 => 'NYIRANGARAMA', 18 => 'REBERO BETERI', 19 => 'RUGARAMA', 20 => 'RUKORE',
        21 => 'RUSURA', 22 => 'RUVUMBA', 23 => 'TARE', 24 => 'TERAMBERE'
    ];

    public function handleUssd(Request $request)
    {
        $this->request = $request; // Store the request object for later use
        $text = $request->input('text');
        $textArray = explode('*', $text);
        $level = count($textArray);

        if ($level == 1) {
            return $this->loginPrompt();
        }

        if ($level == 2) {
            return $this->passwordPrompt($textArray[1]);
        }

        $user = $this->authenticateUser($textArray[1], $textArray[2]);
        if (!$user) {
            return "END Invalid credentials. Please try again.";
        }

        switch ($level) {
            case 3:
                return $this->showUserInfo($user);
            case 4:
            case 5:
            case 6:
            case 7:
                return $this->handleMainMenuOption($user, $textArray[3]);
            default:
                return "END Invalid option.";
        }
    }


    private function loginPrompt()
    {
        return "CON Welcome to Church USSD Service\nPlease enter your email:";
    }

    private function passwordPrompt($email)
    {
        return "CON Please enter your password:";
    }

    private function authenticateUser($email, $password)
    {
        $user = User::where('email', $email)->first();
        if ($user && Hash::check($password, $user->password)) {
            return $user;
        }
        return null;
    }


    private function transferRequest($user, $input = null)
{
    \Log::info("Transfer request input: " . $input);

    $pendingRequest = TransferRequest::where('christian_id', $user->id)
        ->where('approval_status', 'Pending')
        ->first();

    if ($pendingRequest) {
        return "END You already have a pending transfer request. Please wait for it to be processed.";
    }

    $inputParts = explode('*', $input);
    $step = count($inputParts) - 4; // Subtracting 4 to account for email, password, menu option, and transfer request selection

    \Log::info("Step: " . $step);
    \Log::info("Input parts: " . json_encode($inputParts));

    if ($step <= 0) {
        return $this->displayChurches();
    }

    switch ($step) {
        case 1: // Church selection
            if ($inputParts[4] === 'n') {
                $offset = $this->getOffset($input);
                return $this->displayChurches($offset);
            } elseif ($inputParts[4] === '0') {
                return $this->showUserInfo($user);
            }
            $churchId = intval($inputParts[4]);
            
            \Log::info("Selected church ID: " . $churchId);

            $church = Church::find($churchId);
            
            \Log::info("Church query result: " . ($church ? json_encode($church) : "Not found"));

            if (!$church) {
                return "END Invalid church selection. Church ID: {$churchId}, Query result: " . ($church ? "Found" : "Not found") . ". Please try again.";
            }
            
            return "CON Select the reason for your transfer request:\n" .
                "1. Geographical Relocation\n2. Theological Differences\n" .
                "3. Family Reasons\n4. Work\n5. Church Leadership and Management\n6. Other";

        case 2: // Reason selection
            $churchId = intval($inputParts[4]);
            $reasonCode = $inputParts[5];
            $reasons = [
                '1' => 'Geographical Relocation',
                '2' => 'Theological Differences',
                '3' => 'Family Reasons',
                '4' => 'Work',
                '5' => 'Church Leadership and Management',
                '6' => 'Other'
            ];
            if (!isset($reasons[$reasonCode])) {
                return "END Invalid reason selection. Please try again.";
            }
            $reason = $reasons[$reasonCode];
            
            if ($reason === 'Other') {
                return "CON Please specify the other reason for your transfer request:";
            } else {
                return "CON Please provide additional details for your transfer request:";
            }

        case 3: // Create transfer request
            $churchId = intval($inputParts[4]);
            $reasonCode = $inputParts[5];
            $reasons = [
                '1' => 'Geographical Relocation',
                '2' => 'Theological Differences',
                '3' => 'Family Reasons',
                '4' => 'Work',
                '5' => 'Church Leadership and Management',
                '6' => 'Other'
            ];
            $reason = $reasons[$reasonCode] ?? 'Other';
            $description = $inputParts[6];

            // Create the transfer request
            TransferRequest::create([
                'christian_id' => $user->id,
                'from_church_id' => $user->church_id,
                'to_church_id' => $churchId,
                'reason' => $reason,
                'description' => $description,
                'approval_status' => 'Pending'
            ]);

            return "END Your transfer request has been submitted successfully. You will be notified once it's processed.";

        default:
            return "END Invalid input. Please try again.";
    }
}

private function displayChurches($offset = 0)
{
    $churches = Church::orderBy('id')->skip($offset)->take(5)->get();
    $churchList = "CON Select the church you wish to transfer to:\n";
    foreach ($churches as $church) {
        $churchList .= "{$church->id}. {$church->name}\n";
    }
    if (Church::count() > $offset + 5) {
        $churchList .= "n. Next\n";
    }
    $churchList .= "0. Main Menu";
    return $churchList;
}

    private function getOffset($input)
    {
        $parts = explode('*', $input);
        $nCount = 0;
        foreach ($parts as $part) {
            if ($part === 'n') {
                $nCount++;
            }
        }
        return $nCount * 5;
    }

    private function showUserInfo($user)
    {
        $baptismStatus = $user->baptized ? "Baptized" : "Not baptized";
        $churchInfo = $user->church ? $user->church->name : "Not assigned";

        return "CON Your Information:\n" .
            "Marital status: {$user->marital_status}\n" .
            "Baptism status: {$baptismStatus}\n" .
            "Church: {$churchInfo}\n" .
            "\nMain Menu:\n" .
            "1. Transfer Request\n" .
            "2. Logout";
    }

    private function mainMenu()
    {
        return "CON Main Menu\n1. Check Marital Status\n2. Check Baptism Status\n3. Check Church Information\n4. Check Personal Information\n5. Logout\n6. Exit";
    }



    private function handleMainMenuOption($user, $option)
    {
        switch ($option) {
            case '1':
                return $this->transferRequest($user, $this->request->input('text'));
            case '2':
                return $this->logout();
            default:
                return "END Invalid option selected.";
        }
    }
}