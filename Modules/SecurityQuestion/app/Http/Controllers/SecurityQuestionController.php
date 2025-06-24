<?php

namespace Modules\SecurityQuestion\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\SecurityQuestion\App\Models\SecurityQuestion;

class SecurityQuestionController extends Controller
{
    public function index()
    {
        return SecurityQuestion::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|unique:security_questions,question',
        ]);

        return SecurityQuestion::create($request->all());
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'question' => 'required|string|unique:security_questions,question,' . $id, // Ensure uniqueness excluding the current record
        ]);

        $securityQuestion = SecurityQuestion::find($id);

        if (!$securityQuestion) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $securityQuestion->update($request->only('question'));

        return response()->json(['message' => 'Updated successfully', 'data' => $securityQuestion], 200);
    }

    public function destroy($id)
    {
        $securityQuestion = SecurityQuestion::find($id);

        if (!$securityQuestion) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $securityQuestion->delete();

        return response()->json(['message' => 'Deleted successfully'], 200);
    }
}
