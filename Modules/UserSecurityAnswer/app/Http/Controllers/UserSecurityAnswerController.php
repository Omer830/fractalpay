<?php

namespace Modules\UserSecurityAnswer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\UserSecurityAnswer\App\Models\UserSecurityAnswer;

class UserSecurityAnswerController extends Controller
{
    // Store Security Question Answer
    public function store(Request $request)
    {
        $data = $request->all();

        // Validate the array input
        $request->validate([
            '*.user_id' => 'required|exists:users,id',
            '*.security_question_id' => 'required|exists:security_questions,id',
            '*.answer' => 'required|string',
        ]);

        $responses = [];
        $errors = [];

        foreach ($data as $item) {
            // Check if an answer already exists for this user and question
            $exists = UserSecurityAnswer::where('user_id', $item['user_id'])
                ->where('security_question_id', $item['security_question_id'])
                ->exists();

            if ($exists) {
                $errors[] = [
                    'security_question_id' => $item['security_question_id'],
                    'message' => 'You have already answered this security question.'
                ];
                continue;
            }

            // Save the answer
            $responses[] = UserSecurityAnswer::create($item);
        }

        if (!empty($errors)) {
            return response()->json([
                'message' => 'Some security questions were not saved.',
                'errors' => $errors,
                'saved' => $responses
            ], 207); // 207: Multi-Status
        }

        return response()->json($responses, 201);
    }


    // Update Security Question Answer
    public function update(Request $request, $id)
    {
        $request->validate([
            'answer' => 'required|string',
        ]);

        $userSecurityAnswer = UserSecurityAnswer::find($id);

        if (!$userSecurityAnswer) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $userSecurityAnswer->update(['answer' => $request->answer]);

        return response()->json(['message' => 'Updated successfully', 'data' => $userSecurityAnswer], 200);
    }

    // Get All Security Questions with Answers for the Authenticated User
    public function index(Request $request)
    {
        $userId = auth()->id(); // Get authenticated user ID

        $securityAnswers = UserSecurityAnswer::with('securityQuestion') // Assuming relation is defined
            ->where('user_id', $userId)
            ->get();

        return response()->json($securityAnswers);
    }

    public function destroy($id)
    {
        $answer = UserSecurityAnswer::find($id);

        if (!$answer) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $answer->delete(); // Soft delete

        return response()->json(['message' => 'Deleted successfully'], 200);
    }
}
