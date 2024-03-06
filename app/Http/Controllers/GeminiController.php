<?php

namespace App\Http\Controllers;

use Gemini\Laravel\Facades\Gemini;


use Illuminate\Http\Request;

class GeminiController extends Controller
{
    public function getSuggestions(Request  $request)
    {
        $validateData = $request->validate([
            'favorite' => 'string|min:3|max:100|required',
            'recommenderType' => 'string|required',
            'numRecommendations' => 'int|min:1|max:10|required',
        ]);
        if ($validateData) {
            $result = Gemini::geminiPro()->generateContent(
                'User Input:
                Favorite Movie or Book: ' . $validateData['favorite'] . '
                Select a Recommender: ' . ($validateData['recommenderType'] == 0 ? "Book" : "Movie") . '
                Number of Recommendations: ' . $validateData['numRecommendations'] . '
            
                Generate personalized recommendations based on the user\'s favorite movie or book, selected recommender type, and the desired number of recommendations. Consider the user\'s preferences and provide relevant suggestions that align with their interests and tastes.
            
                Desired Response Structure is an Array of objects with recommendations.
                
                Response Example: 
                [{"title" : "The Great Gatsby","description" : "A classic novel by F. Scott Fitzgerald, set in the Roaring Twenties, exploring themes of decadence, idealism, and the American Dream.", "rating":4},{"title" : "To Kill a Mockingbird","description" : "A timeless novel by Harper Lee, addressing issues of racial injustice and moral growth through the perspective of a young girl in the American South.", "rating":4.5}]
                DO NOT INCLUDE BACKTICKS IN THE RESPONSE
                GENERATE UNIQUE SUGGESTIONS EVERY TIME
                '

            );
            return response()->json(['data' => json_decode($result->text())]);
        } else {
            return response(422)->json(['message' => 'Invalid data']);
        }
    }
}
